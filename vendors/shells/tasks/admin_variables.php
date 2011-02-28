<?php
class AdminVariablesTask extends Shell {

    function load($admin) {
        // Load an instance of the admin model object
        $plugin = Inflector::camelize($admin->plugin);

        $modelClass         = $admin->adminModelName;
        $primaryKey         = $admin->modelObj->primaryKey;
        $displayField       = $admin->modelObj->displayField;
        $schema             = $admin->modelObj->schema(true);
        $fields             = array_keys($schema);

        $controllerName     = $admin->controllerName;
        $controllerRoute    = $this->_pluralName($admin->modelName);
        $pluginControllerName= $this->_controllerName($admin->modelName . 'Admin');

        $singularVar        = Inflector::variable($modelClass);
        $singularName       = $this->_singularName($modelClass);
        $singularHumanName  = $this->_singularHumanName($this->_controllerName($admin->modelName));
        $pluralVar          = Inflector::variable($pluginControllerName);
        $pluralName         = $this->_pluralName($modelClass);
        $pluralHumanName    = Inflector::humanize($this->_pluralName($controllerName));

        $associations       = $this->__associations($admin->modelObj);

        return compact(
            'admin',
            'adminModelObj',
            'modelClass',
            'primaryKey',
            'displayField',
            'schema',
            'fields',
            'controllerName',
            'controllerRoute',
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
 * Get an Array of all the tables in the supplied connection
 * will halt the script if no tables are found.
 *
 * @param string $useDbConfig Connection name to scan.
 * @return array Array of tables in the database.
 */
    function getAllTables($useDbConfig = null) {
        App::import('Model', 'ConnectionManager', false);

        $tables = array();
        $db =& ConnectionManager::getDataSource($useDbConfig);
        $db->cacheSources = false;
        $usePrefix = empty($db->config['prefix']) ? '' : $db->config['prefix'];
        if ($usePrefix) {
            foreach ($db->listSources() as $table) {
                if (!strncmp($table, $usePrefix, strlen($usePrefix))) {
                    $tables[] = substr($table, strlen($usePrefix));
                }
            }
        } else {
            $tables = $db->listSources();
        }
        if (empty($tables)) {
            $this->err(__('Your database does not have any tables.', true));
            $this->_stop();
        }
        return $tables;
    }

/**
 * Get a model object for a class name.
 *
 * @param string $className Name of class you want model to be.
 * @return object Model instance
 */
    function &_getModelObject($className, $table = null) {
        if (!$table) {
            $table = Inflector::tableize($className);
        }
        $object =& new Model(array('name' => $className, 'table' => $table, 'ds' => $this->connection));
        return $object;
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
                $associations[$type][$assocKey]['alias'] = $assocKey;
                $associations[$type][$assocKey]['className'] = $assocData['className'];
                $associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
                $associations[$type][$assocKey]['primaryKey'] = $model->{$assocKey}->primaryKey;
                $associations[$type][$assocKey]['displayField'] = $model->{$assocKey}->displayField;
                $associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocData['className']));
                $associations[$type][$assocKey]['fields'] =  array_keys($model->{$assocKey}->schema(true));

                if ($type == 'hasAndBelongsToMany') {
                    $associations[$type][$assocKey]['associationForeignKey'] = $assocData['associationForeignKey'];
                    $associations[$type][$assocKey]['joinTable'] = $assocData['joinTable'];
                }
            }
        }
        return $associations;
    }

}