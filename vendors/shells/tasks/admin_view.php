<?php
class AdminViewTask extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate');

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
        $this->directories();
    }

/**
 * Populates the plugin and template directory properties
 *
 * @return void
 */
    function directories() {
        $this->pluginDir        = APP . 'plugins' . DS;
        $this->templateDir      = array();
        $this->templateDir[]    = dirname(__FILE__);
        $this->templateDir[]    = '..';
        $this->templateDir[]    = '..';
        $this->templateDir[]    = '..';
        $this->templateDir[]    = 'libs';
        $this->templateDir[]    = 'templates';
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
        $endPath = 'libs' . DS . 'templates' . DS . 'actions' . DS;
        if (empty($configuration['plugin'])) {
            $path = APP . $endPath;
        } else {
            $path = $this->pluginDir . $configuration['plugin'] . DS . $endPath;
        }
        $path .= $configuration['type'] . DS . 'views';

        $this->AdminTemplate->set(compact(
            'action',
            'configuration'
        ));

        $template = $configuration['type'];
        if (!$template) return false;
        return $this->AdminTemplate->generate($path, $template);
    }

}