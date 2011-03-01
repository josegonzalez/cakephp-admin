<?php $id = false; ?>
<div class="<?php echo $admin->adminPluralVar; ?> <?php echo $action; ?> form">
<h2><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $admin->singularHumanName); ?></h2>
<?php
echo "<?php echo \$this->Form->create('{$admin->adminModelName}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$admin->controllerRoute}', 'action' => '{$action}')));?>\n";
	if ($configuration['config']['displayPrimaryKey']) {
		if ($configuration['config']['displayName']) {
			echo "\t<?php echo sprintf(__('Are you sure you want to delete record %s, %s?', true),\n\t\t\$this->Form->value('{$admin->adminModelName}.{$admin->primaryKey}'), \$this->Form->value('{$admin->adminModelName}.{$admin->displayField}')); ?>\n";
		} else {
			echo "\t<?php echo sprintf(__('Are you sure you want to delete record %s?', true),\n\t\t\$this->Form->value('{$admin->adminModelName}.{$admin->primaryKey}')); ?>\n";
		}
	} else {
		echo "\t<?php echo __('Are you sure you want to delete this record?', true); ?>\n";
	}
	echo "\t<?php echo \$this->Form->input('{$admin->adminModelName}.{$admin->primaryKey}', array('type' => 'hidden')); ?>\n";
	echo "<?php echo \$this->Form->end(__('Confirm Deletion', true));?>\n";
?>
</div>
<div class="actions">
	<h3><?php echo "<?php __('Actions'); ?>"; ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) {
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) { ?>
		<li><?php echo "<?php echo \$this->Html->link(__('{$config} {$admin->singularHumanName}', true), array('action' => '{$alias}')); ?>";?></li>
<?php
	} elseif (is_array($config)) {
		$url     = array();
		$options = array();
		$confirmMessage = $config['confirmMessage'];
		if (is_array($config['url'])) {
			foreach ($config['url'] as $key => $value) {
				if (!empty($value)) {
					$url[] = "'{$key}' => '{$value}'";
				} else {
					$url[] = "'{$key}'";
				}
			}
			$url[] = "\$this->Form->value('{$admin->adminModelName}.{$admin->primaryKey}')";
			$url = 'array(' . implode(', ', $url) . ')';
		} else {
			$url = "'{$config['url']}'";
		}
		if (is_array($config['options'])) {
			foreach ($config['options'] as $key => $value) {
				if (!empty($value)) {
					$url[] = "'{$key}' => '{$value}'";
				} else {
					$url[] = "'{$key}'";
				}
			}
			$options = 'array(' . implode(', ', $options) . ')';
		} else {
			$options = $config['options'];
		}
		$end = '';
		if (empty($options)) {
			if (!empty($confirmMessage)) {
				$end = ", null, {$confirmMessage}";
			}
		} else {
			$end .= ", '{$options}'";
			if (!empty($confirmMessage)) {
				$end .= ", {$confirmMessage}";
			}
		}
		echo "\t\t<li><?php echo \$this->Html->link(__('{$config['title']}', true), {$url}{$end}); ?></li>\n";
	}
}
?>
	</ul>
</div>