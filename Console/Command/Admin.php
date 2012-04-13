<?php
/**
 * Admin Shell
 *
 * Generates admin-like plugins based on composition of cakephp templates
 *
 * @copyright     Copyright 2010, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link          http://josediazgonzalez.com
 * @package       cake_admin
 * @subpackage    cake_admin.vendors.shells
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
if (!class_exists('CakeAdmin')) {
	App::import('Lib', 'CakeAdmin.cake_admin');
}
if (!class_exists('Shell')) {
	App::import('Core', 'Shell');
}
class AdminShell extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array(
        'AdminTemplate',
        'AdminModel',
        'AdminController',
        'AdminView',
    );

/**
 * Holds tables found on connection.
 *
 * @var array
 * @access protected
 */
    var $_tables = array();

/**
 * Folder handler
 *
 * @var File
 **/
    var $handler;

/**
 *  Constructs this Shell instance.
 *
 */
    function __construct(&$dispatch) {
        parent::__construct($dispatch);

        $this->templateDir      = array();
        $this->templateDir[]    = dirname(__FILE__);
        $this->templateDir[]    = '..';
        $this->templateDir[]    = '..';
        $this->templateDir[]    = 'libs';
        $this->templateDir[]    = 'templates';
        $this->templateDir      = implode(DS, $this->templateDir);
    }

/**
 * Override main
 *
 */
    function generate() {
        if (!isset($this->params['interactive'])) {
            $this->params['interactive'] = false;
        } else {
            $this->params['interactive'] = true;
        }
        $this->interactive = $this->params['interactive'];
        foreach ($this->tasks as $task) {
            $this->{$task}->interactive = $this->params['interactive'];
        }

        // Create the admin object
        $files = $this->_find();
        if (!$files) {
            $this->error(__('Admin files not found', true));
        }

        $build = 0;
        $skipped = 0;
        $plugins = array();
        $adminClasses = array();
        foreach ($files as $file) {
            // Create an instance of the particular admin class
            $className = Inflector::camelize(str_replace('.php', '', $file));
            App::import('Lib', $className, array('file' => "admin/{$file}"));
            $admin = new $className();

            if (!$admin->enabled) {
                $skipped++;
                continue;
            }

            // Check that paths can be written
            if (($error = $this->_checkBuild($admin)) !== true) {
                $this->err(sprintf('%s for %s', $error, $className));
                continue;
            }

            $adminClasses[$file] = $admin;
            $plugins[$admin->plugin][] = array(
                'title'     => $this->_controllerName($admin->modelName),
                'controller'=> $this->_pluralName($this->_controllerName($admin->modelName)),
                'action'    => $admin->redirectTo,
                'adminClass'=> $admin,
            );
        }

        $this->_generateApp($plugins);

        foreach ($adminClasses as $file => $admin) {
            if (!$this->_create($admin)) {
                $this->err(sprintf(
                    __('Error in creating admin for %s', true),
                    $className
                ));
                continue;
            }

            $build++;
        }

        $this->_generateMisc($plugins);

        $fails = count($files) - $skipped - $build;
        if ($build == 0) {
            $this->out(__('Failed to build admin', true));
        }

        $this->out(sprintf(
            __('Admin successfully built for %s models', true),
            count($file)
        ));

        if ($fails !== 0) {
            $this->out(sprintf(
                __('Fix all %s errors before regenerating admin', true),
                $fails
            ));
        }
    }

/**
 * Finds all CakeAdmin files in app/libs/admin
 *
 * @return array
 * @todo test me
 */
    function _find() {
        $this->_handler();
        $this->handler->cd(APPLIBS);
        $content = $this->handler->read();

        if (empty($content[0])) {
            return false;
        }

        if (!in_array('admin', $content[0])) {
            return false;
        }

        $this->handler->cd(APPLIBS . 'admin');
        $content = $this->handler->find('([a-z_]+)(.php)');

        return (empty($content)) ? false : $content;
    }

