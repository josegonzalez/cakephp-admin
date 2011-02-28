<?php
class AdminModelTask extends Shell {

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
    function generate($admin) {
        $path = APP . 'plugins' . DS . 'cake_admin' . DS . 'libs' . DS . 'templates' . DS . 'classes';

        $modelObj = ClassRegistry::init(array(
            'class' => $admin->modelName,
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));


        list($methods, $hasFinders, $hasRelated) = $this->generateContents($admin, $modelObj);

        $associations = $this->AdminVariables->loadAssociations($modelObj, $admin);
        $this->AdminTemplate->set(compact(
            'methods',
            'associations',
            'hasFinders',
            'hasRelated',
            'admin'
        ));
        $contents = $this->AdminTemplate->generate($path, 'model');

        if ($this->createFile($admin->paths['model'], $contents)) {
            return $contents;
        }
        return false;
    }

/**
 * undocumented function
 *
 * @param object $admin
 * @param object $modelObj
 * @return void
 */
    function generateContents($admin, $modelObj) {
        $methods = '';
        $finders = false;
        $related = false;

        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            $contents = $this->getMethods($admin, array(
                'action'        => $configuration['type'],
                'plugin'        => $configuration['plugin'],
                'alias'         => $alias,
                'modelObj'      => $modelObj,
            ));

            if (!empty($contents)) {
                $methods .= "{$contents}\n\n";
                if (in_array('find', (array) $configuration['methods'])) $finders = true;
                if (in_array('related', (array) $configuration['methods'])) $related = true;
            }
        }

        return array($methods, $finders, $related);
    }

/**
 * undocumented function
 *
 * @param string $admin
 * @param string $options
 * @return void
 */
    function getMethods($admin, $options) {
        $endPath = 'libs' . DS . 'templates' . DS . 'actions' . DS;
        if (empty($options['plugin'])) {
            $path = APP . $endPath;
        } else {
            $path = $this->pluginDir . $options['plugin'] . DS . $endPath;
        }
        $path .= $options['action'] . DS . 'models';

        $find               = $options['alias'];
        $alias              = $options['alias'];

        $vars               = $this->AdminVariables->load($admin, true);
        $this->AdminTemplate->set($vars);
        $this->AdminTemplate->set(compact(
            'find',
            'alias'
        ));
        return $this->AdminTemplate->generate($path, "methods");
    }

}