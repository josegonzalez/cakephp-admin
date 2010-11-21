<?php
class CakeAdminChangelogConfig extends CakeAdminActionConfig {

/**
 * Configuration defaults
 *
 * @var array
 **/
    var $defaults = array();

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
    var $type = 'changelog';

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array();

}