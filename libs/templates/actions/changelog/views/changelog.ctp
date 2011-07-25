<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?> index">
	<h2><?php printf("<?php printf(__('%s%%s', true), \${$admin->singularName}['{$admin->modelName}']['{$admin->displayField}']); ?>", $configuration['config']['title']); ?></h2>
	<table id="recent-activity" class="table" cellpadding="0" cellspacing="0">
		<thead class="hide">
			<tr>
				<td><?php __('Type'); ?></td>
				<td><?php __('Title'); ?></td>
				<td><?php __('Owner'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php printf("<?php foreach (\${$admin->singularName}['Log'] as \$log) : ?>\n"); ?>
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
foreach ($admin->links as $alias => $config) {
	if ($alias == $action) continue;
	if ($config !== false && is_string($config)) { ?>
		<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', '%s', true), array('action' => '%s')); ?>", $admin->plugin, $config, $alias); ?></li>
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
		echo sprintf("\t\t<li><?php echo \$this->Html->link(__d('%s', '%s', true), %s); ?></li>\n", $admin->plugin, $config['title'], $url.$end);
	}
}
?>
	</ul>
</div>