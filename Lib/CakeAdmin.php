<?php
/**
 * CakeAdmin
 *
 * Base admin class from which all descendent classes are derived.
 *
 * @copyright     Copyright 2010, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link          http://josediazgonzalez.com
 * @package       cake_admin
 * @subpackage    cake_admin.libs
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::uses('CakeAdminActionConfig', 'CakeAdmin.Lib');
App::uses('Model', 'Model');
class CakeAdmin {

/**
 * Name of the model. Defaults to the class name
 * minus the appended "Admin" string
 *
 * @var string
 */
	var $modelName      = null;

/**
 * Contains a model object
 *
 * @var Object
 **/
	var $modelObj       = null;

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
	var $actsAs      = array();

/**
 * Apply all these Components to the Controller
 *
 * @var array
 **/
	var $components     = array();

/**
 * Apply all these Helpers to the Controller
 *
 * @var array
 **/
	var $helpers        = array();

/**
 * Finders to enable for the Model
 *
 * @var array
 **/
	var $finders        = array();

/**
 * Finders to enable for the Model
 *
 * @var array
 **/
	var $relatedFinders = array();

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
	var $validate    = array();

/**
 * Relations to use on this model. Defaults to belongsTo.
 * Assumes conventions, but you can configure if necessary
 *
 * @var array
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
 * Array of links for the model
 *
 * @var array
 **/
	var $links = array();

/**
 * Action to redirect to on errors
 *
 * @var string
 * @default index
 */
	var $redirectTo = 'index';

/**
 * Alias to link to on a record-level link
 *
 * @var string
 * @default index
 */
	var $linkTo = 'edit';

/**
 * Enable building of this cake_admin set
 *
 * @var string
 **/
	var $enabled = true;

/**
 * Base directory for writing filesystem output
 *
 * @var string
 **/
	var $baseDir;

/**
 * Directory holding cake_admin core template files
 *
 * @var string
 **/
	var $templateDir;

/**
 * Array of paths for writing filesystem output for MVC files
 *
 * @var array
 **/
	var $paths;

/**
 * Customize the admin
 *
 * @var array
 */
	var $_actions        = array(
		'index' => array(
			'type'      => 'index',                                     // If not set, type maps to the key of this action
			'enabled'   => true,                                        // Index is enabled by default
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => array('find'),                               // Has Finders? Has Related Finders?
			'config'    => array(
				'fields'                => array('*'),                  // Array or string of fields to enable
				'list_filter'           => null,                        // Allow these to be filterable
				'link'                  => array('id'),                 // Link to object here. Must be in fields
				'search'                => null,                        // Allow searching of these fields
				'sort'                  => true,                        // Allow sorting. True or array of sortable fields
			)
		),
		'add' => array(
			'type'      => 'add',                                       // If not set, type maps to the key of this action
			'enabled'   => true,                                        // Add is enabled by default
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => array('related'),                            // Has Finders? Has Related Finders?
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
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => array('find', 'related'),                    // Has Finders? Has Related Finders?
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
			'enabled'   => true,                                        // View is disabled by default
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => array('find'),                               // Has Finders? Has Related Finders?
			'config'    => array(
				'fields'                => array('*'),                  // Array or string of fields to enable
			)
		),
		'delete' => array(
			'type'      => 'delete',                                    // If not set, type maps to the key of this action
			'enabled'   => true,                                        // Delete is enabled by default
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => array('find'),                               // Has Finders? Has Related Finders?
			'config'    => array(
				'displayPrimaryKey'     => true,                        // Display the primary key of the record being deleted
				'displayName'           => true,                        // Display the name of the record being deleted
				'cascade'               => true,                        // Cascade delete queries
			)
		),
		'history' => array(
			'type'      => 'history',                                   // If not set, type maps to the key of this action
			'enabled'   => false,                                       // History is disabled by default. Requires Logable
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => false,                                       // Has Finders? Has Related Finders?
			'config'    => array(),
		),
		'changelog' => array(
			'type'      => 'changelog',                                 // If not set, type maps to the key of this action
			'enabled'   => false,                                       // Changelog is disabled by default. Requires Logable
			'plugin'    => 'CakeAdmin',                                 // Path where the associated templates are located
			'methods'   => false,                                       // Has Finders? Has Related Finders?
			'config'    => array(),
		),
	);


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
 * @var array
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

