<?php
class CakeAdminIndexConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'fields'        => array('*'),      // array or string of fields to enable
        'list_filter'   => null,            // Allow these to be filterable
        'link'          => array('id'),     // Link to object here. Must be in fields
        'order'         => 'id ASC',        // Default ordering
        'search'        => null,            // Allow searching of these fields
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

}