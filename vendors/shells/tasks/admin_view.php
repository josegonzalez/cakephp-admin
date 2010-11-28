<?php
class AdminViewTask extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate',  'AdminVariables');

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
        $this->templateDir[]    = $this->pluginDir;
        $this->templateDir[]    = 'cake_admin' . DS;
        $this->templateDir[]    = 'libs' . DS;
        $this->templateDir[]    = 'templates' . DS;
        $this->templateDir      = implode($this->templateDir);
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

            $path = array();
            $path[] = APP . 'plugins' . DS . $admin->plugin . DS . 'views' . DS;
            $path[] = $this->_controllerPath($this->_controllerName($admin->modelName)) . DS;
            $path[] = $alias . '.ctp';

            if (!$this->createFile(implode($path), $content)) return false;
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

        $vars               = $this->AdminVariables->load($admin);
        $this->AdminTemplate->set($vars);
        $this->AdminTemplate->set(compact(
            'action',
            'configuration'
        ));

        $template = $configuration['type'];
        if (!$template) return false;
        return $this->AdminTemplate->generate($path, $template);
    }

}