	function <?php echo $alias ?>() {
		if (!empty($this->data)) {
			$this-><?php echo $admin->adminModelName; ?>->create();
			if ($this-><?php echo $admin->adminModelName; ?>->save($this->data)) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => '<?php echo $admin->redirectTo?>'));
<?php else: ?>
				$this->flash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->adminModelName)); ?> saved.', true), array('action' => '<?php echo $admin->redirectTo?>'));
<?php endif; ?>
			} else {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
<?php if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) : ?>
		$this->set($this-><?php echo $admin->adminModelName; ?>->related('<?php echo $alias; ?>'));
<?php endif; ?>
	}