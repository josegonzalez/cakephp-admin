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

    function __construct() {
        if (!$type) throw new Exception('undefined variable "$type"');
        if (!$plugin) throw new Exception('undefined variable "$plugin"');
    }

}