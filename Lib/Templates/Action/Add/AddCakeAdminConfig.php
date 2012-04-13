<?php
class AddCakeAdminConfig extends CakeAdminActionConfig {

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
        if (empty($configuration)) $configuration = array($this->defaults);

        $formType = false;
        if (isset($configuration[0]['formType'])) {
            $formType = $configuration[0]['formType'];
        }

        $schema = $admin->modelObj->schema();

        foreach ($configuration as $i => $config) {

            foreach ($this->defaults as $key => $value) {
                if (!isset($config[$key])) $configuration[$i][$key] = $value;
            }

            $fields = array();
            if (empty($configuration[$i]['fields']) || (in_array('*', (array) $configuration[$i]['fields']))) {
                // $fields is all fields
                foreach (array_keys($schema) as $field) {
                    if ($field == $admin->modelObj->primaryKey) continue;
                    if (in_array($field, array('created', 'modified', 'updated'))) continue;
                    if (in_array($field, array('created_by', 'modified_by', 'updated_by'))) continue;

                    $fields[$field] = array(
                        'label' => Inflector::humanize($field),
                        'wrapper' => '%s',
                    );
                }
            }
            if (!empty($configuration[$i]['fields'])) {
                $configuration[$i]['fields'] = Set::normalize($configuration[$i]['fields']);
                foreach ((array) $configuration[$i]['fields'] as $field => $config) {
                    if ($field == $admin->modelObj->primaryKey || $field === '*') continue;

                    if (empty($configuration[$i])) {
                        $config = array();
                    } else if (is_string($config)) {
                        $config = array('label' => $config);
                    }

                    if (empty($config['label'])) $config['label'] = Inflector::humanize($field);
                    if (empty($config['wrapper'])) $config['wrapper'] = '%s';
                    $fields[$field] = $config;
                }
            }

            if (!empty($configuration[$i]['exclude'])) {
                foreach ($configuration[$i]['exclude'] as $field) {
                    if (in_array($field, array_keys($fields))) {
                        $fields = array_diff_key($fields, array($field => $field));
                    }
                }
            }

            if (!empty($configuration[$i]['hidden'])) {
                foreach ((array) $configuration[$i]['hidden'] as $field) {
                    if (in_array($field, array_keys($fields))) {
                        $fields[$field]['type'] = 'hidden';
                    }
                }
            }

            if (!empty($configuration[$i]['default'])) {
                foreach ((array) $configuration[$i]['default'] as $field => $value) {
                    if (in_array($field, array_keys($fields))) {
                        $fields[$field]['value'] = $value;
                    }
                }
            }

            $configuration[$i]['fields'] = $fields;
            $configuration[$i]['classes'] = (string) $configuration[$i]['classes'];
            $configuration[$i]['description'] = (string) $configuration[$i]['description'];
        }

        $configuration['formType'] = $formType;

        return $configuration;
    }

}