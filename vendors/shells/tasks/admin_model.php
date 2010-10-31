<?php
class AdminModelTask extends Shell {

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

        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            if (empty($metadata[$alias])) {
                $actionMetadata = array();
            } else {
                $actionMetadata = $metadata[$alias]['metadata'];
            }

            if (!empty($actionMetadata->finders)) {
                foreach ($actionMetadata->finders as $finder) {
                    $contents = $this->getFinder($admin, array(
                        'config'    => $metadata[$alias]['config'],
                        'action'    => $configuration['type'],
                        'plugin'    => $configuration['plugin'],
                        'alias'     => $alias,
                        'finder'    => $finder,
                    ));
                    if (!empty($contents)) $finders .= "{$contents}\n\n";
                }
            }

            if (!empty($actionMetadata->related)) {
                foreach ($actionMetadata->related as $relatedFinder) {
                    $contents = $this->getRelatedFinder($admin, array(
                        'config'    => $metadata[$alias]['config'],
                        'action'    => $configuration['type'],
                        'plugin'    => $configuration['plugin'],
                        'alias'     => $alias,
                        'related'   => $relatedFinder,
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
        $find = Inflector::camelize($options['action']);
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
        $find = Inflector::camelize($options['action']);
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
        $endPath = 'libs' . DS . 'templates' . DS . 'methods';
        if (empty($options['plugin'])) {
            $path = APP . DS . $endPath;
        } else {
            $path = $this->pluginDir . $options['plugin'] . DS. $endPath;
        }

        $alias              = $options['alias'];
        $configuration      = $options['config'];
        $currentModelName   = $admin->modelName . 'Admin';
        $controllerName     = $this->_controllerName($admin->modelName);
        $pluralName         = $this->_pluralName($currentModelName);
        $singularName       = Inflector::variable($currentModelName);
        $singularHumanName  = $this->_singularHumanName($controllerName);
        $pluralHumanName    = $this->_pluralName($controllerName);

        $this->AdminTemplate->set(compact(
            'find',
            'admin',
            'alias',
            'configuration',
            'currentModelName',
            'pluralName',
            'singularName',
            'singularHumanName',
            'pluralHumanName'
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