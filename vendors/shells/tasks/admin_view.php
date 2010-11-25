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
        $success = true;
        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            $content = $this->getContent($admin, $alias, $configuration);
            if (empty($content)) continue;

            $path = array();
            $path[] = APP . 'plugins' . DS . $admin->plugin . DS . 'views' . DS;
            $path[] = $this->_controllerPath($this->_controllerName($admin->modelName)) . DS;
            $path[] = $alias . '.ctp';

            if (!$this->createFile(implode($path), $content)) $success = false;
        }
    }

    function getContent($admin, $action, $configuration) {
        $endPath = 'libs' . DS . 'templates' . DS . 'actions' . DS;
        if (empty($configuration['plugin'])) {
            $path = APP . $endPath;
        } else {
            $path = $this->pluginDir . $configuration['plugin'] . DS . $endPath;
        }
        $path .= $configuration['type'] . DS . 'views';

        $vars               = $this->__loadVariables($admin);
        $this->AdminTemplate->set($vars);
        $this->AdminTemplate->set(compact(
            'admin',
            'modelObj',
            'action',
            'configuration'
        ));

        $template = $configuration['type'];
        if (!$template) return false;
        return $this->AdminTemplate->generate($path, $template);
    }

    function __loadVariables($admin) {
        $plugin = Inflector::camelize($admin->plugin);
        $modelObj = ClassRegistry::init(array(
            'class' => "{$plugin}.{$admin->modelName}Admin",
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        $modelClass         = $admin->modelName . 'Admin';
        $primaryKey         = $modelObj->primaryKey;
        $displayField       = $modelObj->displayField;
        $schema             = $modelObj->schema(true);
        $fields             = array_keys($schema);

        $controllerName     = $this->_controllerName($admin->modelName . 'Admin');
        $pluginControllerName= $this->_pluralName($admin->modelName);

        $singularVar        = Inflector::variable($modelClass);
        $singularName       = $this->_singularName($modelClass);
        $singularHumanName  = $this->_singularHumanName($controllerName);
        $pluralVar          = Inflector::variable($controllerName);
        $pluralName         = $this->_pluralName($modelClass);
        $pluralHumanName    = $this->_pluralName($controllerName);
        $associations       = $this->__associations($modelObj);

        return compact(
            'modelClass',
            'primaryKey',
            'displayField',
            'schema',
            'fields',
            'controllerName',
            'pluginControllerName',
            'singularVar',
            'singularName',
            'singularHumanName',
            'pluralVar',
            'pluralName',
            'pluralHumanName',
            'associations'
        );
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