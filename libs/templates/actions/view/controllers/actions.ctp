	function <?php echo $alias; ?>($<?php echo $admin->primaryKey; ?> = null) {
		$<?php echo $admin->primaryKey; ?> = (!$<?php echo $admin->primaryKey; ?> && !empty($this->params['named']['<?php echo $admin->primaryKey; ?>'])) ? $this->params['named']['<?php echo $admin->primaryKey; ?>'] : $<?php echo $admin->primaryKey; ?>;
		$<?php echo $admin->singularName; ?> = $this-><?php echo $admin->modelName; ?>->find('<?php echo $alias; ?>', array('<?php echo $admin->primaryKey; ?>' => $<?php echo $admin->primaryKey; ?>));

		if (!$<?php echo $admin->singularName; ?>) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		$this->set(compact('<?php echo $admin->singularName ?>'));
	}