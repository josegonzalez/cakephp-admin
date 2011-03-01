<div class="<?php echo $admin->adminPluralVar; ?> <?php echo $action; ?>">
	<h2><?php echo "<?php __('" . Inflector::pluralize(Inflector::humanize($admin->modelName)) . "');?>";?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
<?php foreach ($configuration['config']['fields'] as $field): ?>
<?php	if (in_array($field, $configuration['config']['sort'])) : ?>
		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}');?>";?></th>
<?php	else : ?>
		<th><?php echo Inflector::humanize(preg_replace('/_id$/', '', $field)); ?></th>
<?php	endif; ?>
<?php endforeach;?>
		<th class="actions"><?php echo "<?php __('Actions');?>";?></th>
	</tr>
	<?php
	echo "<?php \$i = 0; foreach (\${$admin->adminPluralVar} as \${$admin->adminSingularVar}) : ?>\n";
	echo "\t<tr<?php if (\$i++ %2 == 0) echo ' class=\"altrow\"';?>>\n";
		foreach ($configuration['config']['fields'] as $field) {
			$isKey = false;
			if (!empty($admin->associations['belongsTo'])) {
				foreach ($admin->associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$admin->adminSingularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$admin->adminSingularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				if ($field == $admin->primaryKey) {
					echo "\t\t<td><?php echo \$this->Html->link(\${$admin->adminSingularVar}['{$admin->adminModelName}']['{$field}'], array(";
					echo "'action' => '{$admin->linkTo}', \${$admin->adminSingularVar}['{$admin->adminModelName}']['{$field}'])); ?></td>\n";
				} else {
					echo "\t\t<td><?php echo \${$admin->adminSingularVar}['{$admin->adminModelName}']['{$field}']; ?>&nbsp;</td>\n";
				}
			}
		}

		echo "\t\t<td class=\"actions\">\n";
		foreach ($admin->links as $alias => $config) {
			if ($alias == $action) continue;
			if (is_array($config)) {
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
					$url[] = "\${$admin->adminSingularVar}['{$admin->adminModelName}']['{$admin->primaryKey}']";
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
				echo "\t\t\t<?php echo \$this->Html->link(__('{$config['title']}', true), {$url}{$end}); ?>\n";
			}
		}
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>
	<p>
	<?php echo "<?php
	echo \$this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>";?>
	</p>

	<div class="paging">
	<?php echo "\t<?php echo \$this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>\n";?>
	 | <?php echo "\t<?php echo \$this->Paginator->numbers();?>\n"?> |
	<?php echo "\t<?php echo \$this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>\n";?>
	</div>
</div>
<div class="actions">
	<h3><?php echo "<?php __('Actions'); ?>"; ?></h3>
	<ul>
<?php
foreach ($admin->links as $alias => $config) :
	if ($config !== false && is_string($config)) : ?>
		<li><?php echo "<?php echo \$this->Html->link(__('{$config} {$admin->singularHumanName}', true), array('action' => '{$alias}')); ?>";?></li>
<?php
	endif;
endforeach;
?>
	</ul>

<?php if (!empty($configuration['config']['list_filter'])) : ?>
	<h3><?php echo "<?php __('Filter'); ?>"; ?></h3>
	<?php echo "<?php \$current = array_diff_key(\$this->params['named'], Set::normalize(array('direction', 'sort', 'order', 'page'))); ?>\n"; ?>
<?php	foreach ($configuration['config']['list_filter'] as $field => $filter) : ?>
	<h4><?php printf("<?php __('By %s'); ?>", Inflector::humanize(preg_replace('/_id$/', '', $field))); ?></h4>
	<ul>
<?php foreach ($filter as $key => $value) : ?>
		<li><?php echo "<?php echo \$this->Html->link(__('Show {$value}', true), array_merge(\$current, array('{$field}' => {$key}))); ?>"; ?></li>
<?php endforeach; ?>
	</ul>
<?php	endforeach; ?>
<?php endif; ?>


<?php if (!empty($configuration['config']['search'])) : ?>
	<h3><?php echo "<?php __('Search'); ?>"; ?></h3>
	<?php echo "<?php echo \$this->Form->create('{$admin->adminModelName}', array('url' => array(
		'plugin' => '{$admin->plugin}', 'controller' => '{$admin->controllerRoute}', 'action' => '{$action}'))); ?>\n"; ?>
	<ul>
<?php	foreach ($configuration['config']['search'] as $field => $config) : ?>
		<li><?php echo "<?php echo \$this->Form->input('{$admin->adminModelName}.{$field}', array('type' => '{$config['type']}')); ?>"; ?></li>
<?php	endforeach; ?>
	</ul>
	<?php echo "<?php echo \$this->Form->submit(); ?>\n"; ?>
	<?php echo "<?php echo \$this->Form->end(); ?>"; ?>
<?php endif; ?>

</div>
