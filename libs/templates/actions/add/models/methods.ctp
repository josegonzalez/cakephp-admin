<?php
// Create a model object
$modelObj = ClassRegistry::init(array(
	'class' => $admin->modelName,
	'table' => $admin->useTable,
	'ds'    => $admin->useDbConfig
));
if (!empty($associations['belongsTo']) || !empty($assocations['hasAndBelongsToMany'])) :

?>
	function _related<?php echo Inflector::camelize($find); ?>() {
<?php
$compacts = array();

foreach (array('belongsTo', 'hasAndBelongsToMany') as $assocType):
	if (!empty($associations[$assocType])):
		foreach ($associations[$assocType] as $i => $relation):
			$otherModelName = Inflector::camelize(Inflector::singularize($relation['alias']));
			$otherPluralName = Inflector::variable(Inflector::pluralize($relation['alias']));
			echo "\t\t\${$otherPluralName} = \$this->{$otherModelName}->find('list');\n";
			$compact[] = "'{$otherPluralName}'";
		endforeach;
	endif;
endforeach;
if (!empty($compact)):
    echo "\t\treturn compact(".join(', ', $compact).");\n";
endif;

?>
	}<?php endif; ?>