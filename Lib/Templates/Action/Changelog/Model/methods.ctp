	public function _find<?php echo Inflector::camelize($find); ?>($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['<?php echo $admin->modelName; ?>.<?php echo $admin->primaryKey?>'] = $query['<?php echo $admin->primaryKey; ?>'];
			$query['fields'] = array('<?php echo $admin->primaryKey?>', '<?php echo $admin->displayField; ?>');
			$query['limit'] = 1;

			return $query;
		}

		if (empty($results[0])) {
			return false;
		}

		$results[0]['Log'] = $this->Behaviors->Logable->Log->find('dashboard', array(
			'conditions' => array(
				'Log.model' => '<?php echo $admin->modelName; ?>',
				'Log.model_id' => $query['<?php echo $admin->primaryKey; ?>'],
			)
		));
		return $results[0];
	}