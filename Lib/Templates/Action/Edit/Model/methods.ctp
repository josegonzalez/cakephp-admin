	public function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['<?php echo $admin->modelName; ?>.<?php echo $admin->primaryKey?>'] = $query['<?php echo $admin->primaryKey; ?>'];
			$query['limit'] = 1;
			unset($query['<?php echo $admin->primaryKey; ?>']);

			return $query;
		}
		if (empty($results[0])) {
			return false;
		}
		return $results[0];
	}<?php

if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) :

?>


	function _related<?php echo Inflector::camelize($find); ?>() {
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