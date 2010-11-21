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
    function mergeVars($configuration = array()) {
        if (empty($configuration)) return $this->defaults;

        if ($configuration['fields'] !== '*') {
            $fields = array();
            $possibleFields = $configuration['fields'];
            foreach ($possibleFields as $field) {
                if ($field !== '*') $fields[] = $field;
            }
        }

        $sort = $fields;
        if ($configuration['sort'] == false) {
            $sort = array();
        } else if (is_array($configuration['sort'])) {
            $sort = $configuration['sort'];
        }

        $configuration = array_merge($configuration, $this->defaults);
        $configuration['fields'] = $fields;
        $configuration['sort'] = $sort;

        return $configuration;
    }

}