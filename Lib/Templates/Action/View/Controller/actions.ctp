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

		$<?php echo $admin->singularName; ?> = $this-><?php echo $admin->modelName; ?>->find('<?php echo $alias; ?>', compact('<?php echo $admin->primaryKey; ?>'));
		if (!$<?php echo $admin->singularName; ?>) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__d('<?php echo $admin->plugin; ?>', 'Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), 'flash/error');
			return $this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			return $this->flash(__('Invalid <?php echo ucfirst(strtolower($admin->singularHumanName)); ?>'), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		$this->set(compact('<?php echo $admin->singularName ?>'));
	}