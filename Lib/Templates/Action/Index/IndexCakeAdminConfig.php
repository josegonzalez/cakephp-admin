<?php
class IndexCakeAdminConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'fields'        => array('*'),      // array or string of fields to enable
        'list_filter'   => array(),         // Allow these to be filterable
        'link'          => array('id'),     // Link to object here. Must be in fields
        'search'        => array(),         // Allow searching of these fields
        'sort'          => true,            // Allow sorting. True or array of sortable fields
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
    var $type = 'index';

/**
 * Whether this action is linkable
 *
 * False to produce no links anywhere (except when specified within a template)
 * True when linkable on a model-level. Link prefix defaults to humanized action alias
 * String when linkable on a model-level. Link prefix is then the string
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
    var $linkable = 'List {{modelname}}';

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

        $filters = array();
        $search = array();
        $fields = array();
        $schema = $admin->modelObj->schema();

        if (empty($configuration['fields']) || (in_array('*', (array) $configuration['fields']))) {
            // $fields is all fields
            foreach (array_keys($schema) as $field) {
                $fields[] = $field;
            }
        } else {
            foreach ((array) $configuration['fields'] as $field) {
                if ($field !== '*') $fields[] = $field;
            }
        }

        if (!empty($configuration['list_filter'])) {
            foreach ($configuration['list_filter'] as $field => $config) {
                $filters[$field] = (array) $config;
            }
        }

        $configuration['search'] = Set::normalize($configuration['search']);
        if (!empty($configuration['search'])) {
            foreach ($configuration['search'] as $field => $alias) {
                if (!in_array($field, array_keys($filters))) {
                    $type = ($schema[$field]['type'] == 'text') ? 'text' : $schema[$field]['type'];
                    if ($type == 'string') $type = 'text';

                    if (is_array($alias)) {
                        if (isset($alias['type'])) {
                            $type = $alias['type'];
                        }

                        if (isset($alias['label'])) {
                            $label = $alias['label'];
                        } else {
                            $label = Inflector::humanize((preg_replace('/_id$/', '', $field)));
                        }

                        if (isset($alias['alias'])) {
                            $alias = $alias['alias'];
                        } else {
                            $alias = $field;
                        }
                    } else {
                        $alias = empty($alias) ? $field : $alias;
                        $label = Inflector::humanize((preg_replace('/_id$/', '', $field)));
                    }

                    $type = ($field === 'id') ? 'text' : $type;

                    $search[$field] = array(
                        'type' => $type,
                        'alias' => $alias,
                        'label' => $label,
                    );
                }
            }
        }

        $sort = $fields;
        if (empty($configuration['sort']) || $configuration['sort'] === false) {
            $sort = array();
        } else if (is_array($configuration['sort'])) {
            $sort = $configuration['sort'];
        }

        if (empty($configuration['link'])) {
            $configuration['link'] = array($admin->primaryKey);
        }

        $configuration = array_merge($this->defaults, $configuration);
        $configuration['list_filter'] = $filters;
        $configuration['search'] = $search;
        $configuration['fields'] = $fields;
        $configuration['sort'] = $sort;

        return $configuration;
    }

}