/**
 * CakeAdmin constructor. Correctly merges descendent classes with
 * itself for further use
 */
	function __construct() {
		if (!$this->enabled) return;

		if (empty($this->modelName)) {
			$this->modelName = substr(get_class($this), 0 , -9);
		}
		$this->controllerName = $this->_controllerName($this->modelName);

		// Set a table if not already set
		if (empty($this->useTable)) {
			$this->useTable = Inflector::tableize($this->modelName);
		}

		// Setup the admin model class
		$this->_setModel();

		// Update the modelName if not set
		$this->_setVariables();

		// Iterate over validation rules to set proper defaults
		if (!empty($this->validate)) {
			$this->_updateValidationRules();
		}

		// Update the actions configuration
		$this->_mergeVars();

		// Set the required model methods
		$this->_setModelMethods();

		// Setup template paths
		$this->_setTemplates();
	}

	function _setVariables() {
		// Load an instance of the admin model object
		$schema                 = $this->modelObj->schema(true);
		$this->fields           = array_keys($schema);

		$this->plugin           = Inflector::underscore($this->plugin);
		$this->controllerRoute  = $this->_pluralName($this->modelName);

		$this->singularVar      = Inflector::variable($this->modelName);
		$this->singularName     = $this->_singularName($this->modelName);
		$this->singularHumanName= $this->_singularHumanName($this->_controllerName($this->modelName));
		$this->pluralVar        = Inflector::variable($this->_controllerName($this->modelName));
		$this->pluralName       = $this->_pluralName($this->modelName);

		$this->associations     = $this->__associations($this->modelObj);

		if ($this->sessions && !in_array('Session', $this->components)) {
			$this->components[] = 'Session';
		}
	}

	function _mergeVars() {
		if (empty($this->actions)) {
			$this->actions = $this->_actions;
		}

		foreach ($this->_actions as $alias => $configuration) {
			if (!isset($this->actions[$alias])) {
				$this->actions[$alias] = $configuration;
			}
		}

		foreach ($this->actions as $alias => $configuration) {
			if (empty($configuration['plugin'])) $this->actions[$alias]['plugin'] = 'CakeAdmin';

			$plugin    = Inflector::camelize($this->actions[$alias]['plugin']);
			$type      = isset($configuration['type']) ? $configuration['type'] : $alias;
			$type      = Inflector::camelize($type);
			$className = Inflector::camelize($type) . 'CakeAdminConfig';
			App::uses($className, "{$plugin}.Lib/Templates/Action/{$type}");

			if (!class_exists($className)) {
				throw new Exception(sprintf("Undefined class %s", $className));
			}

			$this->actions[$alias]['type'] = $type;

			$configClass = new $className;
			if (isset($this->actions[$alias]['enabled'])) {
				$this->actions[$alias]['enabled'] = (bool) $this->actions[$alias]['enabled'];
			} else {
				$this->actions[$alias]['enabled'] = true;
			}

			$this->actions[$alias]['methods'] = $configClass->methods;

			if (!isset($this->actions[$alias]['linkable'])) {
				$this->actions[$alias]['linkable'] = $configClass->linkable;
			}

			if (is_array($this->actions[$alias]['linkable'])) {
				if (!isset($this->actions[$alias]['linkable']['title'])) {
					$this->actions[$alias]['linkable']['title'] = Inflector::humanize($alias);
				}
				if (!isset($this->actions[$alias]['linkable']['url'])) {
					$this->actions[$alias]['linkable']['url'] = array('action' => $alias);
				} else {
					$this->actions[$alias]['linkable']['url'] = Set::normalize($this->actions[$alias]['linkable']['url']);
				}
				if (!isset($this->actions[$alias]['linkable']['options'])) {
					$this->actions[$alias]['linkable']['options'] = null;
				}
				if (!isset($this->actions[$alias]['linkable']['confirmMessage'])) {
					$this->actions[$alias]['linkable']['confirmMessage'] = false;
				}
			}

			$config = (isset($this->actions[$alias]['config'])) ? $this->actions[$alias]['config'] : array();
			$this->actions[$alias]['config'] = $configClass->mergeVars($this, $config);
		}

		$actions = array();
		foreach ($this->actions as $alias => $configuration) {
			if ($configuration['enabled'] === true) {
				$actions[$alias] = $configuration;

				if (is_bool($configuration['linkable'])) {
					$this->links[$alias] = Inflector::humanize($alias) . ' ' . $this->singularHumanName;
				} elseif (is_string($configuration['linkable'])) {
					$this->links[$alias] = str_replace('{{modelname}}', $this->singularHumanName, $configuration['linkable']);
				} else {
					$this->links[$alias] = $configuration['linkable'];
				}
			}
		}
		$this->actions = $actions;
	}

