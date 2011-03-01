	function <?php echo $alias; ?>() {
		$<?php echo $admin->adminSingularName . Inflector::pluralize(Inflector::humanize($alias)); ?> = $this-><?php echo $admin->adminModelName; ?>->findLog();
		$this->set(compact('<?php echo $admin->adminSingularName . Inflector::pluralize(Inflector::humanize($alias)); ?>'));
	}