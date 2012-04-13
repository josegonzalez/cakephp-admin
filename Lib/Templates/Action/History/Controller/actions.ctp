	public function <?php echo $alias; ?>() {
		$logs = $this-><?php echo $admin->modelName; ?>->related('<?php echo $alias; ?>');
		$this->helpers[] = 'Log.Log';
		$this->set(compact('logs'));
	}