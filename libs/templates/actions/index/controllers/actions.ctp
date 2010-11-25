	function <?php echo $alias; ?>() {
		$this->paginate = array('<?php echo $alias; ?>') + array(
			'params' => $this->params,
			'named' => $this->data,
		);
		$<?php echo $pluralName ?> = $this->paginate();
		$this->set(compact('<?php echo $pluralName ?>'));
	}