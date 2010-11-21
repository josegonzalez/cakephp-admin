	function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['<?php echo $admin->modelName; ?>.<?php echo $admin->primaryKey?>'] = $query['<?php echo $admin->primaryKey; ?>'];
			unset($query['<?php echo $admin->primaryKey; ?>']);

			return $query;
		}
		return $results;
	}