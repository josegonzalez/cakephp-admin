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
		foreach ($config['fields'] as $field => $fieldConfig) {
			if (in_array($field, array("{$admin->primaryKey}", "{$modelClass}.{$admin->primaryKey}"))) $id = true;
			$options = array("'div' => array('data-role' =>'fieldcontain')");
			$legend = Inflector::humanize($field);
			$fieldType = false;
			if (strstr($field, '.') === false) {
				$fieldSchema = $modelObj->schema($field);
				$fieldType = $fieldSchema['type'];
			}
			if ($fieldType == 'boolean') {$options[] = "'label' => false";}

			if (!empty($fieldConfig)) {
				if (isset($fieldConfig['legend'])) {
					$legend = $fieldConfig['legend'];
					unset($fieldConfig['legend']);
				}
				foreach ($fieldConfig as $key => $value) {
					$options[] = "'{$key}' => '$value'";
				}
				if (in_array($field, $config['readonly'])) {
					$options[] = "disabled";
				}
			}
			$options = (empty($options)) ? '' : ", array(" . implode(', ', $options). ")";

			if ($fieldType == 'boolean') {
				echo "\t\t<fieldset data-role=\"controlgroup\">\n";
				echo "\t\t\t<legend>{$legend}</legend>\n";
				echo "\t\t\t<?php echo \$this->Form->input('{$field}'{$options}); ?>\n";
				echo "\t\t\t<?php echo \$this->Form->label('{$field}', __('Yes', true)); ?>\n";
				echo "\t\t</fieldset>\n";
			} else {
				echo "\t\t<?php echo \$this->Form->input('{$field}'{$options}); ?>\n";
			}
		}
?>
	</fieldset>
<?php endforeach; ?>
<?php
if ($config['habtm'] === true && !empty($associations['hasAndBelongsToMany'])) {
	echo "\t<fieldset>";
	foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
		echo "\t\t<?php echo \$this->Form->input('{$assocName}', array('data-role' => 'fieldcontain')); ?>\n";
	}
	echo "\t</fieldset";
}
?>
<?php if (!$id) : ?>
	<?php echo "<?php echo \$this->Form->input('{$admin->primaryKey}');?>\n"; ?>
<?php endif; ?>

	<?php echo "<?php echo \$this->Form->submit(__('Submit', true), array('data-theme' => 'a', 'data-inline' => 'true'));?>\n"; ?>
<?php echo "<?php echo \$this->Form->end();?>\n"; ?>
</div>
<div class="actions ui-bar-a" id="actions" data-role="navbar">
	<h4 class="mobile-hide"><?php echo "<?php __('Actions'); ?>"; ?></h4>
	<ul>
<?php
foreach ($admin->links as $alias => $config) {
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) { ?>
		<li class="footer-navbar-li">
			<?php echo "<?php echo \$this->Html->link(__('{$config} {$singularHumanName}', true),\n"; ?>
				<?php echo "array('action' => '{$alias}'),\n"; ?>
				<?php echo "array('class' => 'actions-dialog-a', 'data-role' => 'button', 'data-theme' => 'b', 'rel' => 'external')); ?>\n";?>
		</li>
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
					$options[] = "'{$key}' => '{$value}'";
				} else {
					$options[] = "'{$key}'";
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
		?>
		<li class="footer-navbar-li">
			<?php echo "<?php echo \$this->Html->link(__('{$config['title']}', true),\n"; ?>
				<?php echo "{$url}{$end},\n"; ?>
				<?php echo "array('class' => 'actions-dialog-a', 'data-role' => 'button', 'data-theme' => 'b', 'rel' => 'external')); ?>\n";?>
		</li>
<?php	}
}
?>
	</ul>
</div>