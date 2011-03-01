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
 *  Constructs this Shell instance.
 *
 */
    function __construct(&$dispatch) {
        parent::__construct($dispatch);

        $this->pluginDir        = APP . 'plugins' . DS;
        $this->templateDir      = array();
        $this->templateDir[]    = dirname(__FILE__);
        $this->templateDir[]    = '..';
        $this->templateDir[]    = '..';
        $this->templateDir[]    = '..';
        $this->templateDir[]    = 'libs';
        $this->templateDir[]    = 'templates';
        $this->templateDir      = implode(DS, $this->templateDir);
    }

/**
 * undocumented function
 *
 * @return void
 **/
    function generateAppModel($admin) {
        $path = $this->templateDir . DS . 'classes';

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
        $path = $this->templateDir . DS . 'classes';

        list($methods, $hasFinders, $hasRelated) = $this->generateContents($admin);

        $this->AdminTemplate->set(compact(
            'methods',
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
    function generateContents($admin) {
        $methods = '';
        $finders = false;
        $related = false;
        foreach ($admin->actions as $alias => $configuration) {
            if ($configuration['enabled'] !== true) continue;

            $contents = $this->getMethods($admin, array(
                'action'        => $configuration['type'],
                'plugin'        => $configuration['plugin'],
                'alias'         => $alias,
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
        $path  .= $options['action'] . DS . 'models';

        $find   = $options['alias'];
        $alias  = $options['alias'];

        $this->AdminTemplate->set(compact(
            'admin',
            'find',
            'alias'
        ));
        return $this->AdminTemplate->generate($path, "methods");
    }

}