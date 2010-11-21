---
title: "<?php echo $alias; ?> action",
finders: ["<?php echo $alias; ?>"],
related: ["<?php echo $alias; ?>"]
---

	function <?php echo $alias; ?>($<?php echo $admin->primaryKey; ?> = null) {
		if (!$<?php echo $admin->primaryKey; ?> && empty($this->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__('Invalid <?php echo ucfirst(strtolower($singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo ucfirst(strtolower($singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		if (!empty($this->data)) {
			if ($this-><?php echo $currentModelName; ?>->save($this->data)) {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__('The <?php echo ucfirst(strtolower($singularHumanName)); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
				$this->flash(__('The <?php echo ucfirst(strtolower($singularHumanName)); ?> has been saved.', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
			} else {
<?php if ($admin->sessions): ?>
				$this->Session->setFlash(__('The <?php echo ucfirst(strtolower($singularHumanName)); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
		if (empty($this->data)) {
			$this->data = $this-><?php echo $currentModelName; ?>->find('<?php echo $alias; ?>', array('<?php echo $admin->primaryKey; ?>' => $<?php echo $admin->primaryKey; ?>));
		}
		if (empty($this->data)) {
<?php if ($admin->sessions): ?>
			$this->Session->setFlash(__('Invalid <?php echo ucfirst(strtolower($singularHumanName)); ?>', true), 'flash/error');
			$this->redirect(array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo ucfirst(strtolower($singularHumanName)); ?>', true), array('action' => '<?php echo $admin->redirectTo; ?>'));
<?php endif; ?>
		}
		$this->set($this-><?php echo $currentModelName; ?>->related('<?php echo $alias; ?>'));
	}