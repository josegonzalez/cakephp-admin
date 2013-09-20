<?php
App::uses('AppShell', 'Console/Command');

class AdminControllerTask extends AppShell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
	var $tasks = array('CakeAdmin.AdminTemplate');

/**
 * Constructed plugin directory
 *
 * @var string
 */
	var $pluginDir = null;

/**
 * Constructed base templateDir
 *
 * @var string
 **/
	var $templateDir = null;

/**
 *  Constructs this Shell instance.
 *
 */
	function __construct(&$dispatch) {
		parent::__construct($dispatch);

		$this->pluginDir        = APP . 'Plugin' . DS;
		$this->templateDir      = array();
		$this->templateDir[]    = dirname(__FILE__);
		$this->templateDir[]    = '..';
		$this->templateDir[]    = '..';
		$this->templateDir[]    = '..';
		$this->templateDir[]    = 'Lib';
		$this->templateDir[]    = 'Templates';
		$this->templateDir      = implode(DS, $this->templateDir);
	}

/**
 * undocumented function
 *
 * @return void
 **/
	function generateAppController($admin) {
		$path = $this->templateDir . DS . 'Class';

		$this->AdminTemplate->set(compact('admin'));
		$contents = $this->AdminTemplate->generate($path, 'AppController');

		$path = $this->pluginDir . Inflector::camelize($admin->plugin) . DS . 'Controller' . DS;
		$filename = $path . Inflector::camelize($admin->plugin) . 'AppController.php';
		if ($this->createFile($filename, $contents)) {
			return $contents;
		}
		return false;
	}

/**
 * undocumented function
 *
 * @return void
 **/
	function generate($admin) {;
		$actions    = $this->generateContents($admin);
		if (!$actions) return false;

		$this->AdminTemplate->set(compact(
			'admin',
			'actions'
		));

		$path       = $this->templateDir . DS . 'Class';
		$contents   = $this->AdminTemplate->generate($path, 'Controller');
		if ($this->createFile($admin->paths['controller'], $contents)) {
			return $contents;
		}
		return false;
	}

/**
 * undocumented function
 *
 * @return void
 **/
	function generateContents($admin) {
		$actions    = '';
		foreach ($admin->actions as $alias => $configuration) {
			if ($configuration['enabled'] !== true) continue;

			$actionContents = $this->getAction($admin, array(
				'action' => $configuration['type'],
				'plugin' => $configuration['plugin'],
				'alias'  => $alias,
				'config' => $configuration,
				'modelObj'=>$admin->modelObj,
			));
			if (!$actionContents) return false;
			$actions .= "{$actionContents}\n\n";
		}

		return $actions;
	}

/**
 * Retrieves action contents
 *
 * @return void
 **/
	function getAction($admin, $options = array()) {
		$endPath = 'Lib' . DS . 'Templates' . DS . 'Action' . DS;
		if (empty($options['plugin'])) {
			$path = APP . $endPath;
		} else {
			$path = $this->pluginDir . $options['plugin'] . DS . $endPath;
		}
		$path .= $options['action'] . DS . 'Controller';

		$alias         = $options['alias'];
		$configuration = $options['config'];

		$this->AdminTemplate->set(compact(
			'admin',
			'alias',
			'configuration'
		));
		return $this->AdminTemplate->generate($path, 'actions');
	}

}
