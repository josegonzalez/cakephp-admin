<?php
/**
 * Admin Shell
 *
 * [Short Description]
 *
 * @package cake_admin
 * @subpackage cake_admin.vendors.shells
 * @author Jose Diaz-Gonzalez
 * @version $Id$
 **/
App::import('Lib', 'CakeAdmin.cake_admin');
class AdminShell extends Shell {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
    var $tasks = array('AdminTemplate');

/**
 * Holds tables found on connection.
 *
 * @var array
 * @access protected
 */
    var $_tables = array();

    var $booleans = array();
    var $strings = array();
    var $arrays = array();
    var $array_required = array();

/**
 * Folder handler
 *
 * @var File
 **/
    var $handler;

/**
 * Override main
 *
 */
    function main() {
        // Create the admin object
        $files = $this->find();
        if (!$files) {
            $this->error(__('Admin files not found', true));
        }
        $build = 0;
        foreach ($files as $file) {
            // Create an instance of the particular admin class
            $className = Inflector::camelize(str_replace('.php', '', $file));
            App::import('Lib', $className, array('file' => "admin/{$file}"));
            $admin = new $className;

            // Validate the admin class
            if (!$this->validate($admin)) {
                $this->err(sprintf(
                    __('Validation failed for %s', true),
                    $className
                ));
                continue;
            }

            // Check that paths can be written
            if (($error = $this->checkBuild($admin)) !== true) {
                $this->err(sprintf('%s for %s', $error, $className));
                continue;
            }

            if (!$this->generate($admin)) {
                $this->err(sprintf(
                    __('Error in generating admin for %s', true),
                    $className
                ));
                continue;
            }
            $build++;
        }

        $fails = count($files) - $build;
        if ($build == 0) {
            $this->out(__('Failed to build admin', true));
        }

        $this->out(sprintf(
            __('Admin successfully built for %s models', true),
            count($file)
        ));

        if ($fails !== 0) {
            $this->out(sprintf(
                __('Please fix all %s errors before regenerating admin', true),
                $fails
            ));
        }
    }

/**
 * Finds all CakeAdmin files in app/libs/admin
 *
 * @return array
 * @todo test me
 */
    function find() {
        $this->handler();
        $this->handler->cd(APPLIBS);
        $content = $this->handler->read();

        if (empty($content[0])) {
            return false;
        }

        if (!in_array('admin', $content[0])) {
            return false;
        }

        $this->handler->cd(APPLIBS . 'admin');
        $content = $this->handler->find('([a-z_]+)(.php)');

        return (empty($content)) ? false : $content;
    }

/**
 * Validates an Admin class
 *
 * Checks to ensure the types of each variable are set
 * and are valid in the context of the generation
 *
 * If a variable is private or protected access, returns
 * false
 *
 * @param CakeAdmin $admin
 * @return boolean
 * @todo test me
 **/
    function validate($admin) {
        foreach ($this->booleans as $var) {
            if (!is_bool($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->strings as $var) {
            if (!is_string($admin->$$var) || empty($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->arrays as $var) {
            if (!is_array($admin->$$var)) {
                return false;
            }
        }

        foreach ($this->array_required as $var) {
            if (empty($admin->$$var)) {
                return false;
            }
        }

        return true;
    }

/**
 * Checks that directories are writeable
 *
 * Checks the 'plugin' prefix class variable
 * to ensure that the plugin path, and all
 * subpaths, are writeable by the system.
 *
 * Also ensures that templates are available for
 * all actions, plugins, etc
 *
 * @param CakeAdmin $admin
 * @return mixed boolean true if successful, string error message otherwise
 * @todo test me
 */
    function checkBuild($admin) {
        $this->handler();
        $this->handler->cd(APP);
        $contents = $this->handler->read();

        if (!in_array('plugins', $contents[0])) {
            return __("Missing Plugin directory", true);
        }

        $this->handler->cd(APP . 'plugins');
        $contents = $this->handler->read();
        $path = APP . 'plugins' . DS . $admin->plugin;
        // Recover if the required plugin directory is missing
        if (!in_array($admin->plugin, $contents[0])) {
            $this->handler->create($path);
        }

        $contents = $this->handler->read();
        if (!in_array($admin->plugin, $contents[0])) {
            return sprintf(__("Unable to create path: %s", true), $path);
        }

        // Check all the required MVC directories
        $required = array('controllers', 'models', 'views');
        $this->handler->cd($path);
        $content = $this->handler->read();

        foreach ($required as $directory) {
            if (!in_array($directory, $content[0])) {
                $this->handler->create($path . DS . $directory);
            }
            $content = $this->handler->read();

            if (!in_array($directory, $content[0])) {
                return sprintf(__('Missing directory: %s', true), $directory);
            }
        }

        // Check that the directories and files are writeable by shell
        foreach ($required as $directory) {
            if (!$this->handler->chmod($path . DS .$directory)) {
                return sprintf(__('Directory not writeable: %s', true), $directory);
            }
        }

        return true;
    }

/**
 * Compiles the admin section for a particular model
 *
 * @param CakeAdmin $admin
 * @return boolean
 * @todo test me
 **/
    function generate($admin) {
        if (!$this->generateAppController($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s AppController', Inflector::humanize($admin->plugin)));
            $this->out();
            return false;
        }
        if (($metadata = $this->generateController($admin)) == false) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Controller', $this->_controllerName($admin->modelName)));
            $this->out();
            return false;
        }
        if (!$this->generateAppModel($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s AppModel', Inflector::humanize($admin->plugin)));
            $this->out();
            return false;
        }
        if (!$this->generateModel($admin, $metadata))  {
            $this->out();
            $this->out(sprintf('Failed to generate %s Model', $admin->modelName));
            $this->out();
            return false;
        }
        if (!$this->generateViews($admin)) {
            $this->out();
            $this->out(sprintf('Failed to generate %s Views', $this->_controllerName($admin->modelName)));
            $this->out();
            return false;
        }
        return true;
    }

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
    function generateModel($admin, $metadata) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $methods = $this->generateMethods($admin, $metadata);

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

        $this->AdminTemplate->set(compact('methods', 'associations', 'metadata', 'admin'));
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
    function generateMethods($admin, $metadata) {
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
        return $finders . $related;
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

/**
 * undocumented function
 *
 * @return void
 **/
    function generateController($admin) {
        $path       = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $controllerName = $this->_controllerName($admin->modelName);
        $actions    = $this->generateActions($admin);

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
    function generateViews($admin) {
    }

/**
 * undocumented function
 *
 * @return void
 **/
    function generateActions($admin) {
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

/**
 * Sets up a folder handler
 *
 * Calls App::import('Core', 'Folder') if the handler has not
 * been created in order to create a new folder.
 *
 * @return boolean true if handler is setup, false otherwise
 * @todo test me
 */
    function handler() {
        if (!$this->handler) {
            App::import('Core', 'Folder');
            $this->handler = new Folder(APP);
        }
        return is_object($this->handler);
    }

}