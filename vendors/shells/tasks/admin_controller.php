<?php
class AdminControllerTask extends Shell {

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
    function generateAppController($admin) {
        $path = $this->templateDir . DS . 'classes';

        $this->AdminTemplate->set(compact('admin'));
        $contents = $this->AdminTemplate->generate($path, 'app_controller');

        $path = $this->pluginDir . $admin->plugin . DS;
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
 **/
    function generate($admin) {;
        $actions    = $this->generateContents($admin);
        if (!$actions) return false;

        $this->AdminTemplate->set(compact(
            'admin',
            'actions'
        ));

        $path       = $this->templateDir . DS . 'classes';
        $contents   = $this->AdminTemplate->generate($path, 'controller');
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
        $endPath = 'libs' . DS . 'templates' . DS . 'actions' . DS;
        if (empty($options['plugin'])) {
            $path = APP . $endPath;
        } else {
            $path = $this->pluginDir . $options['plugin'] . DS . $endPath;
        }
        $path .= $options['action'] . DS . 'controllers';

        $alias              = $options['alias'];
        $configuration      = $options['config'];

        $this->AdminTemplate->set(compact(
            'admin',
            'alias',
            'configuration'
        ));
        return $this->AdminTemplate->generate($path, 'actions');
    }

}