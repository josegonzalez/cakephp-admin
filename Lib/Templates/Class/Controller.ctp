<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "<?php\n";
?>
App::uses('<?php echo Inflector::camelize($admin->plugin) ?>AppController', '<?php echo Inflector::camelize($admin->plugin) ?>.Controller');
class <?php echo $admin->controllerName; ?>Controller extends <?php echo Inflector::camelize($admin->plugin) ?>AppController {

	public $uses = array('<?php echo Inflector::camelize($admin->plugin); ?>.<?php echo $admin->modelName; ?>');
	public $layout = 'default';
<?php

if (!empty($admin->components)) {
    echo "\tpublic \$components = array(\n";
    echo $admin->formatted('components', 2);
    echo "\t);\n";
}
if (!empty($admin->helpers)) {
    echo "\tpublic \$helpers = array(\n";
    echo $admin->formatted('helpers', 2);
    echo "\t);\n";
}

echo "\n{$actions}"; ?>

	public function _customPaginate($findMethod, $mapping = array()) {
		$query = array();
		foreach ($mapping as $field) {
			if (!empty($this->request->data['<?php echo "{$admin->modelName}"; ?>'][$field])) {
				$query["<?php echo $admin->modelName; ?>.{$field}"] = $this->request->data['<?php echo "{$admin->modelName}"; ?>'][$field];
			}
		}
		if (!empty($query)) $this->redirect($query);

		$this->paginate = array($findMethod) + array(
			'data'  => $this->request->data,
			'named' => $this->request->params['named'],
		);
		$results = $this->paginate();

		foreach ($mapping as $field) {
			if (!isset($this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"])) {
				continue;
			}
			if (is_string($this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"]) && strlen($this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"])) {
				$this->request->data['<?php echo "{$admin->modelName}"; ?>'][$field] = $this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"];
			} elseif (!empty($this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"])) {
				$this->request->data['<?php echo "{$admin->modelName}"; ?>'][$field] = $this->request->params['named']["<?php echo "{$admin->modelName}"; ?>.{$field}"];
			}
		}
		return $results;
	}

}