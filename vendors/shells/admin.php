<?php
/**
 * Admin Shell
 *
 * [Short Description]
 *
 * @package default
 * @author Jose Diaz-Gonzalez
 * @version $Id$
 **/
App::import('Lib', 'CakeAdmin.cake_admin');
class AdminShell extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 * @access public
 */
    var $tasks = array('AdminTemplate');

    var $booleans = array();
    var $strings = array();
    var $arrays = array();
    var $array_required = array();

/**
 * Folder handler
 *
 * @var File
 **/
    var $handler;

/**
 * Override main
 *
 * @access public
 */
    function main() {
        // Create the admin object
        $files = $this->find();
        if (!$files) {
            $this->error(__('Admin files not found', true));
        }
        $build = 0;
        foreach ($files as $file) {
            // Create an instance of the particular admin class
            $className = Inflector::camelize(str_replace('.php', '', $file));
            App::import('Lib', $className, array('file' => "admin/{$file}"));
            $admin = new $className;

            // Validate the admin class
            if (!$this->validate($admin)) {
                $this->err(sprintf(
                    __('Validation failed for %s', true),
                    $className
                ));
                continue;
            }

            // Check that paths can be written
            if (($error = $this->checkBuild($admin)) !== true) {
                $this->err(sprintf('%s for %s', $error, $className));
                continue;
            }

            if (!$this->generate($admin)) {
                $this->err(sprintf(
                    __('Error in generating admin for %s', true),
                    $className
                ));
                continue;
            }
            $build++;
        }

        $fails = count($files) - $build;
        if ($build == 0) {
            $this->out(__('Failed to build admin', true));
        }

        $this->out(sprintf(
            __('Admin successfully built for %s models', true),
            count($file)
        ));

        if ($fails !== 0) {
            $this->out(sprintf(
                __('Please fix all %s errors before regenerating admin', true),
                $fails
            ));
        }
    }

