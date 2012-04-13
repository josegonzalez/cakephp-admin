	public function <?php echo $alias; ?>($<?php echo $admin->primaryKey; ?> = null) {
		if (!$<?php echo $admin->primaryKey; ?>) {
			if (empty($this->request->params['named']['<?php echo $admin->primaryKey; ?>'])) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), 'flash/error');
				return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
				return $this->flash(__('Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
			} else {
				$id = $this->request->params['named']['<?php echo $admin->primaryKey; ?>'];
			}
		}

		if (!$<?php echo $admin->primaryKey; ?> && empty($this->request->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), 'flash/error');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			return $this->flash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}

		if ($this->request->is('patch') || $this->request->is('put') || $this->request->is('patch')) {
			if ($this-><?php echo $admin->modelName; ?>->save($this->request->data)) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved'), 'flash/success');
				return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
				return $this->flash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> has been saved.'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
			} else {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The <?php echo ucfirst(strtolower($admin->singularHumanName)); ?> could not be saved.'), 'flash/error');
<?php endif; ?>
			}
		}

		if (empty($this->request->data)) {
			$this->request->data = $this-><?php echo $admin->modelName; ?>->find('<?php echo $alias; ?>', compact('<?php echo $admin->primaryKey; ?>'));
		}

		if (empty($this->request->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), 'flash/error');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			return $this->flash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
<?php if (!empty($admin->associations['belongsTo']) || !empty($admin->associations['hasAndBelongsToMany'])) : ?>

		$this->set($this-><?php echo $admin->modelName; ?>->related('<?php echo $alias; ?>'));
<?php endif; ?>
	}