<?php
class AdminControllerTask extends Shell {

    var $pluginDir = null;

    function __construct(&$dispatch) {
        parent::__construct($dispatch);
        $this->directories();
    }

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate');

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
    function generateAppController($admin) {
        $path = $this->templateDir . 'classes';

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
        $controllerName = $this->_controllerName($admin->modelName);
        $controllerPath = $this->_controllerPath($controllerName);
        $actions        = $this->generateContents($admin);

        if (!$actions) return false;

        $path           = $this->templateDir . 'classes';
        $metadata       = $actions['metadata'];
        $actions        = $actions['actions'];
        $currentModelName = "{$admin->modelName}Admin";
        $this->AdminTemplate->set(compact(
            'controllerName',
            'currentModelName',
            'actions',
            'admin'
        ));
        $contents   = $this->AdminTemplate->generate($path, 'controller');
        $filename   = array();
        $filename[] = $this->pluginDir;
        $filename[] = $admin->plugin . DS;
        $filename[] = 'controllers' . DS;
        $filename[] = $controllerPath . '_controller.php';
        $filename   = implode($path);

        if ($this->createFile($filename, $contents)) {
            return $metadata;
        }
        return false;
    }

/**
 * undocumented function
 *
 * @return void
 **/
    function generateContents($admin) {
        $actions        = '';
        $metadata       = array();

        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            $results = $this->getAction($admin, array(
                'action' => $configuration['type'],
                'plugin' => $configuration['plugin'],
                'alias'  => $alias,
                'config' => $configuration
            ));
            if (!$results) return false;

            list($actionContents, $actionMetadata) = $results;
            $metadata[$alias] = array(
                'config'    => $configuration,
                'metadata'  => $actionMetadata
            );
            $actions .= "{$actionContents}\n\n";
        }

        return array('actions' => $actions, 'metadata' => $metadata);
    }

/**
 * Retrieves action contents and parses out yaml metadata
 *
 * @return void
 **/
    function getAction($admin, $options = array()) {
        $endPath = 'libs' . DS . 'templates' . DS . 'actions';
        if (empty($options['plugin'])) {
            $path = APP . DS . $endPath;
        } else {
            $path = $this->pluginsDir . $options['plugin'] . $endPath;
        }

        $alias              = $options['alias'];
        $configuration      = $options['configuration'];
        $currentModelName   = $admin->modelName . 'Admin';
        $controllerName     = $this->_controllerName($admin->modelName);
        $pluralName         = $this->_pluralName($currentModelName);
        $singularName       = Inflector::variable($currentModelName);
        $singularHumanName  = $this->_singularHumanName($controllerName);
        $pluralHumanName    = $this->_pluralName($controllerName);

        $this->AdminTemplate->set(compact(
            'admin',
            'configuration',
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

}