<?php
class CakeAdminIndexConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'fields'        => array('*'),      // array or string of fields to enable
        'list_filter'   => array(),         // Allow these to be filterable
        'link'          => array('id'),     // Link to object here. Must be in fields
        'order'         => 'id ASC',        // Default ordering
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
        if (empty($configuration)) return $this->defaults;

        $modelObj = ClassRegistry::init(array(
            'class' => $admin->modelName,
            'table' => $admin->useTable,
            'ds'    => $admin->useDbConfig
        ));

        $filters = array();
        $search = array();
        $fields = array();
        $order = "{$admin->primaryKey} ASC";
        $schema = $modelObj->schema();

        if (empty($configuration['fields']) || (in_array('*', (array) $configuration['fields']))) {
            // $fields is all fields
            foreach (array_keys($modelObj->schema()) as $field) {
                $fields[] = $field;
            }
        } else {
            foreach ((array) $configuration['fields'] as $field) {
                if ($field !== '*') $fields[] = $field;
            }
        }

        if (!empty($configuration['order'])) {
            $order = $configuration['order'];
            if (count($order) == 1) {
                $order = current($order);
            }
            if (is_array($order)) {
                $order = join("', '", $fields);
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
                    $search[$field] = array(
                        'type' => ($field === 'id') ? 'text' : $type,
                        'alias' => empty($alias) ? $field : $alias,
                    );
                }
            }
        }

        $sort = $fields;
        if ($configuration['sort'] == false) {
            $sort = array();
        } else if (is_array($configuration['sort'])) {
            $sort = $configuration['sort'];
        }

        $configuration = array_merge($this->defaults, $configuration);
        $configuration['list_filter'] = $filters;
        $configuration['search'] = $search;
        $configuration['fields'] = $fields;
        $configuration['order'] = $order;
        $configuration['sort'] = $sort;

        return $configuration;
    }

}