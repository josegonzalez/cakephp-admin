<?php
class CakeAdminDeleteConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        'displayPrimaryKey'     => true,                        // Display the primary key of the record being deleted
        'displayName'           => true,                        // Display the name of the record being deleted
        'cascade'               => true,                        // Cascade delete queries
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
    var $type = 'delete';

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array('find');

}