	function <?php echo $alias; ?>() {
		$<?php echo $singularName . Inflector::pluralize(Inflector::humanize($alias)); ?> = $this-><?php echo $modelClass; ?>->findLog();
		$this->set(compact('<?php echo $singularName . Inflector::pluralize(Inflector::humanize($alias)); ?>'));
	}