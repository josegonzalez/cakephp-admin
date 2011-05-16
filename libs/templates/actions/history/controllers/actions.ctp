	function <?php echo $alias; ?>() {
		$<?php echo $admin->singularName . Inflector::pluralize(Inflector::humanize($alias)); ?> = $this-><?php echo $admin->modelName; ?>->findLog();
		$this->set(compact('<?php echo $admin->singularName . Inflector::pluralize(Inflector::humanize($alias)); ?>'));
	}