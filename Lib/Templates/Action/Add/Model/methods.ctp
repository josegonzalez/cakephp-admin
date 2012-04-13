<?php
// Create a model object
if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) :

?>
	public function _related<?php echo Inflector::camelize($find); ?>() {
<?php
$compacts = array();

foreach (array('belongsTo', 'hasAndBelongsToMany') as $assocType):
	if (!empty($admin->associations[$assocType])):
		foreach ($admin->associations[$assocType] as $i => $relation):
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