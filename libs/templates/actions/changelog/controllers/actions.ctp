	function <?php echo $alias; ?>($<?php echo $admin->primaryKey; ?> = null) {
		$<?php echo $admin->primaryKey; ?> = (!$<?php echo $admin->primaryKey; ?> && !empty($this->params['named']['<?php echo $admin->primaryKey; ?>'])) ? $this->params['named']['<?php echo $admin->primaryKey; ?>'] : $<?php echo $admin->primaryKey; ?>;
		$<?php echo $admin->adminSingularName . Inflector::pluralize(Inflector::humanize($alias)); ?> = $this-><?php echo $admin->adminModelName; ?>->findLog(array('model_id' => $<?php echo $admin->primaryKey; ?>));

		if (!$<?php echo $admin->adminSingularName . Inflector::pluralize(Inflector::humanize($alias)); ?>) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		$this->set(compact('<?php echo $admin->adminSingularName . Inflector::pluralize(Inflector::humanize($alias)); ?>'));
	}