<?php
App::uses('AppShell', 'Console/Command');

class AdminViewTask extends AppShell {

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
	function generate($admin) {
		foreach ($admin->actions as $alias => $configuration) {
			if ($configuration['enabled'] !== true) continue;

			$content = $this->getContent($admin, $alias, $configuration);
			if (empty($content)) continue;
			if (!$this->createFile($admin->paths['views'][$alias], $content)) return false;
		}
		return true;
	}

	function getContent($admin, $action, $configuration) {
		$endPath = 'Lib' . DS . 'Templates' . DS . 'Action' . DS;
		if (empty($configuration['plugin'])) {
			$path = APP . $endPath;
		} else {
			$path = $this->pluginDir . $configuration['plugin'] . DS . $endPath;
		}
		$path .= $configuration['type'] . DS . 'View';

		$this->AdminTemplate->set(compact(
			'admin',
			'action',
			'configuration'
		));

		$template = $configuration['type'];
		if (!$template) return false;
		return $this->AdminTemplate->generate($path, $template);
	}

}
