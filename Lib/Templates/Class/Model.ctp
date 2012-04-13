<?php
/**
 * Model template file.
 *
 * Used by bake to create new Model files.
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
 * @subpackage    cake.console.libs.templates.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "<?php\n"; ?>
class <?php echo $admin->modelName ?> extends <?php echo Inflector::camelize($admin->plugin) ?>AppModel {

	public $displayField = '<?php echo $admin->displayField; ?>';
	public $useDbConfig  = '<?php echo $admin->useDbConfig; ?>';
	public $useTable     = '<?php echo $admin->useTable; ?>';
	public $primaryKey   = '<?php echo $admin->primaryKey; ?>';
<?php
if (!empty($admin->actsAs)) : ?>
	public $actsAs       = array(
		<?php echo $admin->formatted('actsAs', 2, false); ?>
	);
<?php endif; ?>
<?php if (!empty($admin->finders)) : ?>
	public $findMethods = array(
<?php 	foreach ($admin->finders as $findMethod) : ?>
		'<?php echo $findMethod; ?>' => true,
<?php 	endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($admin->relatedFinders)) : ?>
	public $relatedMethods = array(
<?php 	foreach ($admin->relatedFinders as $relatedMethod) : ?>
		'<?php echo $relatedMethod; ?>' => true,
<?php 	endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($admin->validate)): ?>

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			<?php echo $admin->formatted('validate', 3, false) ?>
		);
	}
<?php endif; ?>
<?php
foreach ($admin->relations as $assocType => $values) {
	echo "\n\public \$$assocType = array(\n";
	echo $admin->formatted($values, 2);
	echo "\t);\n";
}

echo "\n{$methods}";
?>
}