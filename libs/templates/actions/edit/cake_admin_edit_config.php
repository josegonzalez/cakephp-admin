<?php
class CakeAdminEditConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array(
        array(
            'title'         => null,                        // Defaults to null
            'classes'       => null,                        // Wraps the section in these classes
            'description'   => null,                        // Used to describe this section
            'fields'        => array('*'),                  // These fields are editable.
            'readonly'      => array('created', 'modified'),// These fields are read-only
            'hidden'        => array(),                     // These fields are hidden
            'default'       => array(),                     // These fields are defaulted
            'exclude'       => array(),                     // These fields are excluded
        )
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
    var $type = 'edit';

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array('find', 'related');

}