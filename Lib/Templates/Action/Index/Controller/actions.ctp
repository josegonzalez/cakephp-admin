	public function <?php echo $alias; ?>() {
<?php
	$mappings = array();
	if (!empty($admin->actions[$alias]['config']['search'])) {
		foreach ($admin->actions[$alias]['config']['search'] as $field => $config) {
			$mappings[] = $field;
		}
	}

	$mappings = (!empty($mappings)) ? "'" . implode("', '", $mappings) . "'" : '';
?>
		$<?php echo $admin->pluralName; ?> = $this->_customPaginate('<?php echo $alias; ?>', <?php echo "array({$mappings})"; ?>);
		$this->set(compact('<?php echo $admin->pluralName; ?>'));
	}