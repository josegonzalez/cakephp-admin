<?php
/**
 * Custom Route class auto-enables /:page routes
 * Enables you to add new pages without having to manually specify a shortcut
 * route in your routes.php file
 *
 * To use, drop this into app/libs/routes/page_route.php and add
 * the following to the top of app/config/routes.php:
 *
 * App::import('Lib', 'routes/CakeAdminRoute');
 *
 * To trigger it, specify the routeClass in the route's options array, along
 * with the regex to allow subpages to be parsed:
 *
 * Router::connect('/admin/*', array(),
 *     array('routeClass' => 'CakeAdminRoute', 'admin_prefix' => 'admin')
 * );
 *
 * Note that if a page has the same name as a controller/plugin, the page will
 * take precedence since it is included before Router::__connectDefaultRoutes()
 * is called.
 *
 * @author Jose Gonzalez (support@savant.be)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @see CakeRoute
 */
class CakeAdminRoute extends CakeRoute {

/**
 * An array of additional parameters for the Route.
 *
 * @var array
 * @access public
 */
	var $options = array(
		'admin_prefix' => 'admin',
		'enabled' => array(),
	);

/**
 * Constructor for a Route
 *
 * @param string $template Template string with parameter placeholders
 * @param array $defaults Array of defaults for the route.
 * @param string $params Array of parameters and additional options for the Route
 * @return void
 * @access public
 */
	function CakeAdminRoute($template, $defaults = array(), $options = array()) {
		$this->template = $template;
		$this->defaults = (array) $defaults;
		$this->options = array_merge($this->options, (array) $options);
		$this->_cache = $this->loadCache();
	}

/**
 * Loads the cached admin routes that may match this request
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 */
	function loadCache() {
		$controllers = App::objects('controller');
		$controllers = array_combine($controllers, $controllers);
		unset($controllers['App']);

		$possibilities = array();
		$baseMethods = $this->_getClassMethods('');
		$prefix = $this->options['admin_prefix'] . '_';

		foreach ($controllers as $controller) {
			if (!in_array($controller, $this->options['enabled'])) {
				continue;
			}

			$methods = $this->_getClassMethods(
				$this->_getPluginControllerPath($controller),
				$baseMethods
			);
			foreach ($methods as $method) {
				if (substr($method, 0, strlen($prefix)) == $prefix) {
					$possibilities[] = strtolower(Inflector::underscore($controller)) . '/' . substr($method, strlen($prefix));
				}
			}
		}

		if (empty($possibilities)) {
			return array();
		}

		$possibilities = array_combine($possibilities, array_map('strlen', $possibilities));
		arsort($possibilities);

		return array_keys($possibilities);
	}

/**
 * Gets the class methods of a controller
 *
 * @param string $ctrlName 
 * @param string $filter 
 * @return void
 * @author Jose Gonzalez
 */
	function _getClassMethods($ctrlName = null, $filter = array()) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num + 1);
		}

		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (!is_array($properties)) {
			return array();
		}

		if (!in_array($ctrlclass, array('Controller', 'AppController')) && array_key_exists('scaffold', $properties)) {
			if ($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} elseif ($properties['scaffold']) {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}

		return array_diff($methods, $filter);
	}

/**
 * Tokenizes controller paths
 *
 * @param string $ctrlName 
 * @return void
 * @author Jose Gonzalez
 */
	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

/**
 * Parses a string url into an array. If an admin page can be routed,
 * it is routed
 *
 * @param string $url The url to parse
 * @return mixed false on failure, or an array of request parameters
 */
	function parse($url) {
		$new_url = strtolower(str_replace('/' . $this->options['admin_prefix'] . '/', '', $url));
		$params = parent::parse($new_url);
		$routed = false;

		foreach ($this->_cache as $match) {
			if ($match == $new_url) {
				list($params['controller'], $params['action']) = explode('/', $match);
				$params['action'] = $this->options['admin_prefix'] . '_' . $params['action'];
				$params['prefix'] = $this->options['admin_prefix'];
				$params[$this->options['admin_prefix']] = true;
				$routed = true;
				break;
			}
		}

		if (!$routed) {
			foreach ($this->_cache as $match) {
				if (preg_match('/^' . preg_quote($match, '/') . '/', $new_url)) {
					list($params['controller'], $params['action']) = explode('/', $match);
					$params['action'] = $this->options['admin_prefix'] . '_' . $params['action'];
					$params['prefix'] = $this->options['admin_prefix'];
					$params[$this->options['admin_prefix']] = true;
					$routed = true;
					break;
				}
			}
		}

		if (!$routed) {
			return false;
		}

		if (empty($params['pass'])) {
			$params['pass'] = array();
		}
		if (empty($params['named'])) {
			$params['named'] = array();
		}

		return $params;
	}

}