/**
 * Finds all CakeAdmin files in app/libs/admin
 *
 * @return array
 * @author Jose Diaz-Gonzalez
 * @todo test me
 */
    function find() {
        $this->handler();
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
 * Validates an Admin class
 *
 * Checks to ensure the types of each variable are set
 * and are valid in the context of the generation
 *
 * If a variable is private or protected access, returns
 * false
 *
 * @param CakeAdmin $admin
 * @return boolean
 * @author Jose Diaz-Gonzalez
 * @todo test me
 **/
    function validate($admin) {
        foreach ($this->booleans as $var) {
            if (!is_bool($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->strings as $var) {
            if (!is_string($admin->$$var) || empty($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->arrays as $var) {
            if (!is_array($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->array_required as $var) {
            if (empty($admin->$$var)) {
                return false;
            }
        }

        return true;
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
 * @author Jose Diaz-Gonzalez
 * @todo test me
 */
    function checkBuild($admin) {
        $this->handler();
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
                return sprintf(__('Directory not writeable: %s', true), $directory);
            }
        }

        return true;
    }

/**
 * Compiles the admin section for a particular model
 *
 * @param CakeAdmin $admin
 * @return boolean
 * @author Jose Diaz-Gonzalez
 * @todo test me
 **/
    function generate($admin) {
        if (!$this->generateAppController($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s App Controller', Inflector::humanize($admin->plugin)));
            $this->out();
            return false;
        }
        if (($metadata = $this->generateController($admin)) == false) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Controller', $this->_controllerName($admin->modelName)));
            $this->out();
            return false;
        }
        if (!$this->generateModel($admin, $metadata))  {
            $this->out();
            $this->out(sprintf('Failed to generate %s Model', $admin->modelName));
            $this->out();
            return false;
        }
        if (!$this->generateViews($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Views', $this->_controllerName($admin->modelName)));
            $this->out();
            return false;
        }
        return true;
    }

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function generateAppController($admin) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $this->AdminTemplate->set(compact('admin'));
        $contents = $this->AdminTemplate->generate($path, 'app_controller');

        $path = APP . 'plugins' . DS . $admin->plugin . DS;
        $filename = $path . $admin->plugin . '_app_controller.php';
        if ($this->createFile($filename, $contents)) {
            return $contents;
        }
        return false;
    }

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function generateModel($admin, $metadata) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'methods';

        $methods = $this->generateMethods($admin, $metadata);

        $this->AdminTemplate->set(compact('methods', 'admin'));
        $contents = $this->AdminTemplate->generate($path, 'model');

        $path = APP . 'plugins' . DS . $admin->plugin . DS . 'models' . DS;
        $filename = $path . Inflector::underscore($admin->modelName);
        if ($this->createFile($filename, $contents)) {
            return $contents;
        }
        return false;
    }



/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function generateController($admin) {
        $path       = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $controllerName = $this->_controllerName($admin->modelName);
        $actions    = $this->generateActions($admin);

        if (!$actions) return false;

        $metadata   = $actions['metadata'];
        $actions    = $actions['actions'];

        $this->AdminTemplate->set(compact('controllerName', 'actions', 'admin'));
        $contents   = $this->AdminTemplate->generate($path, 'controller');

        $path       = APP . 'plugins' . DS . $admin->plugin . DS . 'controllers' . DS;
        $filename   = $path . $this->_controllerPath($controllerName) . '_controller.php';
        if ($this->createFile($filename, $contents)) {
            return $metadata;
        }
        return false;
    }

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function generateViews($admin) {
    }

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function generateActions($admin) {
        $actions = '';
        $actionMetadata = array();
        foreach ($admin->actions as $alias => $action) {
            $this->out(sprintf("Action: %s", $action));

            $plugin = null;
            if (is_array($action)) {
                $plugin = $action['plugin'];
                $action = $action['action'];
            }
            $actionAlias = (ctype_digit($alias)) ? $action : $alias;

            $results = $this->getAction($admin, array(
                'action' => $action,
                'plugin' => $plugin,
                'alias'  => $alias
            ));
            if (!$results) return false;

            list($actionContents, $metadata) = $results;
            $actionMetadata[$alias] = $metadata;
            $actions .= "{$actionContents}\n\n";
        }
        return array('actions' => $actions, 'metadata' => $actionMetadata);
    }

/**
 * Retrieves action contents and parses out yaml metadata
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function getAction($admin, $options = array()) {
        if (!empty($options['plugin'])) {
            $intermediate = 'plugins' . DS . $options['plugin'] . DS;
        } else {
            $intermediate = 'plugins' . DS . 'cake_admin' . DS;
        }

        $path = APP . $intermediate . 'libs' . DS . 'templates' . DS . 'actions';

        $alias = $options['alias'];

        $currentModelName = $this->_modelName($this->_controllerName($admin->modelName)). 'Admin';
        $pluralName = $this->_pluralName($currentModelName);
        $singularName = Inflector::variable($currentModelName);
        $singularHumanName = $this->_singularHumanName($this->_controllerName($admin->modelName));
        $pluralHumanName = $this->_pluralName($this->_controllerName($admin->modelName));

        $this->AdminTemplate->set(compact(
            'admin',
            'currentModelName',
            'pluralName',
            'singularName',
            'singularHumanName',
            'pluralHumanName',
            'alias'
        ));
        $content = $this->AdminTemplate->generate($path, $options['action']);
        return $this->AdminTemplate->parseMetadata($content);
    }

/**
 * Sets up a folder handler
 *
 * Calls App::import('Core', 'Folder') if the handler has not
 * been created in order to create a new folder.
 *
 * @return boolean true if handler is setup, false otherwise
 * @author Jose Diaz-Gonzalez
 * @todo test me
 */
    function handler() {
        if (!$this->handler) {
            App::import('Core', 'Folder');
            $this->handler = new Folder(APP);
        }
        return is_object($this->handler);
    }

}