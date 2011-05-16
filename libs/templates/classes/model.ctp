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
class <?php echo $admin->modelName ?><?php echo Inflector::humanize($admin->plugin); ?> extends <?php echo Inflector::humanize($admin->plugin); ?>AppModel {

	var $name         = '<?php echo $admin->modelName; ?><?php echo Inflector::humanize($admin->plugin); ?>';
	var $displayField = '<?php echo $admin->displayField; ?>';
	var $useDbConfig  = '<?php echo $admin->useDbConfig; ?>';
	var $useTable     = '<?php echo $admin->useTable; ?>';
	var $primaryKey   = '<?php echo $admin->primaryKey; ?>';
<?php
if (!empty($admin->behaviors)) : ?>
	var $actsAs       = array(
		<?php echo $admin->formatted('behaviors', 2, false); ?>
	);
<?php endif; ?>
<?php if (!empty($admin->finders)) : ?>
	var $_findMethods = array(
<?php 	foreach ($admin->finders as $findMethod) : ?>
		'<?php echo $findMethod; ?>' => true,
<?php 	endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($admin->relatedFinders)) : ?>
	var $_relatedMethods = array(
<?php 	foreach ($admin->relatedFinders as $relatedMethod) : ?>
		'<?php echo $relatedMethod; ?>' => true,
<?php 	endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($admin->validate)): ?>

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			<?php echo $admin->formatted('validate', 3, false) ?>
		);
	}
<?php endif; ?>
<?php
foreach ($admin->relations as $assocType => $values) {
	echo "\n\tvar \$$assocType = array(\n";
	echo $admin->formatted($values, 2);
	echo "\t);\n";
}

echo "\n{$methods}";
?>
}