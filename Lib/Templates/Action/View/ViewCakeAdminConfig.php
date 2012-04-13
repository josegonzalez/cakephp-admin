<?php
class ViewCakeAdminConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'fields'        => array('*'),      // These fields are editable. if associative, field => label
                                            // May also indicate whether to link to the belongsTo model,
                                            // 'contain' => true
        'contain'       => array('*'),      // Array or string of models to contain
    );

/**
 * Is this action enabled by default
 *
 * @var boolean
 **/
    var $enabled = false;

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
    var $type = 'view';

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
    var $linkable = array('title' => 'View');

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array('find');

/**
 * Merges instantiated configuration with the class defaults
 *
 * @param array $configuration action configuration
 * @return array
 * @author Jose Diaz-Gonzalez
 */
    function mergeVars($admin, $configuration = array()) {
        if (empty($configuration)) $configuration = $this->defaults;
        if (empty($configuration['link'])) $configuration['link'] = array();
        if (empty($configuration['contain'])) $configuration['contain'] = array();

        $fields = array();
        $contains = array();
        $schema = $admin->modelObj->schema();

        if (empty($configuration['fields']) || (in_array('*', (array) $configuration['fields']))) {
            // $fields is all fields
            foreach (array_keys($schema) as $field) {
                $fields[$field] = array('label' => Inflector::humanize($field), 'link' => false);
            }
        }
        if (!empty($configuration['fields'])) {
            $configuration['fields'] = Set::normalize($configuration['fields']);
            foreach ((array) $configuration['fields'] as $field => $config) {
                if ($field === '*') continue;
                if (is_string($config)) {
                    $config = array('label' => $config);
                }

                if (empty($config['label'])) $config['label'] = Inflector::humanize($field);
                if (empty($config['link'])) $config['link'] = false;
                if (in_array($field, $configuration['link'])) $config['link'] = true;
                $fields[$field] = $config;
            }
        }

        $linkAssociations = array();
        // $fields is all fields
        foreach ($admin->associations as $type => $associations) {
            foreach ($associations as $assoc => $assocData) {
                if (in_array('*', (array) $configuration['contain'])) {
                    $contains[$assoc] = array('fields' => $assocData['fields']);
                }
                $linkAssociations[$assocData['foreignKey']] = array(
                    'model'         => $assoc,
                    'fields'        => array($assocData['primaryKey'], $assocData['displayField']),
                );
            }
        }
        if (!empty($configuration['contain'])) {
            foreach (Set::normalize((array) $configuration['contain']) as $assoc => $assocData) {
                if ($assoc !== '*') $contains[$assoc] = $assocData;
            }
        }

        foreach ($configuration['link'] as $field) {
            $fields[$field]['link'] = true;
        }

        foreach ($fields as $field => $config) {
            if ($config['link']) {
                if (!isset($linkAssociations[$field])) continue;
                $link = $linkAssociations[$field];
                if (!isset($contains[$link['model']]) || !isset($contains[$link['model']]['fields'])) {
                    $contains[$link['model']]['fields'] = array();
                }
                foreach ($link['fields'] as $field) {
                    if (!in_array($field, $contains[$link['model']]['fields'])) {
                        $contains[$link['model']]['fields'][] = $field;
                    }
                }
            }
        }

        $configuration = array_merge($this->defaults, $configuration);
        $configuration['fields'] = $fields;
        $configuration['contain'] = $contains;
        return $configuration;
    }

}