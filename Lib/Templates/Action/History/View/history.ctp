<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> index">
	<h2><?php printf("<?php echo __('%s'); ?>", $configuration['config']['title']); ?></h2>
	<table id="recent-activity" cellpadding="0" cellspacing="0">
		<thead class="hide">
			<tr>
				<th><?php __('Type'); ?></th>
				<th><?php __('Title'); ?></th>
				<th><?php __('Owner'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php printf("<?php foreach (\$logs as \${$admin->singularName}L) : ?>\n"); ?>
				<tr>
				<?php printf("<?php if (\$this->Log->checkIfChanged(date('Y-m-d', strtotime(\${$admin->singularName}L['Log']['created'])))) : ?>\n"); ?>
					<td colspan="3">
						<?php printf("<?php echo \$this->Log->logDate(\${$admin->singularName}L['Log']['created']); ?>\n"); ?>
					</td>
				</tr>
				<tr>
				<?php printf("<?php endif; ?>\n");?>
					<td class="frontpage-type" style="width:200px;padding-left: 70px;">
						<?php printf("<?php echo \$this->Log->logType(\${$admin->singularName}L['Log']); ?>\n"); ?>
					</td>
					<td class="frontpage-title">
						<?php echo sprintf("<?php echo \$this->Html->link(\${$admin->singularName}L['Log']['title'], array(", $admin->modelName, $admin->displayField);
						echo sprintf("'action' => '%s', \${$admin->singularName}L['Log']['model_id'])); ?>\n", $admin->linkTo);
						?>
					</td>
					<td class="frontpage-owner">
						<?php printf("<?php echo \$this->Log->logOwner(\${$admin->singularName}L['Log']['action'], \${$admin->singularName}L['User']); ?>\n"); ?>
					</td>
				</tr>
			<?php printf("<?php endforeach; ?>\n"); ?>
		</tbody>
	</table>
</div>
<div class="actions">
	<h3><?php echo sprintf("<?php echo __d('%s', 'Actions'); ?>", $admin->plugin); ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) :
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) : ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', '%s'), array('action' => '%s')); ?>", $admin->plugin, $config, $alias); ?></li>
<?php
	endif;
endforeach;
?>
	</ul>

<?php if (!empty($configuration['config']['list_filter'])) : ?>
	<h3><?php echo sprintf("<?php echo __d('%s', 'Filter'); ?>", $admin->plugin); ?></h3>
	<?php echo "<?php \$current = array_diff_key(\$this->request->params['named'], Set::normalize(array('direction', 'sort', 'order', 'page'))); ?>\n"; ?>
<?php	foreach ($configuration['config']['list_filter'] as $field => $filter) : ?>
	<h4><?php echo sprintf("<?php echo __d('%s', 'By %s'); ?>", $admin->plugin, Inflector::humanize(preg_replace('/_id$/', '', $field))); ?></h4>
	<ul>
<?php		foreach ($filter as $key => $value) : ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', 'Show %s'), array_merge(\$current, array('%s' => %s))); ?>", $admin->plugin, $value, $field, $key); ?></li>
<?php		endforeach; ?>
	</ul>
<?php	endforeach; ?>
<?php endif; ?>

<?php if (!empty($configuration['config']['search'])) : ?>
	<h3><?php echo sprintf("<?php echo __d('%s', 'Search'); ?>", $admin->plugin); ?></h3>
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