[![Build Status](https://travis-ci.org/josegonzalez/cakephp-admin.png?branch=master)](https://travis-ci.org/josegonzalez/cakephp-admin) [![Coverage Status](https://coveralls.io/repos/josegonzalez/cakephp-admin/badge.png?branch=master)](https://coveralls.io/r/josegonzalez/cakephp-admin?branch=master) [![Total Downloads](https://poser.pugx.org/josegonzalez/cakephp-admin/d/total.png)](https://packagist.org/packages/josegonzalez/cakephp-admin) [![Latest Stable Version](https://poser.pugx.org/josegonzalez/cakephp-admin/v/stable.png)](https://packagist.org/packages/josegonzalez/cakephp-admin)
# CakeAdmin Plugin

Generate an awesome backend for your CakePHP application. Under **EXTREMELY** heavy development.

## Background

I'm jealous of Django admin and think `cake bake` is not the greatest to work with. So I started toying with my own [app skeleton](http://github.com/josegonzalez/app_skellington). I realized halfway through that it sucks to base everything on the DB. What if I want to include behaviors other than those included in app_skellington? Impossible.

So I started work on a Django admin for CakePHP. Here is the beginning of that. And it will rule. And you will love me. And you'll make oodles of cash from this likely. I'm hoping some of this goes to me, but I can dream, can't I?

## Requirements

* CakePHP 2.x
* Patience
* PHP 5.2.8

## Installation

_[Using [Composer](http://getcomposer.org/)]_

Add the plugin to your project's `composer.json` - something like this:

	{
		"require": {
			"josegonzalez/cakephp-admin": "dev-master"
		}
	}

Because this plugin has the type `cakephp-plugin` set in it's own `composer.json`, composer knows to install it inside your `/Plugins` directory, rather than in the usual vendors file. It is recommended that you add `/Plugins/Upload` to your .gitignore file. (Why? [read this](http://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).)

_[Manual]_

* Download this: [http://github.com/josegonzalez/cakephp-admin/zipball/master](http://github.com/josegonzalez/cakephp-admin/zipball/master)
* Unzip that download.
* Copy the resulting folder to `app/Plugin`
* Rename the folder you just copied to `CakeAdmin`

_[GIT Submodule]_

In your app directory type:

	git submodule add -b master git://github.com/josegonzalez/cakephp-admin.git Plugin/CakeAdmin
	git submodule init
	git submodule update

_[GIT Clone]_

In your `Plugin` directory type:

	git clone -b master git://github.com/josegonzalez/cakephp-admin.git CakeAdmin

### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:

	CakePlugin::load('CakeAdmin');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

## Usage

Create a folder in your `app/Lib` folder called `Admin`. This folder will contain all of your `CakeAdmin` classes. We'll use a `Post` model for our example admin section.

Create a @PostCakeAdmin.php@ file in your `app/Lib/Admin` folder. This will contain your `PostCakeAdmin` class. [This gist](http://gist.github.com/583603) is an example of such a class.

Once your `PostCakeAdmin.php` file has been created, run `cake CakeAdmin.admin` from the shell. This will create the respective plugin if not already (in our case, an `Admin` plugin), as well as all the models, views, and controllers for ALL available `*CakeAdmin.php` files. In our case, you can then access the admin section at `example.com/admin/posts`.

## Parsing

## Model Validation Rules

Validation rules are parsed in two steps. The `CakeAdmin` `__construct()` method parses each validation rule for a message and a rule. If the message is not found, then a generic message is attached. This avoids the need to edit the model file at a later date.

The default validation rules can be overridden easily. They are defined within the @CakeAdmin@ class. The following is default set:

```
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
```

If you would like to override them within your own admin classes, either include the above in your variable declaration, or override in the `__construct()` method:

```
function __construct() {
    $this->_validationMessages = array_merge(array(
        'custom' => 'Custom message for {{field}}'),
        $this->_validationMessages
    );
    parent::__construct();
}
```

All the default validation rules work as normal, with option parsing and everything. For example, given:

```
var $validate    = array(
    'content'   => array(
        'required'  => 'notempty',
        'maxLength' => array('maxLength', 255),
        'inList' => array(
            'rule' => array('inList', array(true, 'b', null)),
            'allowEmpty' => true,
            'on' => 'create',
            'message' => 'Please be in the good list'
        )
    )
);
```

`CakeAdmin` will generate the following @__construct()@ method:

```
function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->validate = array(
        'content' => array(
            'required' => array(
                'rule' => array('notempty'),
                'message' => __d('admin', 'Content cannot be empty', true),
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => __d('admin', 'Content must have less than 255 characters', true),
            ),
            'inList' => array(
                'rule' => array('inList', array(true, 'b', null)),
                'allowEmpty' => true,
                'on' => 'create',
                'message' => __d('admin', 'Please be in the good list', true),
            ),
        ),
    );
}
```

All validation rules are wrapped in domain-specific internationalization calls - `__d()` - meaning it is possible to further internationalize your generated admin section without mucking with the included classes.

### Action Configuration

When defining custom actions, there may be certain defaults that need to be set. All the included actions in CakeAdmin come with `CakeAdminActionConfig` classes. These define various properties, such as the action type, the plugin that the action is included in, whether it is enabled by default, and configuration defaults. Because the process of merging default configurations and your personal configuration is not standard, each `CakeAdminActionConfig` class can define it's own `mergeVars($configuration)` method. It can then proceed to merge the configuration as it wants, and returns the merged configuration. If omitted from the class implementation, an `array_merge` will be performed upon the configuration and the defaults.

The following is an example implementation of `mergeVars($configuration)`:

```
function mergeVars($admin, $configuration = array()) {
    if (empty($configuration)) return $this->defaults;

    $modelObj = ClassRegistry::init(array(
        'class' => $admin->modelName,
        'table' => $admin->useTable,
        'ds'    => $admin->useDbConfig
    ));

    $filters = array();
    $search = array();
    $fields = array();
    $schema = $modelObj->schema();

    if (empty($configuration['fields']) || (in_array('*', (array) $configuration['fields']))) {
        // $fields is all fields
        foreach (array_keys($modelObj->schema()) as $field) {
            $fields[] = $field;
        }
    } else {
        foreach ((array) $configuration['fields'] as $field) {
            if ($field !== '*') $fields[] = $field;
        }
    }

    if (!empty($configuration['list_filter'])) {
        foreach ($configuration['list_filter'] as $field => $config) {
            $filters[$field] = (array) $config;
        }
    }

    $configuration['search'] = Set::normalize($configuration['search']);
    if (!empty($configuration['search'])) {
        foreach ($configuration['search'] as $field => $alias) {
            if (!in_array($field, array_keys($filters))) {
                $type = ($schema[$field]['type'] == 'text') ? 'text' : $schema[$field]['type'];
                $search[$field] = array(
                    'type' => ($field === 'id') ? 'text' : $type,
                    'alias' => empty($alias) ? $field : $alias,
                );
            }
        }
    }

    $sort = $fields;
    if ($configuration['sort'] == false) {
        $sort = array();
    } else if (is_array($configuration['sort'])) {
        $sort = $configuration['sort'];
    }

    $configuration = array_merge($this->defaults, $configuration);
    $configuration['list_filter'] = $filters;
    $configuration['search'] = $search;
    $configuration['fields'] = $fields;
    $configuration['sort'] = $sort;

    return $configuration;
}
```

## TODO

The following is a list of things I am planning to implement, in order of most likely to be implemented first.

* <del>Refactor template paths</del>
* <del>Implement mergeVars</del>
* <del>Deprecate Frontmatter</del>
* <del>View Templates, partials, and layouts</del>
* <del>Allow installation of cake_admin generator in base plugins dir</del>
* <del>Formatting wrappers for fields in built-in actions</del>
* Little box for logged-in user in top-left corner of built-in dashboard
* Tree Behavior Support
* Support for CakeAdmin class files in plugins
* Ajax Form-handling and Pagination
* Screencast
* Building into regular application as opposed to plugin
* Security Component Integration
* Controller/Model/View base class enhancements
* <del>Behavior/Helper/Component option parsing</del>
* Authentication Implementations
* Webservice Implementation
* Inline model editing on built-in index action
* Cache integration
* Automagic CakeAdmin file-generation based solely upon DB
* Unit Tests!!!
* Theme support
* Translate Behavior support
* RSS Feeds
* Internationalization
* Bake-like console and web tool

## License

The MIT License (MIT)

Copyright (c) 2010 Jose Diaz-Gonzalez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
