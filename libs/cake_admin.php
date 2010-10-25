<?php

class CakeAdmin {

/**
 * Name of the model. Defaults to the class name 
 * minus the appended "Admin" string
 *
 * @var string
 */
    var $modelName      = null;

/**
 * Name of database connection to use
 *
 * @var string
 */
    var $useDbConfig    = 'default';

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
    var $helpers        = array();

/**
 * Model validation rules
 *
 * field => array(alias => rule)
 *
 * where rule can be an array. If the rule is a string or the
 * message is missing, the message is set to some default.
 *
 * Validation rules are wrapped in __d() calls within the CakeAdmin model template,
 * meaning your admin section can be localized at a later date and time.
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
    var $relations      = array();

/**
 * Use sessions?
 *
 * @var boolean
 * @default true
 */
    var $sessions = true;

/**
 * Action to redirect to on errors
 *
 * @var string
 * @default index
 */
    var $redirectTo = 'index';

/**
 * Customize the admin
 *
 * @var array
 */
    var $_actions        = array(
        'index' => array(
            'type'      => 'index',                                     // If not set, type maps to the key of this action
            'enabled'   => true,                                        // Index is enabled by default
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
            'config'    => array(
                'fields'                => array('*'),                  // array or string of fields to enable
                'list_filter'           => null,                        // Allow these to be filterable
                'link'                  => array('id'),                 // Link to object here. Must be in fields
                'order'                 => 'id ASC',                    // Default ordering
                'search'                => null,                        // Allow searching of these fields
                'sort'                  => true,                        // Allow sorting. True or array of sortable fields
            )
        ),
        'add' => array(
            'type'      => 'add',                                       // If not set, type maps to the key of this action
            'enabled'   => true,                                        // Add is enabled by default
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
            'config'    => array(
                array(
                    'title'             => null,                        // Defaults to null
                    'classes'           => null,                        // Wraps the section in these classes
                    'description'       => null,                        // Used to describe this section
                    'fields'            => array('*'),                  // These fields are editable.
                    'readonly'          => array('created', 'modified'),// These fields are read-only
                    'hidden'            => array(),                     // These fields are hidden
                    'default'           => array(),                     // These fields are defaulted
                    'exclude'           => array(),                     // These fields are excluded
                )
            )
        ),
        'edit' => array(
            'type'      => 'edit',                                      // If not set, type maps to the key of this action
            'enabled'   => true,                                        // Edit is enabled by default
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
            'config'    => array(
                array(
                    'title'             => null,                        // Defaults to null
                    'classes'           => null,                        // Wraps the section in these classes
                    'description'       => null,                        // Used to describe this section
                    'fields'            => array('*'),                  // These fields are editable. If empty, defaults to *
                    'readonly'          => array('created', 'modified'),// These fields are read-only
                    'hidden'            => array(),                     // These fields are hidden
                    'default'           => array(),                     // These fields are defaulted
                    'exclude'           => array(),                     // These fields are excluded
                )
            )
        ),
        'view' => array(
            'type'      => 'view',                                      // If not set, type maps to the key of this action
            'enabled'   => false,                                       // View is disabled by default
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
            'config'    => array(
                'fields'                => array('*'),                  // These fields are editable. If empty, defaults to *
            )
        ),
        'delete' => array(
            'type'      => 'delete',                                    // If not set, type maps to the key of this action
            'enabled'   => true,                                        // Delete is enabled by default
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
            'config'    => array(
                'displayPrimaryKey'     => true,                        // Display the primary key of the record being deleted
                'displayName'           => true,                        // Display the name of the record being deleted
                'cascade'               => true,                        // Cascade delete queries
            )
        ),
        'history' => array(
            'type'      => 'history',                                   // If not set, type maps to the key of this action
            'enabled'   => false,                                       // History is disabled by default. Requires Logable
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
        ),
        'changelog' => array(
            'type'      => 'changelog',                                 // If not set, type maps to the key of this action
            'enabled'   => false,                                       // Changelog is disabled by default. Requires Logable
            'plugin'    => 'cake_admin',                                // Path where the associated templates are located
        ),
    );

    function __construct() {
        // Update the modelName if not set
        if (empty($this->modelName)) {
            $this->modelName = substr(get_class($this), 0 , -5);
        }

        // Set a table if not already set
        if (empty($this->useTable)) {
            $this->useTable = Inflector::tableize($this->modelName);
        }

        // Iterate over validation rules to set proper defaults
        if (!empty($this->validations)) {
            $this->_updateValidationRules();
        }

        // Update the actions configuration
        if (empty($this->actions)) {
            $this->actions = $this->_actions;
        } else {
            $this->actions = Set::merge(
                $this->_actions,
                $this->actions
            );
        }
    }

/**
 * Update the class validation rules to conform to multiple validation 
 * rule standards. Also updates the message if currently non-existent.
 * Properly deals with all core validation rule use-cases
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 */
    function _updateValidationRules() {
        $validationRules = array();
        foreach ($this->validations as $fieldName => $rules) {
            if (is_string($rules)) {
                $options = array('rule' => $rules);
                $rule = $options['rule'];
                $options['message'] = $this->_validationMessage(
                    $fieldName,
                    $rule,
                    $options
                );
                $validationRules[$fieldName][$rule] = $options;
                continue;
            }

            foreach ($rules as $alias => $options) {
                if (is_string($options) || Set::numeric(array_keys($options))) {
                    $options = array('rule' => $options);
                }
                $rule = $options['rule'];
                if (is_array($rule)) {
                    $rule = current($rule);
                }
                if (empty($options['message'])) {
                    $options['message'] = $this->_validationMessage(
                        $fieldName,
                        strtolower($rule),
                        $options
                    );
                }
                $validationRules[$fieldName][$alias] = $options;
            }
        }
        $this->validations = $validationRules;
    }

