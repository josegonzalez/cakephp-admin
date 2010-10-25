<?php
class AdminControllerTask extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate');

/**
 * undocumented function
 *
 * @return void
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
 **/
    function generate($admin) {
        $path       = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $controllerName = $this->_controllerName($admin->modelName);
        $actions    = $this->generateContents($admin);

        if (!$actions) return false;

        $metadata   = $actions['metadata'];
        $actions    = $actions['actions'];

        $currentModelName = $this->_modelName($this->_controllerName($admin->modelName)). 'Admin';
        $this->AdminTemplate->set(compact('controllerName', 'currentModelName', 'actions', 'admin'));
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
 **/
    function generateContents($admin) {
        $actions = '';
        $actionMetadata = array();
        foreach ($admin->actions as $alias => $action) {
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
 **/
    function getAction($admin, $options = array()) {
        if (!empty($options['plugin'])) {
            $intermediate = 'plugins' . DS . $options['plugin'] . DS;
        } else {
            $intermediate = 'plugins' . DS . 'cake_admin' . DS;
        }

        $path = APP . $intermediate . 'libs' . DS . 'templates' . DS . 'actions';

        $alias              = $options['alias'];
        $currentModelName   = $this->_modelName($this->_controllerName($admin->modelName)). 'Admin';
        $pluralName         = $this->_pluralName($currentModelName);
        $singularName       = Inflector::variable($currentModelName);
        $singularHumanName  = $this->_singularHumanName($this->_controllerName($admin->modelName));
        $pluralHumanName    = $this->_pluralName($this->_controllerName($admin->modelName));

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

}