	public function <?php echo $alias ?>() {
		if (!$this->request->is('post')) {
			return;
		}

		if (empty($this->request->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'No data was posted'), 'flash/success');
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', 'No data was posted.'), array('action' => '<?php echo $admin->redirectTo?>'));
<?php endif; ?>
			return;
		}

		$this-><?php echo $admin->modelName; ?>->create();
		if ($this-><?php echo $admin->modelName; ?>->save($this->request->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved'), 'flash/success');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo?>'));
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->modelName)); ?> saved.'), array('action' => '<?php echo $admin->redirectTo?>'));
<?php endif; ?>
		} else {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> could not be saved.'), 'flash/error');
<?php endif; ?>
		}
<?php if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) : ?>

		$this->set($this-><?php echo $admin->modelName; ?>->related('<?php echo $alias; ?>'));
<?php endif; ?>
	}