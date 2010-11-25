<?php

$modelObj = ClassRegistry::init(array(
	'class' => $admin->modelName,
	'table' => $admin->useTable,
	'ds'    => $admin->useDbConfig
));

$fields = $admin->actions[$find]['config']['fields'];
$order = $admin->actions[$find]['config']['order'];
$searches = $admin->actions[$find]['config']['searches'];
$filters = $admin->actions[$find]['config']['filters'];

// Available variables: [modelObj, filters, searches, fields, order]

?>
	function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
<?php foreach ($filters as $filter) : ?>
			if (!empty($query['params']['named']['<?php echo $filter; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}Admin.{$filter}"; ?>'] = $query['params']['named']['<?php echo $filter; ?>'];
			}
<?php endforeach; ?>

<?php foreach ($searches as $search) : ?>
			if (!empty($query['data']['<?php echo $admin->modelName; ?>']['<?php echo $search; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}Admin.{$search}"; ?>'] = $query['data']['<?php echo $admin->modelName; ?>Admin']['<?php echo $search; ?>'];
			}
<?php endforeach; ?>
<?php if (!empty($fields)) : ?>
			$query['fields'] = array('<?php echo join("', '", $fields); ?>');
<?php endif; ?>
			$query['order'] = array('<?php echo $order; ?>');
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