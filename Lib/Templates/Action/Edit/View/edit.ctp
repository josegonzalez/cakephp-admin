<?php
$id = false;
$formType = '';
if ($configuration['config']['formType']) {
  $formType = ", 'type' => '" . $configuration['config']['formType'] . "'";
}
unset($configuration['config']['formType']);
?>
<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> form">
	<h2><?php printf("<?php echo __d('%s', '%s %s'); ?>", $admin->plugin, Inflector::humanize($action), $admin->singularHumanName); ?></h2>
	<?php echo "<?php echo \$this->Form->create('{$admin->modelName}', array('url' => array(
		'plugin' => '{$admin->plugin}', 'controller' => '{$admin->controllerRoute}', 'action' => '{$action}'){$formType}));?>\n";?>
<?php foreach ($configuration['config'] as $i => $config): ?>
		<fieldset<?php if (!empty($config['classes'])) echo ' class="' . $config['classes'] . '"'; ?>>
<?php if (!empty($config['title'])) : ?>
 			<legend><?php printf("<?php echo __d('%s', '%s'); ?>", $admin->plugin, $config['title']); ?></legend>
<?php endif; ?>
<?php if (!empty($config['description'])) : ?>
			<p><?php printf("<?php echo __d('%s', '%s'); ?>", $admin->plugin, $config['description']); ?></p><br />
<?php endif; ?>
<?php
		echo "\t\t\t<?php\n";
		foreach ($config['fields'] as $field => $fieldConfig) {
			if (in_array($field, array("{$admin->primaryKey}", "{$admin->modelName}.{$admin->primaryKey}"))) $id = true;
			$options = array();
			if (!empty($fieldConfig)) {
				foreach ($fieldConfig as $key => $value) {
					if (in_array($key, array('readonly', 'wrapper'))) continue;
					$option = sprintf("'%s' => '%s'", $key, $value);
					if ($key === 'label') {
					    $option = sprintf("'%s' => __d('%s', '%s')", $key, $admin->plugin, $value);
					}
					$options[] = $option;
				}
				if (in_array($field, $config['readonly'])) {
					$options[] = "'disabled'";
				}
			}
			$options = (empty($options)) ? '' : sprintf(", array(\n\t\t\t\t\t%s\n\t\t\t\t)", implode(",\n\t\t\t\t\t ", $options));
			echo sprintf($fieldConfig['wrapper'], sprintf("\t\t\t\techo \$this->Form->input('%s'%s);\n", $field, $options));
		}
		echo "\t\t\t?>\n";
?>
		</fieldset>
<?php endforeach; ?>
<?php
if ($config['habtm'] === true && !empty($admin->associations['hasAndBelongsToMany'])) {
	echo "\t\t<fieldset>";
	foreach ($admin->associations['hasAndBelongsToMany'] as $assocName => $assocData) {
		echo sprintf("\t\t\t<?php echo \$this->Form->input('%s'); ?>\n", $assocName);
	}
	echo "\t\t</fieldset";
}
?>
<?php if (!$id) : ?>
		<?php echo sprintf("<?php echo \$this->Form->input('%s');?>\n", $admin->primaryKey); ?>
<?php endif; ?>
	<?php echo sprintf("<?php echo \$this->Form->end(__d('%s', 'Submit'));?>\n", $admin->plugin); ?>
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