    function _validationMessage($fieldName, $rule, $options = array()) {
        $fieldName = Inflector::humanize((preg_replace('/_id$/', '', $fieldName)));

        // Retrieve rule message
        $ruleMessage = '{{field}} must be valid input';
        if ($this->_in_arrayi($rule, array_keys($this->_validationMessages))) {
            $ruleMessage = $this->_validationMessages[strtolower($rule)];
        }

        // Requires only fieldName
        $fieldNameOnly = array(
            'alphanumeric', 'blank', 'boolean',
            'email', 'extension', 'file',
            'ip', 'inlist', 'isunique',
            'money', 'numeric', 'notempty',
            'phone', 'postal', 'ssn', 'url'
        );
        $ruleMessage = str_replace("{{field}}", $fieldName, $ruleMessage);
        if (in_array($rule, $fieldNameOnly)) return $ruleMessage;

        // Require min/max
        $minMaxRequired = array('between', 'multiple', 'range');
        if (in_array($rule, $minMaxRequired)) {
            $min = 'min';
            $max = 'max';
            if (!empty($options['min'])) {
                $min = $options['min'];
            } else if (is_array($options['rule']) && count($options['rule']) === 3) {
                $min = $options['rule'][1];
            }
            if (!empty($options['max'])) {
                $max = $options['max'];
            } else if (is_array($options['rule']) && count($options['rule']) === 3) {
                $max = $options['rule'][2];
            }
            $ruleMessage = str_replace("{{min}}", $min, $ruleMessage);
            return str_replace("{{max}}", $max, $ruleMessage);
        }

        // Require Length
        $lengthRequired = array('decimal', 'maxlength', 'minlength');
        if (in_array($rule, $lengthRequired)) {
            $length = 'length';
            if (!empty($options['length'])) {
                $length = $options['length'];
            } else if (is_array($options['rule']) && count($options['rule']) === 2) {
                $length = $options['rule'][1];
            }
            return str_replace("{{length}}", $length, $ruleMessage);
        }

        // Comparison, [comparison, value]
        if ($rule == 'comparison') {
            $comparison = 'comparison';
            $value = 'value';
            if (!empty($options['comparison'])) {
                $comparison = $options['comparison'];
            } else if (is_array($options['rule']) && count($options['rule']) === 3) {
                $comparison = $options['rule'][1];
            }
            if (!empty($options['value'])) {
                $value = $options['value'];
            } else if (is_array($options['rule']) && count($options['rule']) === 3) {
                $value = $options['rule'][2];
            }
            $ruleMessage = str_replace("{{comparison}}", $comparison, $ruleMessage);
            return str_replace("{{value}}", $value, $ruleMessage);
        }

        // Format
        if ($rule == 'format') {
            $format = 'format';
            if (!empty($options['format'])) {
                $format = $options['format'];
            } else if (is_array($options['rule']) && count($options['rule']) === 2) {
                $format = $options['rule'][1];
            }
            return str_replace("{{format}}", $format, $ruleMessage);
        }

        return $ruleMessage;
    }

    function _in_arrayi($needle, $haystack) {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }


/**
 * Default Validation Messages
 *
 * Messages may contain the string `{{field}}` in order to support
 * inclusion of fieldnames via str_replace
 *
 * Rules that have parameters may include those parameters as {{parameter}}
 *
 * When overriding these in sub-classes, remember to either override
 * in the __construct() method to array_merge with the defaults, or
 * specify all the messages that may be used
 *
 * @var string
 */
    var $_validationMessages = array(
        'alphanumeric'  => '{{field}} must only contain letters and numbers',
        'between'       => '{{field}} must be between {{min}} and {{max}} characters long',
        'blank'         => '{{field}} must be blank or contain only whitespace characters',
        'boolean'       => 'Incorrect value for {{field}}',
        'cc'            => 'The credit card number you supplied was invalid',
        'comparison'    => '{{field}} must be {{comparison}} to {{value}}',
        'date'          => 'Enter a valid date in {{format}} format',
        'decimal'       => '{{field}} must be a valid decimal number with at least {{length}} decimal points',
        'email'         => '{{field}} must be a valid email address',
        'equalTo'       => '{{field}} must be equal to {{number}}',
        'extension'     => '{{field}} must have a valid extension',
        'file'          => '{{field}} must be a valid file name',
        'ip'            => '{{field}} must be a valid IP address',
        'inlist'        => 'Your selection for {{field}} must be in the given list',
        'isunique'      => 'This {{field}} has already been taken',
        'maxlength'     => '{{field}} must have less than {{length}} characters',
        'minlength'     => '{{field}} must have at least {{length}} characters',
        'money'         => '{{field}} must be a valid monetary amount',
        'multiple'      => 'You must select at least {{min}} and no more than {{max}} options for {{field}}',
        'numeric'       => '{{field}} must be numeric',
        'notempty'      => '{{field}} cannot be empty',
        'phone'         => '{{field}} must be a valid phone number',
        'postal'        => '{{field}} must be a valid postal code',
        'range'         => '{{field}} must be between {{min}} and {{max}}',
        'ssn'           => '{{field}} must be a valid social security number',
        'url'           => '{{field}} must be a valid url',
    );
}