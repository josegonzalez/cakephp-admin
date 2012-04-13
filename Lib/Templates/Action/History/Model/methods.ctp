	public function _related<?php echo Inflector::camelize($find); ?>() {
		return $this->Behaviors->Logable->Log->find('dashboard', array(
			'conditions' => array('Log.model' => '<?php echo $admin->modelName; ?>')
		));
	}