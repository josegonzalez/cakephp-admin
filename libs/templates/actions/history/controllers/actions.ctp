	function <?php echo $alias; ?>() {
		$logs = $this-><?php echo $admin->modelName; ?>->Log->find('dashboard', array(
			'conditions' => array('Log.model' => '<?php echo $admin->modelName; ?>')
		));
		$this->helpers[] = 'Log.Log';
		$this->set(compact('logs'));
	}