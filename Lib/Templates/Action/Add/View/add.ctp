<?php
$formType = '';
if ($configuration['config']['formType']) {
  $formType = ", 'type' => '" . $configuration['config']['formType'] . "'";
}
unset($configuration['config']['formType']);
?>
<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> form">
	<h2><?php echo sprintf("<?php echo __d('%s', '%s %s'); ?>", $admin->plugin, Inflector::humanize($action), $admin->singularHumanName); ?></h2>
	<?php echo sprintf("<?php echo \$this->Form->create('%s', array('url' => array(
		'plugin' => '%s', 'controller' => '%s', 'action' => '%s')%s));?>\n", $admin->modelName, $admin->plugin, $admin->controllerRoute, $action, $formType); ?>
<?php foreach ($configuration['config'] as $i => $config): ?>
		<fieldset<?php if (!empty($config['classes'])) echo sprintf(' class="%s"', $config['classes']); ?>>
<?php if (!empty($config['title'])) : ?>
 			<legend><?php printf("<?php echo __d('%s', '%s'); ?>", $admin->plugin, $config['title']); ?></legend>
<?php endif; ?>
<?php if (!empty($config['description'])) : ?>
			<p><?php printf("<?php echo __d('%s', '%s'); ?>", $admin->plugin, $config['description']); ?></p><br />
<?php endif; ?>
<?php
		echo "\t\t\t<?php\n";
		foreach ($config['fields'] as $field => $fieldConfig) {
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
	echo "\t<fieldset>";
	foreach ($admin->associations['hasAndBelongsToMany'] as $assocName => $assocData) {
		echo sprintf("\t\t<?php echo \$this->Form->input('%s'); ?>\n", $assocName);
	}
	echo "\t</fieldset";
}
?>
<?php echo sprintf("\t<?php echo \$this->Form->end(__d('%s', 'Submit'));?>\n", $admin->plugin); ?>
</div>
<div class="actions">
	<h3><?php echo sprintf("<?php echo __d('%s', 'Actions'); ?>", $admin->plugin); ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) {
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) { ?>
		<li><?php printf("<?php echo \$this->Html->link(__d('%s', '%s'), array('action' => '%s')); ?>", $admin->plugin, $config, $alias); ?></li>
<?php
	}
}
?>
	</ul>
</div>