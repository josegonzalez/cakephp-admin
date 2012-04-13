	public function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['<?php echo $admin->modelName; ?>.<?php echo $admin->primaryKey?>'] = $query['<?php echo $admin->primaryKey; ?>'];
<?php if (!empty($admin->actions[$find]['config']['contain'])) : ?>
			$query['contain'] = array(
<?php echo $admin->formatted($admin->actions[$find]['config']['contain'], 4); ?>
			);
<?php endif; ?>
			$query['fields'] = array(
<?php echo $admin->formatted(array_keys($admin->actions[$find]['config']['fields']), 4); ?>
			);
			$query['limit'] = 1;
			unset($query['<?php echo $admin->primaryKey; ?>']);

			return $query;
		}
		if (empty($results[0])) {
			return false;
		}
		return $results[0];
	}