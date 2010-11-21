<?php
// Create a model object
$modelObj = ClassRegistry::init(array(
	'class' => $admin->modelName,
	'table' => $admin->useTable,
	'ds'    => $admin->useDbConfig
));
if (!empty($modelObj->belongsTo) && !empty($modelObj->hasAndBelongsToMany)) :

?>
	function _related<?php echo Inflector::camelize($find); ?>() {
<?php
$compacts = array();

foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
    foreach ($modelObj->{$assoc} as $associationName => $relation):
        if (!empty($associationName)):
            $otherModelName = Inflector::camelize(Inflector::singularize($associationName));
            $otherPluralName = Inflector::variable(Inflector::pluralize($associationName));
            echo "\t\t\${$otherPluralName} = \$this->{$otherModelName}->find('list');\n";
            $compact[] = "'{$otherPluralName}'";
        endif;
    endforeach;
endforeach;
if (!empty($compact)):
    echo "\t\treturn compact(".join(', ', $compact).");\n";
endif;

?>
	}
<?php endif; ?>