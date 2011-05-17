<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> index">
	<h2><?php printf("<?php __('%s'); ?>", $configuration['config']['title']); ?></h2>
	<table id="recent-activity" class="table" cellpadding="0" cellspacing="0">
		<thead class="hide">
			<tr>
				<td><?php __('Type'); ?></td>
				<td><?php __('Title'); ?></td>
				<td><?php __('Owner'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php printf("<?php foreach (\$logs as \$log) : ?>\n"); ?>
				<tr>
				<?php printf("<?php if (\$this->Log->checkIfChanged(date('Y-m-d', strtotime(\$log['Log']['created'])))) : ?>\n"); ?>
					<td colspan="3">
						<?php printf("<?php echo \$this->Log->logDate(\$log['Log']['created']); ?>\n"); ?>
					</td>
				</tr>
				<tr>
				<?php printf("<?php endif; ?>\n");?>
					<td class="frontpage-type">
						<?php printf("<?php echo \$this->Log->logType(\$log['Log']); ?>\n"); ?>
					</td>
					<td class="frontpage-title">
						<?php echo sprintf("<?php echo \$this->Html->link(\$log['Log']['title'], array(", $admin->modelName, $admin->displayField);
						echo sprintf("'action' => '%s', \$log['Log']['model_id'])); ?>\n", $admin->linkTo);
						?>
					</td>
					<td class="frontpage-owner">
						<?php printf("<?php echo \$this->Log->logOwner(\$log['Log']['action'], \$log['User']); ?>\n"); ?>
					</td>
				</tr>
			<?php printf("<?php endforeach; ?>\n"); ?>
		</tbody>
	</table>
</div>
<div class="actions">
	<h3><?php echo sprintf("<?php __d('%s', 'Actions'); ?>", $admin->plugin); ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) :
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) : ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', '%s', true), array('action' => '%s')); ?>", $admin->plugin, $config, $alias); ?></li>
<?php
	endif;
endforeach;
?>
	</ul>

<?php if (!empty($configuration['config']['list_filter'])) : ?>
	<h3><?php echo sprintf("<?php __d('%s', 'Filter'); ?>", $admin->plugin); ?></h3>
	<?php echo "<?php \$current = array_diff_key(\$this->params['named'], Set::normalize(array('direction', 'sort', 'order', 'page'))); ?>\n"; ?>
<?php	foreach ($configuration['config']['list_filter'] as $field => $filter) : ?>
	<h4><?php echo sprintf("<?php __d('%s', 'By %s'); ?>", $admin->plugin, Inflector::humanize(preg_replace('/_id$/', '', $field))); ?></h4>
	<ul>
<?php		foreach ($filter as $key => $value) : ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', 'Show %s', true), array_merge(\$current, array('%s' => %s))); ?>", $admin->plugin, $value, $field, $key); ?></li>
<?php		endforeach; ?>
	</ul>
<?php	endforeach; ?>
<?php endif; ?>

<?php if (!empty($configuration['config']['search'])) : ?>
	<h3><?php echo sprintf("<?php __d('%s', 'Search'); ?>", $admin->plugin); ?></h3>
	<?php echo sprintf("<?php echo \$this->Form->create('%s', array('url' => array(
		'plugin' => '%s', 'controller' => '%s', 'action' => '%s'))); ?>\n", $admin->modelName, $admin->plugin, $admin->controllerRoute, $action); ?>
	<ul>
<?php	foreach ($configuration['config']['search'] as $field => $config) : ?>
		<li><?php echo sprintf("<?php echo \$this->Form->input('%s.%s', array('label' => '%s', 'type' => '%s')); ?>", $admin->modelName, $field, $config['label'], $config['type']); ?></li>
<?php	endforeach; ?>
	</ul>
	<?php echo "<?php echo \$this->Form->submit(); ?>\n"; ?>
	<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
<?php endif; ?>
</div>