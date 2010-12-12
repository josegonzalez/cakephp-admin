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
<div class="<?php echo $pluralVar;?> form">
<h2 class="mobile-hide"><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
<?php echo "<?php echo \$this->Form->create('{$modelClass}', array('url' => array(
	'plugin' => '{$admin->plugin}', 'controller' => '{$controllerRoute}', 'action' => '{$action}')));?>\n";?>
	<h2 class="desktop-hide"><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
<?php foreach ($configuration['config'] as $i => $config): ?>
	<fieldset<?php if (!empty($config['classes'])) echo ' class="' . $config['classes'] . '"'; ?>>
<?php if (!empty($config['title'])) : ?>
 		<legend><h4><?php printf("<?php __('%s'); ?>", $config['title']); ?></h4></legend>
<?php endif; ?>
<?php if (!empty($config['description'])) : ?>
		<h5><?php printf("<?php __('%s'); ?>", $config['description']); ?></h5><br />
<?php endif; ?>
<?php
		foreach ($config['fields'] as $field => $fieldConfig) {
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
	}
}
?>
	</ul>
</div>