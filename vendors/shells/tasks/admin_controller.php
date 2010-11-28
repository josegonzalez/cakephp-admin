<?php
class AdminControllerTask extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate', 'AdminVariables');

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
        $modelClass = "{$admin->modelName}Admin";
        $this->AdminTemplate->set(compact(
            'controllerName',
            'modelClass',
            'actions',
            'admin'
        ));
        $contents   = $this->AdminTemplate->generate($path, 'controller');
        $filename   = array();
        $filename[] = $this->pluginDir;
        $filename[] = $admin->plugin . DS;
        $filename[] = 'controllers' . DS;
        $filename[] = $controllerPath . '_controller.php';
        $filename   = implode($filename);

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
    function generateContents($admin) {
        $actions        = '';

        $plugin = Inflector::camelize($admin->plugin);
        $modelObj = ClassRegistry::init(array(
            'class' => "{$plugin}.{$admin->modelName}Admin",
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            $actionContents = $this->getAction($admin, array(
                'action' => $configuration['type'],
                'plugin' => $configuration['plugin'],
                'alias'  => $alias,
                'config' => $configuration,
                'modelObj'=>$modelObj,
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

        $vars               = $this->AdminVariables->load($admin);
        $this->AdminTemplate->set($vars);
        $this->AdminTemplate->set(compact(
            'alias',
            'configuration'
        ));
        return $this->AdminTemplate->generate($path, 'actions');
    }

/**
 * Returns associations for controllers models.
 *
 * @return  array $associations
 * @access private
 */
    function __associations(&$model) {
        $keys = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
        $associations = array();

        foreach ($keys as $key => $type) {
            foreach ($model->{$type} as $assocKey => $assocData) {
                $associations[$type][$assocKey]['primaryKey'] = $model->{$assocKey}->primaryKey;
                $associations[$type][$assocKey]['displayField'] = $model->{$assocKey}->displayField;
                $associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
                $associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocData['className']));
                $associations[$type][$assocKey]['fields'] =  array_keys($model->{$assocKey}->schema(true));
            }
        }
        return $associations;
    }

}