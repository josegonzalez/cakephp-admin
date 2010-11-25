<?php

$modelObj = ClassRegistry::init(array(
	'class' => $admin->modelName,
	'table' => $admin->useTable,
	'ds'    => $admin->useDbConfig
));

$fields = $admin->actions[$find]['config']['fields'];
$searches = $admin->actions[$find]['config']['search'];
$list_filter = $admin->actions[$find]['config']['list_filter'];

// Available variables: [modelObj, list_filter, searches, fields]

?>
	function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
<?php foreach ($list_filter as $field => $config) : ?>
			if (!empty($query['params']['named']['<?php echo $field; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}Admin.{$field}"; ?>'] = $query['params']['named']['<?php echo $field; ?>'];
			}
<?php endforeach; ?>

<?php foreach ($searches as $field => $config) : ?>
			if (!empty($query['data']['<?php echo $admin->modelName; ?>']['<?php echo $field; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}Admin.{$field}"; ?>'] = $query['data']['<?php echo $admin->modelName; ?>Admin']['<?php echo $field; ?>'];
			}
<?php endforeach; ?>

<?php if (!empty($fields)) : ?>
			$query['fields'] = array('<?php echo join("', '", $fields); ?>');
<?php endif; ?>
<?php $contains = array(); ?>
<?php if (!empty($fields)) : ?>
<?php	foreach (array_keys($modelObj->schema()) as $field) : ?>
<?php		if (!in_array($field, $fields)) continue; ?>
<?php		if (!empty($associations['belongsTo'])) : ?>
<?php			foreach ($associations['belongsTo'] as $alias => $details) : ?>
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