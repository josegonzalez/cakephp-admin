<?php $id = false; ?>
<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> destructive-form form">
	<h2><?php printf("<?php echo __d('%s', '%s %s'); ?>", $admin->plugin, Inflector::humanize($action), $admin->singularHumanName); ?></h2>
<?php
echo "\t<?php echo \$this->Form->create('{$admin->modelName}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$admin->controllerRoute}', 'action' => '{$action}')));?>\n";
	if ($configuration['config']['displayPrimaryKey']) {
		if ($configuration['config']['displayName']) {
			echo sprintf("\t\t<?php echo sprintf(__d('%s', 'Are you sure you want to delete record %%s, %%s?'),\n\t\t\t\$this->Form->value('%s.%s'), \$this->Form->value('%s.%s')); ?>\n", $admin->plugin, $admin->modelName, $admin->primaryKey, $admin->modelName, $admin->displayField);
		} else {
			echo sprintf("\t\t<?php echo sprintf(__d('%s', 'Are you sure you want to delete record %s?'),\n\t\t\t\$this->Form->value('%s.%s')); ?>\n", $admin->plugin, $admin->modelName, $admin->primaryKey);
		}
	} else {
		echo sprintf("\t\t<?php echo __d('%s', 'Are you sure you want to delete this record?'); ?>\n", $admin->plugin);
	}
	echo sprintf("\t\t<?php echo \$this->Form->input('%s.%s', array('type' => 'hidden')); ?>\n", $admin->modelName, $admin->primaryKey);
	echo sprintf("\t<?php echo \$this->Form->end(__d('%s', 'Confirm Deletion'));?>\n", $admin->plugin);
?>
</div>
<div class="actions">
	<h3><?php echo sprintf("<?php echo __d('%s', 'Actions'); ?>", $admin->plugin); ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) {
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) { ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', '%s'), array('action' => '%s')); ?>", $admin->plugin, $config, $alias); ?></li>
<?php
	} elseif (is_array($config)) {
		$url     = array();
		$options = array();
		$confirmMessage = $config['confirmMessage'];
		if (is_array($config['url'])) {
			foreach ($config['url'] as $key => $value) {
				if (!empty($value)) {
					$url[] = sprintf("'%s' => '%s'", $key, $value);
				} else {
					$url[] = sprintf("'%s'", $key);
				}
			}
			$url[] = sprintf("\$this->Form->value('%s.%s')", $admin->modelName, $admin->primaryKey);
			$url = sprintf('array(%s)', implode(', ', $url));
		} else {
			$url = sprintf("'%s'", $config['url']);
		}
		if (is_array($config['options'])) {
			foreach ($config['options'] as $key => $value) {
				if (!empty($value)) {
					$url[] = sprintf("'%s' => '%s'", $key, $value);
				} else {
					$url[] = sprintf("'%s'", $key);
				}
			}
			$options = sprintf('array(%s)', implode(', ', $options));
		} else {
			$options = $config['options'];
		}
		$end = '';
		if (empty($options)) {
			if (!empty($confirmMessage)) {
				$end = sprintf(", null, %s", $confirmMessage);
			}
		} else {
			$end .= sprintf(", '%s'", $options);
			if (!empty($confirmMessage)) {
				$end .= sprintf(", %s", $confirmMessage);
			}
		}
		echo sprintf("\t\t<li><?php echo \$this->Html->link(__d('%s', '%s'), %s); ?></li>\n", $admin->plugin, $config['title'], $url.$end);
	}
}
?>
	</ul>
</div>