/**
 * Sets the used find and related methods for this model
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function _setModelMethods() {
		foreach ($this->actions as $alias => $configuration) {
			if ($configuration['enabled'] !== true) continue;
			if (empty($configuration['methods'])) continue;

			if (in_array('find', (array) $configuration['methods'])) $this->finders[] = $alias;
			if (in_array('related', (array) $configuration['methods'])) $this->relatedFinders[] = $alias;
		}

		$this->finders = array_unique($this->finders);
		$this->relatedFinders = array_unique($this->relatedFinders);
	}

	function _setTemplates() {
		$this->baseDir          = APP .'Plugin' . DS . Inflector::camelize($this->plugin) . DS;
		$this->templateDir      = dirname(__FILE__) . DS . 'Templates';

		$this->paths            = array();
		$controllerPath = $this->_controllerPath($this->controllerName);

		$outputModelPath = array();
		$outputModelPath[] = $this->baseDir . 'Model' . DS;
		$outputModelPath[] = $this->modelName . '.php';

		$outputControllerPath   = array();
		$outputControllerPath[] = $this->baseDir;
		$outputControllerPath[] = 'Controller' . DS;
		$outputControllerPath[] = $controllerPath . 'Controller.php';

		$this->paths['model'] = implode($outputModelPath);
		$this->paths['controller'] = implode($outputControllerPath);

		foreach ($this->actions as $alias => $configuration) {
			if ($configuration['enabled'] !== true) continue;

			$outputViewPath = array();
			$outputViewPath[] = $this->baseDir . 'View' . DS;
			$outputViewPath[] = $this->_controllerPath($this->controllerName) . DS;
			$outputViewPath[] = $alias . '.ctp';

			$this->paths['views'][$alias] = implode(DS, array(
				$this->baseDir . 'View',
				$controllerPath,
				$alias . '.ctp'
			));
		}
	}

	function _setModel() {
		// Lets get a bare model
		$this->modelObj = new Model(array(
			'name'  => $this->modelName,
			'table' => $this->useTable,
			'ds'    => $this->useDbConfig
		));

		// Construct all relations, attach validation rules
		if ($this->modelObj->hasField($this->displayField)) {
			$this->modelObj->displayField = $this->displayField;
		} else {
			foreach (array('name', 'title', 'id', 'uuid') as $field) {
				if ($this->modelObj->hasField($field)) {
					$this->modelObj->displayField = $field;
				}
			}
		}

		$this->modelObj->primaryKey   = $this->primaryKey;
		$this->modelObj->validate     = $this->validate;

		// Attach related models. Must be non-cake_admin models, tables must exist
		$this->modelObj->bindModel($this->relations, false);

		// Attach behaviors
		foreach (Set::normalize($this->actsAs) as $behavior => $config) {
			$this->modelObj->Behaviors->attach($behavior, $config);
		}
	}

/**
 * creates the singular name for use in views.
 *
 * @param string $name
 * @return string $name
 * @access protected
 */
	function _singularName($name) {
		return Inflector::variable(Inflector::singularize($name));
	}

