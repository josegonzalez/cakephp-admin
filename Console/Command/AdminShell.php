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

App::uses('CakeAdmin', 'CakeAdmin.Lib');
App::uses('AppShell', 'Console/Command');
App::uses('Folder', 'Utility');

class AdminShell extends AppShell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
	public $tasks = array(
		'CakeAdmin.AdminTemplate',
		'CakeAdmin.AdminModel',
		'CakeAdmin.AdminController',
		'CakeAdmin.AdminView',
	);

/**
 * Holds tables found on connection.
 *
 * @var array
 * @access protected
 */
	protected $_tables = array();

/**
 * Folder handler
 *
 * @var File
 **/
	public $handler;

/**
 *  Constructs this Shell instance.
 *
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);

		$this->templateDir      = array();
		$this->templateDir[]    = dirname(__FILE__);
		$this->templateDir[]    = '..';
		$this->templateDir[]    = '..';
		$this->templateDir[]    = 'Lib';
		$this->templateDir[]    = 'Templates';
		$this->templateDir      = implode(DS, $this->templateDir);
	}

/**
 * Override main
 *
 */
	public function generate() {
		if (!isset($this->params['interactive'])) {
			$this->params['interactive'] = false;
		} else {
			$this->params['interactive'] = true;
		}
		$this->interactive = $this->params['interactive'];
		foreach (array('AdminController', 'AdminModel', 'AdminView') as $task) {
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
			$className = str_replace('.php', '', $file);
			App::uses($className, 'Lib/Admin');
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
			$plugins[Inflector::underscore($admin->plugin)][] = array(
				'title'     => $this->_controllerName($admin->modelName),
				'controller'=> $this->_pluralName($this->_controllerName($admin->modelName)),
				'action'    => $admin->redirectTo,
				'adminClass'=> $admin,
			);
		}

		$this->_generateApp($plugins);

		foreach ($adminClasses as $file => $admin) {
			if (!$this->_create($admin)) {
				$this->err(__('Error in creating admin for %s', $file));
				continue;
			}

			$build++;
		}

		$this->_generateMisc($plugins);

		$fails = count($files) - $skipped - $build;
		if ($build == 0) {
			$this->out(__('Failed to build admin'));
		}

		$this->out(sprintf(
			__('Admin successfully built for %s models'),
			count($file)
		));

		if ($fails !== 0) {
			$this->out(sprintf(
				__('Fix all %s errors before regenerating admin'),
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
	public function _find() {
		$this->_handler();
		$this->handler->cd(APPLIBS);
		$content = $this->handler->read();

		if (empty($content[0])) {
			return false;
		}

		if (!in_array('Admin', $content[0])) {
			return false;
		}

		$this->handler->cd(APPLIBS . 'Admin');
		$content = $this->handler->find('([\w_]+)(.php)');

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
	public function _checkBuild($admin) {
		$this->_handler();
		$this->handler->cd(APP);
		$contents = $this->handler->read();

		if (!in_array('Plugin', $contents[0])) {
			return __("Missing Plugin directory");
		}

		$this->handler->cd(APP . 'Plugin');
		$contents = $this->handler->read();
		$path = APP . 'Plugin' . DS . Inflector::camelize($admin->plugin);
		// Recover if the required plugin directory is missing
		if (!in_array($admin->plugin, $contents[0])) {
			$this->handler->create($path);
		}

		$contents = $this->handler->read();
		if (!in_array(Inflector::camelize($admin->plugin), $contents[0])) {
			return __("Unable to create path: %s", $path);
		}

		// Check all the required MVC directories
		$required = array('Controller', 'Model', 'View');
		$this->handler->cd($path);
		$content = $this->handler->read();

		foreach ($required as $directory) {
			if (!in_array($directory, $content[0])) {
				$this->handler->create($path . DS . $directory);
			}
			$content = $this->handler->read();

			if (!in_array($directory, $content[0])) {
				return __('Missing directory: %s', $directory);
			}
		}

		// Check that the directories and files are writeable by shell
		foreach ($required as $directory) {
			if (!$this->handler->chmod($path . DS .$directory)) {
				return __('Directory not writeable: %s', $directory);
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
	public function _create($admin) {
		if (!$this->AdminModel->generate($admin))  {
			$this->out();
			$this->out(__('Failed to generate %s Model', $admin->modelName));
			$this->out();
			return false;
		}
		if (!$this->AdminController->generate($admin)) {
			$this->out();
			$this->out(__('Failed to generate %s Controller',
				$this->_controllerName($admin->modelName)
			));
			$this->out();
			return false;
		}
		if (!$this->AdminView->generate($admin)) {
			$this->out();
			$this->out(__('Failed to generate %s Views',
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
	public function _generateApp($plugins) {
		$generated = array();

		foreach ($plugins as $plugin => $cakeAdmins) {
			if (in_array($plugin, $generated)) continue;
			$generated[] = $plugin;

			$admin = current($cakeAdmins);
			$admin = $admin['adminClass'];

			if (!$this->AdminController->generateAppController($admin)) {
				$this->out();
				$this->out(__('Failed to generate %sAppController', Inflector::camelize($admin->plugin)));
				$this->out();
				return false;
			}
			if (!$this->AdminModel->generateAppModel($admin)) {
				$this->out();
				$this->out(__('Failed to generate %sAppModel', Inflector::camelize($admin->plugin)));
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
	public function _generateMisc($plugins) {
		foreach ($plugins as $plugin => $cakeAdmins) {
			$pluginPath = APP . 'Plugin' . DS . $plugin;

			// Generate flash elements
			$elementPath = $pluginPath . DS . 'View' . DS . 'Elements' . DS . 'flash';
			foreach (array('error', 'info', 'notice', 'success') as $file) {
				$contents = $this->AdminTemplate->generate(
					$this->templateDir . DS . 'Misc' . DS . 'flash' . DS,
					$file
				);

				$this->createFile($elementPath . DS . $file . '.ctp', $contents);
			}

			// Generate CSS
			$contents = $this->AdminTemplate->generate(
				$this->templateDir . DS . 'Misc' . DS,
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
				$this->templateDir . DS . 'Misc' . DS,
				'layout.default'
			);
			$path = $pluginPath . DS . 'View' . DS . 'Layouts';
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
	public function _handler() {
		if (!$this->handler) {
			$this->handler = new Folder(APP);
		}
		return is_object($this->handler);
	}

/**
* get the option parser.
*
* @return void
*/
public function getOptionParser() {
	$parser = parent::getOptionParser();
	return $parser->description(__d('cake_admin',
		'The CakeAdmin shell generates a backend for your application.'
	))->addSubcommand('generate', array(
		'help' => __d('cake_admin', 'Auto-generates admin sections based on your app/Lib/Admin files'),
	))->addSubcommand('help', array(
		'help' => __d('cake_admin', 'Shows this help message'),
	));
}


}
