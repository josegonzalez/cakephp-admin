<?php
class CakeAdminAddConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'title'         => null,                        // Defaults to null
        'classes'       => null,                        // Wraps the section in these classes
        'description'   => null,                        // Used to describe this section
        'fields'        => array('*'),                  // These fields are editable. if associative, field => label
        'readonly'      => array(),                     // These fields are read-only
        'hidden'        => array(),                     // These fields are hidden
        'default'       => array(),                     // These fields are defaulted, field => default
        'exclude'       => array('created', 'modified', 'updated'),// These fields are excluded
        'habtm'         => false,                       // Allow editing of habtm data
    );

/**
 * Is this action enabled by default
 *
 * @var boolean
 **/
    var $enabled = true;

/**
 * Plugin where the templates for this action are located
 *
 * @var string
 **/
    var $plugin = 'cake_admin';

/**
 * Type of action this is
 * Standard types are [index, add, edit, deleted, changelog, history]
 *
 * @var string
 **/
    var $type = 'add';

/**
 * Whether this action is linkable
 *
 * False to produce no links anywhere (except when specified within a template)
 * True when linkable on a model-level
 * An array when linkable on the record-level. The mappings for the array are:
 * - (string) title: The content to be wrapped by <a> tags.
 * - (array) options: Array of HTML attributes
 * - (string) confirmMessage: JavaScript confirmation message. Literal string will be output
 *          the following will be replaced within the confirmMessage
 *              {{primaryKey}} : alias of the primaryKey
 *              {{modelName}} : alias of the humanized application modelName
 *              {{pluginModelName}} : alias of the humanized generated modelName
 *
 * @var mixed
 **/
    var $linkable = true;

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array('related');

    function mergeVars($admin, $configuration) {
        if (empty($configuration)) return array($this->defaults);

        $modelObj = ClassRegistry::init(array(
            'class' => $admin->modelName,
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        $schema = $modelObj->schema();

        foreach ($configuration as $i => $config) {

            foreach ($this->defaults as $key => $value) {
                if (!isset($config[$key])) $configuration[$i][$key] = $value;
            }

            $fields = array();
            if (empty($config['fields']) || (in_array('*', (array) $config['fields']))) {
                // $fields is all fields
                foreach (array_keys($schema) as $field) {
                    $fields[] = $field;
                }
            } else {
                $config['fields'] = Set::normalize($config['fields']);
                foreach ((array) $config['fields'] as $field => $alias) {
                    if ($field !== '*') $fields[$field] = $alias;
                }
            }

            if (!empty($config['exclude'])) {
                foreach ($config['exclude'] as $field) {
                    if (in_array($field, array_keys($fields))) {
                        $fields = array_diff_key($fields, array($field => $field));
                    }
                }
            }

            $configuration[$i]['fields'] = $fields;
        }

        return $configuration;
    }

}