/**
 * Creates the plural name for views
 *
 * @param string $name Name to use
 * @return string Plural name for views
 * @access protected
 */
	function _pluralName($name) {
		return Inflector::variable(Inflector::pluralize($name));
	}

/**
 * Creates the singular human name used in views
 *
 * @param string $name Controller name
 * @return string Singular human name
 * @access protected
 */
	function _singularHumanName($name) {
		return Inflector::humanize(Inflector::underscore(Inflector::singularize($name)));
	}

/**
 * Creates the proper controller path for the specified controller class name
 *
 * @param string $name Controller class name
 * @return string Path to controller
 * @access protected
 */
	function _controllerPath($name) {
		return Inflector::camelize($name);
	}

/**
 * Creates the proper controller plural name for the specified controller class name
 *
 * @param string $name Controller class name
 * @return string Controller plural name
 * @access protected
 */
	function _controllerName($name) {
		return Inflector::pluralize(Inflector::camelize($name));
	}

/**
 * Returns associations for controllers models.
 *
 * @return  array $associations
 * @access private
 */
	function __associations(&$model) {
		$keys = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
		$associations = array();

		foreach ($keys as $key => $type) {
			foreach ($model->{$type} as $assocKey => $assocData) {
				$associations[$type][$assocKey]['alias'] = $assocKey;
				$associations[$type][$assocKey]['className'] = $assocData['className'];
				$associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
				$associations[$type][$assocKey]['primaryKey'] = $model->{$assocKey}->primaryKey;
				$associations[$type][$assocKey]['displayField'] = $model->{$assocKey}->displayField;
				$associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocData['className']));
				$associations[$type][$assocKey]['fields'] =  array_keys($model->{$assocKey}->schema(true));

				if ($type == 'hasAndBelongsToMany') {
					$associations[$type][$assocKey]['associationForeignKey'] = $assocData['associationForeignKey'];
					$associations[$type][$assocKey]['joinTable'] = $assocData['joinTable'];
				}
			}
		}
		return $associations;
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
		foreach ($this->validate as $fieldName => $rules) {
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
		$this->validate = $validationRules;
	}

/**
 * Constructs a validation message for a given fieldname/rule combination
 *
 * @param string $fieldName fieldname
 * @param string $rule name of a rule
 * @param array $options validation rule options
 * @return string
 */
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

