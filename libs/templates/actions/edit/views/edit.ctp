<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php $id = false; ?>
<div class="<?php echo $pluralVar;?> form">
<h2><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
<?php echo "<?php echo \$this->Form->create('{$modelClass}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$controllerRoute}', 'action' => '{$action}')));?>\n";?>
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
			if (in_array($field, array("{$admin->primaryKey}", "{$modelClass}.{$admin->primaryKey}"))) $id = true;
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
if ($config['habtm'] === true && !empty($associations['hasAndBelongsToMany'])) {
	echo "\t<fieldset>";
	foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
		echo "\t\t<?php echo \$this->Form->input('{$assocName}'); ?>\n";
	}
	echo "\t</fieldset";
}
?>
<?php if (!$id) : ?>
	<?php echo "<?php echo \$this->Form->input('id');?>\n"; ?>
<?php endif; ?>
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
		<li><?php echo "<?php echo \$this->Html->link(__('{$config} {$singularHumanName}', true), array('action' => '{$alias}')); ?>";?></li>
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
			$url[] = "\$this->Form->value('{$modelClass}.{$primaryKey}')";
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
		echo "\t\t<li><?php echo \$this->Html->link(__('{$config['title']}', true), {$url}{$end});?></li>\n";
	}
}
?>
	</ul>
</div>