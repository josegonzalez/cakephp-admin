<div class="<?php echo $admin->adminPluralVar; ?> <?php echo $action; ?> form">
<h2><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $admin->singularHumanName); ?></h2>
<?php echo "<?php echo \$this->Form->create('{$admin->adminModelName}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$admin->controllerRoute}', 'action' => '{$action}')));?>\n";?>
<?php foreach ($configuration['config'] as $i => $config): ?>
	<fieldset<?php if (!empty($config['classes'])) echo ' class="' . $config['classes'] . '"'; ?>>
<?php if (!empty($config['title'])) : ?>
 		<legend><?php printf("<?php __('%s'); ?>", $config['title']); ?></legend>
<?php endif; ?>
<?php if (!empty($config['description'])) : ?>
		<p><?php printf("<?php __('%s'); ?>", $config['description']); ?></p><br />
<?php endif; ?>
<?php
		echo "\t\t<?php\n";
		foreach ($config['fields'] as $field => $fieldConfig) {
			$options = array();
			if (!empty($fieldConfig)) {
				foreach ($fieldConfig as $key => $value) {
					$options[] = "'{$key}' => '$value'";
				}
				if (in_array($field, $config['readonly'])) {
					$options[] = "disabled";
				}
			}
			$options = (empty($options)) ? '' : ", array(" . implode(', ', $options). ")";
			echo "\t\t\techo \$this->Form->input('{$field}'{$options});\n";
		}
		echo "\t\t?>\n";
?>
	</fieldset>
<?php endforeach; ?>
<?php
if ($config['habtm'] === true && !empty($admin->associations['hasAndBelongsToMany'])) {
	echo "\t<fieldset>";
	foreach ($admin->associations['hasAndBelongsToMany'] as $assocName => $assocData) {
		echo "\t\t<?php echo \$this->Form->input('{$assocName}'); ?>\n";
	}
	echo "\t</fieldset";
}
?>
<?php
	echo "<?php echo \$this->Form->end(__('Submit', true));?>\n";
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
	}
}
?>
	</ul>
</div>