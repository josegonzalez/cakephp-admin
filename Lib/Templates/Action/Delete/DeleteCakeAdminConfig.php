<?php
class DeleteCakeAdminConfig extends CakeAdminActionConfig {

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
 *              {{displayField}} : alias of the displayField
 *              {{modelName}} : alias of the humanized application modelName
 *              {{modelNameVar}} : alias of the variablized application modelName
 *              {{pluginModelName}} : alias of the humanized generated modelName
 *              {{pluginModelNameVar}} : alias of the variablized generated modelName
 *
 * @var mixed
 **/
    var $linkable = array('title' => 'Delete');

/**
 * Model methods this action contains
 *
 * @var array
 **/
    var $methods = array('find');

}