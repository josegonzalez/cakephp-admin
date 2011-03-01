	function <?php echo $alias; ?>($<?php echo $admin->primaryKey; ?> = null) {
		if (!$<?php echo $admin->primaryKey; ?> && empty($this->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		if (!empty($this->data)) {
			if ($this-><?php echo $admin->adminModelName; ?>->save($this->data)) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
				$this->flash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved.', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
			} else {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
		if (empty($this->data)) {
			$this->data = $this-><?php echo $admin->adminModelName; ?>->find('<?php echo $alias; ?>', array('<?php echo $admin->primaryKey; ?>' => $<?php echo $admin->primaryKey; ?>));
		}
		if (empty($this->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
<?php if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) : ?>
		$this->set($this-><?php echo $admin->adminModelName; ?>->related('<?php echo $alias; ?>'));
<?php endif; ?>
	}