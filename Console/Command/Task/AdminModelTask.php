<?php
App::uses('AppShell', 'Console/Command');

class AdminModelTask extends AppShell {

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
	function generateAppModel($admin) {
		$path = $this->templateDir . DS . 'Class';

		$this->AdminTemplate->set(compact('admin'));
		$contents = $this->AdminTemplate->generate($path, 'AppModel');

		$path = APP . 'Plugin' . DS . Inflector::camelize($admin->plugin) . DS . 'Model' . DS;
		$filename = $path . Inflector::camelize($admin->plugin) . 'AppModel.php';
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
	function generate($admin) {
		$path = $this->templateDir . DS . 'Class';

		list($methods, $hasFinders, $hasRelated) = $this->generateContents($admin);

		$this->AdminTemplate->set(compact(
			'admin',
			'methods',
			'hasFinders',
			'hasRelated'
		));
		$contents = $this->AdminTemplate->generate($path, 'Model');

		if ($this->createFile($admin->paths['model'], $contents)) {
			return $contents;
		}
		return false;
	}

/**
 * undocumented function
 *
 * @param object $admin
 * @param object $modelObj
 * @return void
 */
	function generateContents($admin) {
		$methods = '';
		$finders = false;
		$related = false;
		foreach ($admin->actions as $alias => $configuration) {
			if ($configuration['enabled'] !== true) continue;

			$contents = $this->getMethods($admin, array(
				'action'        => $configuration['type'],
				'plugin'        => $configuration['plugin'],
				'alias'         => $alias,
			));

			if (!empty($contents)) {
				$methods .= "{$contents}\n\n";
				if (in_array('find', (array) $configuration['methods'])) $finders = true;
				if (in_array('related', (array) $configuration['methods'])) $related = true;
			}
		}

		return array($methods, $finders, $related);
	}

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $options
 * @return void
 */
	function getMethods($admin, $options) {
		$endPath = 'Lib' . DS . 'Templates' . DS . 'Action' . DS;
		if (empty($options['plugin'])) {
			$path = APP . $endPath;
		} else {
			$path = $this->pluginDir . $options['plugin'] . DS . $endPath;
		}
		$path  .= $options['action'] . DS . 'Model';

		$find   = $options['alias'];
		$alias  = $options['alias'];

		$this->AdminTemplate->set(compact(
			'admin',
			'alias',
			'find'
		));
		return $this->AdminTemplate->generate($path, "methods");
	}

}
