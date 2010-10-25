<?php
class AdminModelTask extends Shell {

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
    function generateAppModel($admin) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $this->AdminTemplate->set(compact('admin'));
        $contents = $this->AdminTemplate->generate($path, 'app_model');

        $path = APP . 'plugins' . DS . $admin->plugin . DS;
        $filename = $path . $admin->plugin . '_app_model.php';
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
    function generate($admin, $metadata) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        list($methods, $hasFinders, $hasRelated) = $this->generateContents($admin, $metadata);

        $modelObj = ClassRegistry::init(array(
            'class' => $admin->modelName,
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));
        $associations = array(
            'belongsTo' => array(),
            'hasMany' => array(),
            'hasOne'=> array(),
            'hasAndBelongsToMany' => array()
        );
        $this->listAll($admin->useDbConfig, false);
        $associations = $this->findBelongsTo($modelObj, $associations);
        $associations = $this->findHasOneAndMany($modelObj, $associations);
        $associations = $this->findHasAndBelongsToMany($modelObj, $associations);

        $this->AdminTemplate->set(compact(
            'methods',
            'associations',
            'metadata',
            'hasFinders',
            'hasRelated',
            'admin'
        ));
        $contents = $this->AdminTemplate->generate($path, 'model');

        $path = APP . 'plugins' . DS . $admin->plugin . DS . 'models' . DS;
        $filename = $path . Inflector::underscore($admin->modelName) . '.php';
        if ($this->createFile($filename, $contents)) {
            return $contents;
        }
        return false;
    }

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $metadata
 * @return void
 */
    function generateContents($admin, $metadata) {
        $finders = '';
        $related = '';
        foreach ($admin->actions as $alias => $action) {
            $plugin = null;
            if (is_array($action)) {
                $plugin = $action['plugin'];
                $action = $action['action'];
            }
            $actionAlias = (ctype_digit($alias)) ? $action : $alias;
            $actionMetadata = (isset($metadata[$alias])) ? $metadata[$alias] : array() ;

            if (!empty($actionMetadata->finders)) {
                foreach ($actionMetadata->finders as $finder) {
                    $contents = $this->getFinder($admin, array(
                        'action'    => $action,
                        'plugin'    => $plugin,
                        'alias'     => $alias,
                        'finder'    => $finder
                    ));
                    if (!empty($contents)) $finders .= "{$contents}\n\n";
                }
            }
            if (!empty($actionMetadata->related)) {
                foreach ($actionMetadata->related as $relatedFinder) {
                    $contents = $this->getRelatedFinder($admin, array(
                        'action'    => $action,
                        'plugin'    => $plugin,
                        'alias'     => $alias,
                        'related'   => $relatedFinder
                    ));
                    if (!empty($contents)) $related .= "{$contents}\n\n";
                }
            }
        }
        return array(
            $finders . $related,
            (!empty($finders)) ? true : false,
            (!empty($related)) ? true : false,
        );
    }

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $options
 * @return void
 */
    function getFinder($admin, $options) {
        $path = $this->_getModelMethod($admin, $options['finder'], $options);
        $find = Inflector::camelize($options['finder']);
        return $this->AdminTemplate->generate($path, "_find{$find}");
    }

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $options
 * @return void
 */
    function getRelatedFinder($admin, $options) {
        $path = $this->_getModelMethod($admin, $options['related'], $options);
        $find = Inflector::camelize($options['related']);
        return $this->AdminTemplate->generate($path, "_related{$find}");
    }

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $options
 * @return void
 */
    function _getModelMethod($admin, $find, $options) {
        if (!empty($options['plugin'])) {
            $intermediate = 'plugins' . DS . $options['plugin'] . DS;
        } else {
            $intermediate = 'plugins' . DS . 'cake_admin' . DS;
        }

        $path = APP . $intermediate . 'libs' . DS . 'templates' . DS . 'methods';

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
            'alias',
            'find'
        ));
        return $path;
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
                $associations['belongsTo'][] = array(
                    'alias' => $tmpModelName,
                    'className' => $tmpModelName,
                    'foreignKey' => $fieldName,
                );
            } elseif ($fieldName == 'parent_id') {
                $associations['belongsTo'][] = array(
                    'alias' => 'Parent' . $model->name,
                    'className' => $model->name,
                    'foreignKey' => $fieldName,
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
                        'foreignKey' => $fieldName
                    );
                } elseif ($otherTable == $model->table && $fieldName == 'parent_id') {
                    $assoc = array(
                        'alias' => 'Child' . $model->name,
                        'className' => $model->name,
                        'foreignKey' => $fieldName
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
                    'joinTable' => $otherTable
                );
            } elseif ($otherOffset !== false) {
                $habtmName = $this->_modelName(substr($otherTable, 0, $otherOffset));
                $associations['hasAndBelongsToMany'][] = array(
                    'alias' => $habtmName,
                    'className' => $habtmName,
                    'foreignKey' => $foreignKey,
                    'associationForeignKey' => $this->_modelKey($habtmName),
                    'joinTable' => $otherTable
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

}