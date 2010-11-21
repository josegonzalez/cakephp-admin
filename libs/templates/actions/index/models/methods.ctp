<?php

$modelObj = ClassRegistry::init(array(
	'class' => $admin->modelName,
	'table' => $admin->useTable,
	'ds'    => $admin->useDbConfig
));

// Iterate over all the fields displayed on this view
$fields = array();

if (empty($admin->views[$find]['fields'])) {
    // $fields is all fields
    foreach (array_keys($modelObj->schema()) as $field) {
        $fields[] = $field;
    }
} else {
    foreach ($admin->views[$find]['fields'] as $field) {
        $fields[] = $field;
    }
}

if (!empty($admin->views[$find]['order'])) {
    $order = $admin->views[$find]['order'];
    if (count($order) == 1) {
        $order = current($order);
    }
} else {
    $order = "{$admin->primaryKey} ASC";
}

if (is_array($order)) {
    $order = join("', '", $fields);
}

$searches = array();
$filters = array();
// Get filterable fields
if (!empty($admin->views[$find]['search'])) {
    foreach ($admin->views[$find]['search'] as $filter) {
        $searches[] = $filter;
        $filters[] = $filter;
    }
}
if (!empty($admin->views[$find]['list_filter'])) {
    foreach (array_keys($admin->views[$find]['list_filter']) as $filter) {
        $filters[] = $filter;
    }
}
$filters = array_unique($filters);
// Available variables: [modelObj, filters, fields, order]

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