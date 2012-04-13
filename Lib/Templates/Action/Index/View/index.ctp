<div class="<?php echo $admin->pluralVar; ?> <?php echo $action; ?>">
	<h2><?php printf("<?php echo __d('%s', '%s');?>", $admin->plugin, Inflector::pluralize(Inflector::humanize($admin->modelName))) ;?></h2>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
<?php foreach ($configuration['config']['fields'] as $field): ?>
<?php	if (in_array($field, $configuration['config']['sort'])) : ?>
			  <th><?php echo sprintf("<?php echo \$this->Paginator->sort('%s');?>", $field); ?></th>
<?php	else : ?>
  			<th><?php echo Inflector::humanize(preg_replace('/_id$/', '', $field)); ?></th>
<?php	endif; ?>
<?php endforeach;?>
				<th class="actions"><?php echo sprintf("<?php echo __d('%s', 'Actions'); ?>", $admin->plugin); ?></th>
			</tr>
		</thead>
	<?php
	echo "\t<?php \$i = 0; foreach (\${$admin->pluralVar} as \${$admin->singularVar}) : ?>\n";
	echo "\t\t<tr<?php if (\$i++ %2 == 0) echo ' class=\"altrow\"';?>>\n";
		foreach ($configuration['config']['fields'] as $field) {
			$isKey = false;
			if (!empty($admin->associations['belongsTo'])) {
				foreach ($admin->associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo sprintf("\t\t\t<td>\n\t\t\t\t<?php echo \$this->Html->link(\$%s['%s']['%s'], array('controller' => '%s', 'action' => 'view', \$%s['%s']['%s'])); ?>\n\t\t\t</td>\n", $admin->singularVar, $alias, $details['displayField'], $details['controller'], $admin->singularVar, $alias, $details['primaryKey']);
						break;
					}
				}
			}
			if ($isKey !== true) {
				if ($field == $admin->primaryKey || in_array($field, $configuration['config']['link'])) {
					echo sprintf("\t\t\t<td><?php echo \$this->Html->link(\$%s['%s']['%s'], array(", $admin->singularVar, $admin->modelName, $field);
					echo sprintf("'action' => '%s', \$%s['%s']['%s'])); ?></td>\n", $admin->linkTo, $admin->singularVar, $admin->modelName, $admin->primaryKey);
				} else {
					echo sprintf("\t\t\t<td><?php echo \$%s['%s']['%s']; ?>&nbsp;</td>\n", $admin->singularVar, $admin->modelName, $field);
				}
			}
		}

		echo "\t\t\t<td class=\"actions\">\n";
		foreach ($admin->links as $alias => $config) {
			if ($alias == $action) continue;
			if (is_array($config)) {
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
				echo sprintf("\t\t\t\t<?php echo \$this->Html->link(__d('%s', '%s'), %s); ?>\n", $admin->plugin, $config['title'], $url.$end);
			}
		}
		echo "\t\t\t</td>\n";
	echo "\t\t</tr>\n";

	echo "\t\t<?php endforeach; ?>\n";
	?>
	</table>
	<p class="paging-details">
		<?php echo sprintf("<?php
			echo \$this->Paginator->counter(array(
				'format' => __d('%s', 'Page %%page%% of %%pages%%, showing %%current%% records out of %%count%% total, starting on record %%start%%, ending on %%end%%')
			));
		?>\n", $admin->plugin); ?>
	</p>
	<div class="paging">
		<?php echo sprintf("<?php echo \$this->Paginator->prev(__d('%s', '« Previous'), array(), null, array('class'=>'disabled')); ?>\n", $admin->plugin); ?>
		<?php echo "\t<?php echo \$this->Paginator->numbers(array('separator' => false)); ?>\n"?>
	<?php echo sprintf("\t<?php echo \$this->Paginator->next(__d('%s', 'Next »'), array(), null, array('class' => 'disabled')); ?>\n", $admin->plugin); ?>
	</div>
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