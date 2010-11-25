<?php
class CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var string
 **/
    var $defaults = array();

/**
 * Is this action enabled by default
 *
 * @var string
 **/
    var $enabled = true;

/**
 * Plugin where the templates for this action are located
 *
 * @var string
 **/
    var $plugin = null;

/**
 * Type of action this is
 * Standard types are [index, add, edit, deleted, changelog, history]
 *
 * @var string
 **/
    var $type = null;

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array();

/**
 * Constructor. Throws exceptions on invalid class properties
 *
 * @author Jose Diaz-Gonzalez
 */
    function __construct() {
        if (!$this->type) throw new Exception('undefined property "$type"');
        if (!$this->plugin) throw new Exception('undefined property "$plugin"');
    }

/**
 * Merges instantiated configuration with the class defaults
 *
 * @param array $configuration action configuration
 * @return array
 * @author Jose Diaz-Gonzalez
 */
    function mergeVars($admin, $configuration = array()) {
        return array_merge($this->defaults, $configuration);
    }

}