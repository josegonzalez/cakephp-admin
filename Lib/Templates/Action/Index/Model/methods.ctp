<?php

$fields = $admin->actions[$find]['config']['fields'];
$searches = $admin->actions[$find]['config']['search'];
$list_filter = $admin->actions[$find]['config']['list_filter'];

// Available variables: [modelObj, list_filter, searches, fields]

?>
	public function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
<?php if (!empty($list_filter)) : ?>
<?php foreach ($list_filter as $field => $config) : ?>
			if (isset($query['named']['<?php echo $field; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}.{$field}"; ?>'] = $query['named']['<?php echo $field; ?>'];
			}
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($searches)) : ?>
<?php foreach ($searches as $field => $config) : ?>
<?php if ($field !== 'id' && ($config['type'] == 'text' || $config['type'] == 'string')) {
	$modifier = ' LIKE';
	$query = "'%' . \$query['named']['{$admin->modelName}.{$field}'] . '%';\n";
} else {
	$modifier = '';
	$query = "\$query['named']['{$admin->modelName}.{$field}'];\n";
}
?>
			if (isset($query['named']['<?php echo "{$admin->modelName}.{$field}"; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}.{$field}{$modifier}"; ?>'] = <?php echo $query; ?>
			}
<?php endforeach; ?>

<?php endif; ?>
<?php if (!empty($searches)) : ?>
<?php foreach ($searches as $field => $config) : ?>
<?php if ($field !== 'id' && ($config['type'] == 'text' || $config['type'] == 'string')) {
	$modifier = ' LIKE';
	$query = "'%' . \$query['data']['{$admin->modelName}']['{$field}'] . '%';\n";
} else {
	$modifier = '';
	$query = "\$query['data']['{$admin->modelName}']['{$field}'];\n";
}
?>
			if (!empty($query['data']['<?php echo "{$admin->modelName}"; ?>']['<?php echo $field; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}.{$field}{$modifier}"; ?>'] = <?php echo $query; ?>
			}
<?php endforeach; ?>

<?php endif; ?>
<?php if (!empty($fields)) : ?>
			$query['fields'] = array('<?php echo join("', '", $fields); ?>');
<?php endif; ?>
<?php $contains = array(); ?>
<?php if (!empty($fields)) : ?>
<?php	foreach (array_keys($admin->modelObj->schema()) as $field) : ?>
<?php		if (!in_array($field, $fields)) continue; ?>
<?php		if (!empty($admin->associations['belongsTo'])) : ?>
<?php			foreach ($admin->associations['belongsTo'] as $alias => $details) : ?>
<?php				if ($field === $details['foreignKey']) : ?>
<?php					$contains[] = "{$details['alias']}.{$details['primaryKey']}"; ?>
<?php					$contains[] = "{$details['alias']}.{$details['displayField']}"; ?>
<?php				endif; ?>
<?php			endforeach; ?>
<?php		endif; ?>
<?php	endforeach; ?>
<?php endif; ?>
<?php if (!empty($contains)) : ?>
			$query['contain'] = array('<?php echo join("', '", $contains); ?>');
<?php else : ?>
			$query['contain'] = false;
<?php endif; ?>
			unset($query['data'], $query['named']);

			if (!empty($query['operation'])) {
				return $this->_findCount($state, $query, $results);
			}
			return $query;
		}
		if (!empty($query['operation'])) {
			return $this->_findCount($state, $query, $results);
		}
		return $results;
	}