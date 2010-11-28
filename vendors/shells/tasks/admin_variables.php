<?php
class AdminVariablesTask extends Shell {

    function load($admin, $useDbAssociations = false) {
        // Load an instance of the admin model object
        $plugin = Inflector::camelize($admin->plugin);
        $modelObj = ClassRegistry::init(array(
            'class' => "{$plugin}.{$admin->modelName}Admin",
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        $adminModelObj = ClassRegistry::init(array(
            'class' => "{$plugin}.{$admin->modelName}Admin",
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        $modelClass         = $admin->modelName . 'Admin';
        $primaryKey         = $adminModelObj->primaryKey;
        $displayField       = $adminModelObj->displayField;
        $schema             = $adminModelObj->schema(true);
        $fields             = array_keys($schema);

        $controllerName     = $this->_controllerName($admin->modelName);
        $controllerRoute    = $this->_pluralName($admin->modelName);
        $pluginControllerName= $this->_controllerName($admin->modelName . 'Admin');

        $singularVar        = Inflector::variable($modelClass);
        $singularName       = $this->_singularName($modelClass);
        $singularHumanName  = $this->_singularHumanName($this->_controllerName($admin->modelName));
        $pluralVar          = Inflector::variable($pluginControllerName);
        $pluralName         = $this->_pluralName($modelClass);
        $pluralHumanName    = Inflector::humanize($this->_pluralName($controllerName));
        if ($useDbAssociations) {
            $associations   = $this->loadAssociations($modelObj, $admin->useDbConfig);
        } else {
            $associations   = $this->__associations($adminModelObj);
        }

        return compact(
            'admin',
            'modelObj',
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

    function loadAssociations($modelObj, $dbConfig) {
        $associations = array(
            'belongsTo' => array(),
            'hasMany' => array(),
            'hasOne'=> array(),
            'hasAndBelongsToMany' => array()
        );

        $this->listAll($dbConfig, false);
        $associations = $this->findBelongsTo($modelObj, $associations);
        $associations = $this->findHasOneAndMany($modelObj, $associations);
        $associations = $this->findHasAndBelongsToMany($modelObj, $associations);
        return $associations;
    }

/**
 * Find belongsTo relations and add them to the associations list.
 *
 * @param object $model Model instance of model being generated.
 * @param array $associations Array of inprogress associations
 * @return array $associations with belongsTo added in.
 */
    function findBelongsTo(&$model, $associations) {
        $fields = $model->schema(true);
        foreach ($fields as $fieldName => $field) {
            $offset = strpos($fieldName, '_id');
            if ($fieldName != $model->primaryKey && $fieldName != 'parent_id' && $offset !== false) {
                $tmpModelName = $this->_modelNameFromKey($fieldName);
                $tmpModelObj = ClassRegistry::init($tmpModelName);
                $associations['belongsTo'][] = array(
                    'alias' => $tmpModelName,
                    'className' => $tmpModelName,
                    'foreignKey' => $fieldName,
                    'primaryKey' => $tmpModelObj->primaryKey,
                    'displayField' => $tmpModelObj->displayField,
                );
            } elseif ($fieldName == 'parent_id') {
                $associations['belongsTo'][] = array(
                    'alias' => 'Parent' . $model->name,
                    'className' => $model->name,
                    'foreignKey' => $fieldName,
                    'primaryKey' => $model->primaryKey,
                    'displayField' => $model->displayField,
                );
            }
        }
        return $associations;
    }

/**
 * Find the hasOne and HasMany relations and add them to associations list
 *
 * @param object $model Model instance being generated
 * @param array $associations Array of inprogress associations
 * @return array $associations with hasOne and hasMany added in.
 */
    function findHasOneAndMany(&$model, $associations) {
        $foreignKey = $this->_modelKey($model->name);
        foreach ($this->_tables as $otherTable) {
            $tempOtherModel = $this->_getModelObject($this->_modelName($otherTable), $otherTable);
            $modelFieldsTemp = $tempOtherModel->schema(true);

            $pattern = '/_' . preg_quote($model->table, '/') . '|' . preg_quote($model->table, '/') . '_/';
            $possibleJoinTable = preg_match($pattern , $otherTable);
            if ($possibleJoinTable == true) {
                continue;
            }
            foreach ($modelFieldsTemp as $fieldName => $field) {
                $assoc = false;
                if ($fieldName != $model->primaryKey && $fieldName == $foreignKey) {
                    $assoc = array(
                        'alias' => $tempOtherModel->name,
                        'className' => $tempOtherModel->name,
                        'foreignKey' => $fieldName,
                        'primaryKey' => $tempOtherModel->primaryKey,
                        'displayField' => $tempOtherModel->displayField,
                    );
                } elseif ($otherTable == $model->table && $fieldName == 'parent_id') {
                    $assoc = array(
                        'alias' => 'Child' . $model->name,
                        'className' => $model->name,
                        'foreignKey' => $fieldName,
                        'primaryKey' => $model->primaryKey,
                        'displayField' => $model->displayField,
                    );
                }
                if ($assoc) {
                    $associations['hasOne'][] = $assoc;
                    $associations['hasMany'][] = $assoc;
                }

            }
        }
        return $associations;
    }

/**
 * Find the hasAndBelongsToMany relations and add them to associations list
 *
 * @param object $model Model instance being generated
 * @param array $associations Array of inprogress associations
 * @return array $associations with hasAndBelongsToMany added in.
 */
    function findHasAndBelongsToMany(&$model, $associations) {
        $foreignKey = $this->_modelKey($model->name);
        foreach ($this->_tables as $otherTable) {
            $tempOtherModel = $this->_getModelObject($this->_modelName($otherTable), $otherTable);
            $modelFieldsTemp = $tempOtherModel->schema(true);

            $offset = strpos($otherTable, $model->table . '_');
            $otherOffset = strpos($otherTable, '_' . $model->table);

            if ($offset !== false) {
                $offset = strlen($model->table . '_');
                $habtmName = $this->_modelName(substr($otherTable, $offset));
                $associations['hasAndBelongsToMany'][] = array(
                    'alias' => $habtmName,
                    'className' => $habtmName,
                    'foreignKey' => $foreignKey,
                    'associationForeignKey' => $this->_modelKey($habtmName),
                    'joinTable' => $otherTable,
                );
            } elseif ($otherOffset !== false) {
                $habtmName = $this->_modelName(substr($otherTable, 0, $otherOffset));
                $associations['hasAndBelongsToMany'][] = array(
                    'alias' => $habtmName,
                    'className' => $habtmName,
                    'foreignKey' => $foreignKey,
                    'associationForeignKey' => $this->_modelKey($habtmName),
                    'joinTable' => $otherTable,
                );
            }
        }
        return $associations;
    }

/**
 * outputs the a list of possible models or controllers from database
 *
 * @param string $useDbConfig Database configuration name
 */
    function listAll($useDbConfig = null) {
        $this->_tables = $this->getAllTables($useDbConfig);
        $this->connection = $useDbConfig;
        return $this->_tables;
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