/**
 * Checks that directories are writeable
 *
 * Checks the 'plugin' prefix class variable
 * to ensure that the plugin path, and all
 * subpaths, are writeable by the system.
 *
 * Also ensures that templates are available for
 * all actions, plugins, etc
 *
 * @param CakeAdmin $admin
 * @return mixed boolean true if successful, string error message otherwise
 * @todo test me
 */
    function _checkBuild($admin) {
        $this->_handler();
        $this->handler->cd(APP);
        $contents = $this->handler->read();

        if (!in_array('plugins', $contents[0])) {
            return __("Missing Plugin directory", true);
        }

        $this->handler->cd(APP . 'plugins');
        $contents = $this->handler->read();
        $path = APP . 'plugins' . DS . $admin->plugin;
        // Recover if the required plugin directory is missing
        if (!in_array($admin->plugin, $contents[0])) {
            $this->handler->create($path);
        }

        $contents = $this->handler->read();
        if (!in_array($admin->plugin, $contents[0])) {
            return sprintf(__("Unable to create path: %s", true), $path);
        }

        // Check all the required MVC directories
        $required = array('controllers', 'models', 'views');
        $this->handler->cd($path);
        $content = $this->handler->read();

        foreach ($required as $directory) {
            if (!in_array($directory, $content[0])) {
                $this->handler->create($path . DS . $directory);
            }
            $content = $this->handler->read();

            if (!in_array($directory, $content[0])) {
                return sprintf(__('Missing directory: %s', true), $directory);
            }
        }

        // Check that the directories and files are writeable by shell
        foreach ($required as $directory) {
            if (!$this->handler->chmod($path . DS .$directory)) {
                return sprintf(__('Directory not writeable: %s', true), 
                    $directory
                );
            }
        }

        return true;
    }

/**
 * Compiles the admin section for a particular model
 *
 * @param CakeAdmin $admin
 * @return boolean
 * @todo test me
 **/
    function _create($admin) {
        if (!$this->AdminModel->generate($admin))  {
            $this->out();
            $this->out(sprintf('Failed to generate %s Model',
                $admin->modelName
            ));
            $this->out();
            return false;
        }
        if (!$this->AdminController->generate($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Controller',
                $this->_controllerName($admin->modelName)
            ));
            $this->out();
            return false;
        }
        if (!$this->AdminView->generate($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Views',
                $this->_controllerName($admin->modelName)
            ));
            $this->out();
            return false;
        }
        return true;
    }

/**
 * Generates App classes for each plugin
 *
 * @return boolean
 * @author Jose Diaz-Gonzalez
 **/
    function _generateApp($plugins) {
        $generated = array();

        foreach ($plugins as $plugin => $cakeAdmins) {
            if (in_array($plugin, $generated)) continue;
            $generated[] = $plugin;

            $admin = current($cakeAdmins);
            $admin = $admin['adminClass'];

            if (!$this->AdminController->generateAppController($admin)) {
                $this->out();
                $this->out(sprintf('Failed to generate %s AppController',
                    Inflector::humanize($admin->plugin)
                ));
                $this->out();
                return false;
            }
            if (!$this->AdminModel->generateAppModel($admin)) {
                $this->out();
                $this->out(sprintf('Failed to generate %s AppModel',
                    Inflector::humanize($admin->plugin)
                ));
                $this->out();
                return false;
            }
        }
        return true;
    }

/**
 * Adds extra data for each generated plugin, such as
 * layouts, css, and elements
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function _generateMisc($plugins) {
        foreach ($plugins as $plugin => $cakeAdmins) {
            $pluginPath = APP . 'plugins' . DS . $plugin;

            // Generate flash elements
            $elementPath = $pluginPath . DS . 'views' . DS . 'elements' . DS . 'flash';
            $files = array('error', 'info', 'notice', 'success');
            foreach ($files as $file) {
                $contents = $this->AdminTemplate->generate(
                    $this->templateDir . DS . 'misc' . DS . 'flash' . DS,
                    $file
                );

                $this->createFile($elementPath . DS . $file . '.ctp', $contents);
            }

            // Generate CSS
            $contents = $this->AdminTemplate->generate(
                $this->templateDir . DS . 'misc' . DS,
                'cake.admin.generic.min'
            );
            $path = $pluginPath . DS . 'webroot' . DS . 'css';
            $this->createFile($path . DS . 'cake.admin.generic.min.css', $contents);

            // Generate Layout
            $this->AdminTemplate->set(compact(
                'plugin',
                'plugins'
            ));
            $contents = $this->AdminTemplate->generate(
                $this->templateDir . DS . 'misc' . DS,
                'layout.default'
            );
            $path = $pluginPath . DS . 'views' . DS . 'layouts';
            $this->createFile($path . DS . 'default.ctp', $contents);
        }
    }

/**
 * Sets up a folder handler
 *
 * Calls App::import('Core', 'Folder') if the handler has not
 * been created in order to create a new folder.
 *
 * @return boolean true if handler is setup, false otherwise
 * @todo test me
 */
    function _handler() {
        if (!$this->handler) {
            App::import('Core', 'Folder');
            $this->handler = new Folder(APP);
        }
        return is_object($this->handler);
    }

/**
 * Displays help contents
 *
 * @access public
 */
    function help() {
        $help = <<<TEXT
The CakeAdmin Shell
---------------------------------------------------------------
Usage: cake admin generate
---------------------------------------------------------------
Params:


Commands:

    admin generate
        auto-generates admin sections based on your app/libs/admin files

    my help
        shows this help message.

TEXT;
        $this->out($help);
        $this->_stop();
    }

}