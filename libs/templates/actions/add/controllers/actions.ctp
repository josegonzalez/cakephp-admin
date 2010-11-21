	function <?php echo $alias ?>() {
		if (!empty($this->data)) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->data)) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__('The <?php echo ucfirst(strtolower($singularHumanName)); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => '<?php echo $admin->redirectTo?>'));
<?php else: ?>
				$this->flash(__('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.', true), array('action' => '<?php echo $admin->redirectTo?>'));
<?php endif; ?>
			} else {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__('The <?php echo ucfirst(strtolower($singularHumanName)); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
		$this->set($this-><?php echo $currentModelName; ?>->related('<?php echo $alias; ?>'));
	}