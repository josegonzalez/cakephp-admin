<?php

class CakeAdmin {

/**
 * Name of the model. Must be overridden in order to create a
 * valid admin section, unless you want everything to reference
 * a Cake Model
 *
 * @var string
 */
    var $modelName      = 'cake';

/**
 * Name of the primaryKey of the model
 *
 * @var string
 */
    var $primaryKey     = 'id';

/**
 * Name of the displayField of the model
 *
 * @var string
 */
    var $displayField   = 'title';

/**
 * Instead of prefixes, we use plugins to set a valid prefix
 * Pluginizing the app means we can isolate the "admin" portion
 * From everything else. One can also App::import() the plugin
 * Models/Views and import the admin goodness.
 *
 * Isolating the CakeAdmin'ed app from everything else is good 
 * for ease of updating code without have to worry about a 
 * re-admin deleting customizations
 *
 * @var string
 */
    var $plugin         = 'admin';

/**
 * Apply all these Behaviors to the Model
 *
 * @var array
 */
    var $behaviors      = array();

/**
 * Apply all these Components to the Controller
 *
 * @var string
 **/
    var $components     = array();

/**
 * Apply all these Helpers to the Controller
 *
 * @var string
 **/
    var $helpers    = array();

/**
 * Model validation rules
 *
 * name => rule, where rule can be an array. If a string, it is set to a default
 *
 * @var array
 */
    var $validations    = array();

/**
 * Relations to use on this model. Defaults to belongsTo.
 * Assumes conventions, but you can configure if necessary
 *
 * @var string
 */
    var $relations = array();

/**
 * Customize the views further from the base
 *
 * @var array
 */
    var $views      = array(
        'index' => array(
            'fields'        => '*',
            'list_filter'   => null,
            'link'          => array('id'),                 // Link to object here
            'order'         => 'id ASC',                    // Default ordering
            'search'        => array('id'),                 // Allow searching of these fields
            'sort'          => true,                        // Allow sorting. Also takes array of fields to enable sorting on
        ),
        'create' => array(
            array('fields'  => array('title', 'content')),
        ),
        'update' => array(
            array('fields' => array('title', 'content')),
        )
    );

/**
 * The following actions are implemented, where alias => action
 *
 * @var array
 **/
    var $actions = array(
        'add'       => 'add',
        'view'      => 'view',
        'edit'      => 'edit',
        'delete'    => 'delete',
        'history'   => 'history', 
        'changelog' => 'changelog',
    );

/**
 * Auth Configuration
 * We can decide which actions we want to require auth for
 * Authentication can be implemented by some plugin adapter, app-wide
 *
 * @var string
 * @todo implement me
 */
    var $auth = array();

/**
 * AuthImplementation
 *
 * Either Acl, Auth, Authsome.Authsome, or Sanction.Permit, or the boolean false
 *
 * Acl requires Auth, Sanction.Permit requires Authsome
 *
 * @var array
 * @todo implement me
 */
    var $authImplementation = false;

/**
 * Use sessions?
 *
 * @var boolean
 * @default true
 */
    var $sessions = true;

/**
 * Automagic Webservice?
 *
 * @var boolean
 * @default false
 * @todo implement me
 **/
    var $webservice = false;

/**
 * Action to redirect to on errors
 *
 * @var string
 * @default index
 */
    var $redirectTo = 'index';

}