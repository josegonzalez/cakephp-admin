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
	var $recursive    = -1;
<?php
$findMethods = array();
$relatedMethods = array();
foreach ($metadata as $action => $data) {
	if (!empty($data->finders)) {
		foreach ($data->finders as $finder) {
			$findMethods[] = $finder;
		}
	}
	if (!empty($data->related)) {
		foreach ($data->related as $relatedFinder) {
			$relatedMethods[] = $relatedFinder;
		}
	}
}
if (!empty($findMethods)) : ?>
	var $_findMethods = array(
<?php foreach ($findMethods as $findMethod): ?>
		'<?php echo $findMethod; ?>' => true,
<?php endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($relatedMethods)) : ?>
	var $_relatedMethods = array(
<?php foreach ($relatedMethods as $relatedMethod): ?>
		'<?php echo $relatedMethod; ?>' => true,
<?php endforeach; ?>
	);
<?php endif; ?>
<?php if (!empty($admin->validations)): ?>

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
<?php 	foreach ($admin->validations as $field => $validations) : ?>
			'<?php echo $field; ?>' => array(
<?php 		foreach ($validations as $key => $options) : ?>
				'<?php echo $key; ?>' => array(
<?php 			foreach ($options as $option => $value) : ?>
<?php 				if ($option === 'rule') : ?>
					'rule' => array(<?php
						if (is_array($options['rule'])) {
							$ruleOptionsCount = count($options['rule']) - 1;
							$i = 0;
							foreach ($value as $k => $v) {
								if (!is_array($v)) {
									if (is_numeric($v)) {
										echo $v;
									} else if ($v === false) {
										echo "false";
									} else if ($v === true) {
										echo "true";
									} else if ($v === null) {
										echo "null";
									} else {
										echo "'{$v}'";
									}
								} else {
									$paramCount = count($v) - 1;
									$j = 0;
									echo "array(";
									foreach ($v as $param => $paramValue) {
										if (is_numeric($paramValue)) {
											echo $paramValue;
										} else if ($paramValue === false) {
											echo "false";
										} else if ($paramValue === true) {
											echo "true";
										} else if ($paramValue === null) {
											echo "null";
										} else {
											echo "'{$paramValue}'";
										}
										if ($j < $paramCount) {
											echo ", ";
										}
										$j++;
									}
									echo ")";
								}
								if ($i < $ruleOptionsCount) {
									echo ", ";
								}
								$i++;
							}
						} else {
							if (is_numeric($value)) {
								echo $value;
							} else if ($value === false) {
								echo "false";
							} else if ($value === true) {
								echo "true";
							} else if ($value === null) {
								echo "null";
							} else {
								echo "'{$value}'";
							}
						}
?>),
<?php 					continue; ?>
<?php 				endif; ?>
<?php 				if ($option === 'message') : ?>
					'message' => __d('<?php echo $admin->plugin; ?>', '<?php echo $value; ?>', true),
<?php					continue;?>
<?php 				endif; ?>
					'<?php echo $option; ?>' => <?php  if (is_numeric($value)) {
															echo $value;
														} else if ($value === false) {
															echo "false";
														} else if ($value === true) {
															echo "true";
														} else if ($value === null) {
															echo "null";
														} else {
															echo "'{$value}'";
														} ?>,
<?php 			endforeach; ?>
				),
<?php 		endforeach; ?>
			),
<?php 	endforeach; ?>
		);
	}
<?php endif; ?>
<?php
foreach (array('hasOne', 'belongsTo') as $assocType):
	if (!empty($associations[$assocType])):
		$typeCount = count($associations[$assocType]);
		echo "\n\tvar \$$assocType = array(";
		foreach ($associations[$assocType] as $i => $relation):
			$out = "\n\t\t'{$relation['alias']}' => array(\n";
			$out .= "\t\t\t'className' => '{$relation['className']}',\n";
			$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
			$out .= "\t\t\t'conditions' => '',\n";
			$out .= "\t\t\t'fields' => '',\n";
			$out .= "\t\t\t'order' => ''\n";
			$out .= "\t\t)";
			if ($i + 1 < $typeCount) {
				$out .= ",";
			}
			echo $out;
		endforeach;
		echo "\n\t);\n";
	endif;
endforeach;

if (!empty($associations['hasMany'])):
	$belongsToCount = count($associations['hasMany']);
	echo "\n\tvar \$hasMany = array(";
	foreach ($associations['hasMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'dependent' => false,\n";
		$out .= "\t\t\t'conditions' => '',\n";
		$out .= "\t\t\t'fields' => '',\n";
		$out .= "\t\t\t'order' => '',\n";
		$out .= "\t\t\t'limit' => '',\n";
		$out .= "\t\t\t'offset' => '',\n";
		$out .= "\t\t\t'exclusive' => '',\n";
		$out .= "\t\t\t'finderQuery' => '',\n";
		$out .= "\t\t\t'counterQuery' => ''\n";
		$out .= "\t\t)";
		if ($i + 1 < $belongsToCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;

if (!empty($associations['hasAndBelongsToMany'])):
	$habtmCount = count($associations['hasAndBelongsToMany']);
	echo "\n\tvar \$hasAndBelongsToMany = array(";
	foreach ($associations['hasAndBelongsToMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
		$out .= "\t\t\t'unique' => true,\n";
		$out .= "\t\t\t'conditions' => '',\n";
		$out .= "\t\t\t'fields' => '',\n";
		$out .= "\t\t\t'order' => '',\n";
		$out .= "\t\t\t'limit' => '',\n";
		$out .= "\t\t\t'offset' => '',\n";
		$out .= "\t\t\t'finderQuery' => '',\n";
		$out .= "\t\t\t'deleteQuery' => '',\n";
		$out .= "\t\t\t'insertQuery' => ''\n";
		$out .= "\t\t)";
		if ($i + 1 < $habtmCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;

echo "\n{$methods}";
?>
}