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
				$query['conditions']['<?php echo "{$admin->modelName}.{$filter}"; ?>'] = $query['params']['named']['<?php echo $filter; ?>'];
			}
<?php endforeach; ?>

<?php foreach ($searches as $search) : ?>
			if (!empty($query['data']['<?php echo $admin->modelName; ?>']['<?php echo $search; ?>'])) {
				$query['conditions']['<?php echo "{$admin->modelName}.{$search}"; ?>'] = $query['data']['<?php echo $admin->modelName; ?>']['<?php echo $search; ?>'];
			}
<?php endforeach; ?>
<?php if (!empty($fields)) : ?>
			$query['fields'] = array('<?php echo join("', '", $fields); ?>');
<?php endif; ?>
			$query['order'] = array('<?php echo $order; ?>');

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