/**
 * Searches haystack for needle. Case-insensitive on the haystack
 *
 * @param mixed $needle The searched value
 * @param array $haystack The stack
 * @param bool $strict If the third parameter strict is set to TRUE then the in_array() function will also check the types of the needle in the haystack.
 * @return bool
 */
	function _in_arrayi($needle, $haystack, $strict = false) {
		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

/**
 * Returns an attribute as a formatted string
 *
 * @param $attribute Name of class attribute to format
 * @param $num Number of tabs to prepend to each line
 * @param $first Prepend the first line with tabs?
 * @return string containing formatted array
 **/
	function formatted($attribute, $num = 2, $first = true) {
		if ($attribute === 'validate') {
			return $this->_formattedValidations($num, $first);
		} else if (is_string($attribute)) {
			return $this->_format($this->$attribute, $num, $first);
		} else {
			return $this->_format($attribute, $num, $first);
		}
	}

/**
 * Returns the contents of a formatted array
 *
 * @param $attribute Variable holding attribute contents
 * @param $num Number of tabs to prepend to each line
 * @param $first Prepend the first line with tabs?
 * @return string containing formatted array
 **/
	function _format($attribute, $num = 2, $first = true) {
		$result = array();
		foreach (Set::normalize($attribute) as $attribute => $config) {
			$currentLine = "'" . $attribute . "'";
			if (empty($config)) {
				$result[] = $currentLine . ',';
			} else {
				$currentLine = $currentLine . ' => ';

				$lines = preg_split("/(\r?\n)/", str_replace('array (', 'array(', var_export($config, true)));
				$lastLine = count($lines) - 1;
				foreach ($lines as $i => $line) {
					if ($i == 0) {
						$result[] = $currentLine . str_replace('  ', "\t", $line);
					} else if ($i == $lastLine) {
						$result[] = str_replace('  ', "\t", "{$line},");
					} else {
						$result[] = str_replace('  ', "\t", $line);
					}
				}
			}
		}

		$tabs = '';
		while ($num > 0) {
			$tabs .= "\t";
			$num--;
		}

		foreach ($result as $i => $line) {
			if (trim($line) == 'array(') {
				$result[$i-1] .= ' array(';
				unset($result[$i]);
			} else if ($i == 0) {
				if ($first) {
					$result[$i] = $tabs . $line;
				} else {
					$result[$i] = $line;
				}
			} else {
				$result[$i] = $tabs . $line;
			}
		}
		return implode("\n", $result) . "\n";
	}

/**
 * Returns a formatted validation array
 *
 * @param $num Number of tabs to prepend to each line
 * @param $first Prepend the first line with tabs?
 * @return string containing formatted array
 **/
	function _formattedValidations($num = 2, $first = true) {
		$results = array();
		foreach ($this->validate as $field => $validations) {
			$results[] = "'" . $field . "' => array(";
			foreach ($validations as $key => $options) {
				$results[] = "\t'" . $key . "' => array(";
				foreach ($options as $option => $value) {
					if ($option === 'rule') {
						$line = "\t\t'rule' => array(";
						if (is_array($options['rule'])) {
							$ruleOptionsCount = count($options['rule']) - 1;
							$i = 0;
							foreach ($value as $k => $v) {
								if (!is_array($v)) {
									if (is_numeric($v)) {
										$line = $line . $v;
									} else if ($v === false) {
										$line = $line . 'false';
									} else if ($v === true) {
										$line = $line . 'true';
									} else if ($v === null) {
										$line = $line . 'null';
									} else {
										$line = $line . "'{$v}'";
									}
								} else {
									$paramCount = count($v) - 1;
									$j = 0;
									$line = $line . 'array(';
									foreach ($v as $param => $paramValue) {
										if (is_numeric($paramValue)) {
											$line = $line . $paramValue;
										} else if ($paramValue === false) {
											$line = $line . 'false';
										} else if ($paramValue === true) {
											$line = $line . 'true';
										} else if ($paramValue === null) {
											$line = $line . 'null';
										} else {
											$line = $line . "'{$paramValue}'";
										}
										if ($j < $paramCount) {
											$line = $line . ", ";
										}
										$j++;
									}
									$line = $line . ")";
								}
								if ($i < $ruleOptionsCount) {
									$line = $line . ", ";
								}
								$i++;
							}
							$results[] = $line .  '),';
						} else {
							if (is_numeric($value)) {
								$results[] = $line . $value . '),';
							} else if ($value === false) {
								$results[] = $line . 'false' . '),';
							} else if ($value === true) {
								$results[] = $line . 'true' . '),';
							} else if ($value === null) {
								$results[] = $line . 'null' . '),';
							} else {
								$results[] = $line . "'{$value}'" . '),';
							}
						}
						continue;
					}
					if ($option === 'message') {
						$results[] = "\t\t'message' => __d('{$this->plugin}', '{$value}', true),";
						continue;
					}
					$line = "\t\t'{$option}' => ";
					if (is_numeric($value)) {
						$line = $line . $value;
					} else if ($value === false) {
						$line = $line . "false";
					} else if ($value === true) {
						$line = $line . "true";
					} else if ($value === null) {
						$line = $line . "null";
					} else {
						$line = $line . "'{$value}'";
					}
					$results[] = $line . ",";
				}
				$results[] = "\t),";
			}
			$results[] = '),';
		}

		$tabs = '';
		while ($num > 0) {
			$tabs .= "\t";
			$num--;
		}

		foreach ($results as $i => $line) {
			if (trim($line) == 'array(') {
				$results[$i-1] .= ' array(';
				unset($results[$i]);
			} else if ($i == 0) {
				if ($first) {
					$results[$i] = $tabs . $line;
				} else {
					$results[$i] = $line;
				}
			} else {
				$results[$i] = $tabs . $line;
			}
		}
		return implode("\n", $results) . "\n";
	}

}
