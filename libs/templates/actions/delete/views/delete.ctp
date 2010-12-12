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
<?php
echo "<?php echo \$this->Form->create('{$modelClass}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$controllerRoute}', 'action' => '{$action}')));?>\n";
	if ($configuration['config']['displayPrimaryKey']) {
		if ($configuration['config']['displayName']) {
			echo "\t<?php echo sprintf(__('Are you sure you want to delete record %s, %s?', true),\n\t\t\$this->Form->value('{$modelClass}.{$admin->primaryKey}'), \$this->Form->value('{$modelClass}.{$admin->displayField}')); ?>\n";
		} else {
			echo "\t<?php echo sprintf(__('Are you sure you want to delete record %s?', true),\n\t\t\$this->Form->value('{$modelClass}.{$admin->primaryKey}')); ?>\n";
		}
	} else {
		echo "\t<?php echo __('Are you sure you want to delete this record?', true); ?>\n";
	}
	echo "\t<?php echo \$this->Form->input('{$modelClass}.{$admin->primaryKey}', array('type' => 'hidden')); ?>\n";
	echo "<?php echo \$this->Form->submit(__('Confirm Deletion', true), array('data-theme' => 'a', 'data-inline' => 'true'));?>\n";
	echo "<?php echo \$this->Form->end();?>\n";
?>
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