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

		if (!$<?php echo $admin->primaryKey; ?>) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), 'flash/error');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			return $this->flash(__('Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}

		if (empty($this->request->data['<?php echo $admin->modelName; ?>']['<?php echo $admin->primaryKey; ?>'])) {
			$this->request->data = $this-><?php echo $admin->modelName; ?>->find('<?php echo $alias; ?>', compact('<?php echo $admin->primaryKey; ?>'));
		}

		if (empty($this->request->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> unspecified'), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> unspecified'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}

		if ($<?php echo $admin->primaryKey; ?> != $this->request->data['<?php echo $admin->modelName; ?>']['<?php echo $admin->primaryKey; ?>']) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'The posted <?php echo $admin->primaryKey; ?> did not match the url'), 'flash/error');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			return $this->flash(__('The posted <?php echo $admin->primaryKey; ?> did not match the url'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}

		if ($this->request->is('post')) {
			if ($this-><?php echo $admin->modelName; ?>->delete($this->request->data['<?php echo $admin->modelName; ?>']['<?php echo $admin->primaryKey; ?>'])) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> deleted'), 'flash/success');
				return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
				$this->flash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> deleted'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
			}
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> was not deleted'), 'flash/error');
<?php else: ?>
			return $this->flash(__d('<?php echo $admin->plugin; ?>', '<?php echo ucfirst(strtolower($admin->singularHumanName)); ?> was not deleted'), array('action' => '<?php echo $alias; ?>', $<?php echo $admin->primaryKey; ?>));
<?php endif; ?>
			$<?php echo $admin->primaryKey; ?> = $this->request->data['<?php echo $admin->modelName; ?>']['<?php echo $admin->primaryKey; ?>'];
		}
		$this->set(compact('<?php echo $admin->primaryKey; ?>'));
	}