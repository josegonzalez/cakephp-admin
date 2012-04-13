<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> index">
	<h2><?php printf("<?php echo __('%s%%s', \${$admin->singularName}['{$admin->modelName}']['{$admin->displayField}']); ?>", $configuration['config']['title']); ?></h2>
	<table id="recent-activity" cellpadding="0" cellspacing="0">
		<thead class="hide">
			<tr>
				<th><?php __('Type'); ?></th>
				<th><?php __('Title'); ?></th>
				<th><?php __('Owner'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php printf("<?php foreach (\${$admin->singularName}['Log'] as \${$admin->singularName}Log) : ?>\n"); ?>
				<tr>
				<?php printf("<?php if (\$this->Log->checkIfChanged(date('Y-m-d', strtotime(\${$admin->singularName}Log['Log']['created'])))) : ?>\n"); ?>
					<td colspan="3">
						<?php printf("<?php echo \$this->Log->logDate(\${$admin->singularName}Log['Log']['created']); ?>\n"); ?>
					</td>
				</tr>
				<tr>
				<?php printf("<?php endif; ?>\n");?>
					<td class="frontpage-type" style="width:200px;padding-left: 70px;">
						<?php printf("<?php echo \$this->Log->logType(\${$admin->singularName}Log['Log']); ?>\n"); ?>
					</td>
					<td class="frontpage-title">
						<?php echo sprintf("<?php echo \$this->Html->link(\${$admin->singularName}Log['Log']['title'], array(", $admin->modelName, $admin->displayField);
						echo sprintf("'action' => '%s', \${$admin->singularName}Log['Log']['model_id'])); ?>\n", $admin->linkTo);
						?>
					</td>
					<td class="frontpage-owner">
						<?php printf("<?php echo \$this->Log->logOwner(\${$admin->singularName}Log['Log']['action'], \${$admin->singularName}Log['User']); ?>\n"); ?>
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
			$url[] = sprintf("\$%s['%s']['%s']", $admin->singularVar, $admin->modelName, $admin->primaryKey);
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