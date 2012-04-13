<div class="<?php echo $admin->pluralVar;?> <?php echo $action; ?>">
<h2><?php printf("<?php echo __d('%s', '%s %s'); ?>", $admin->plugin, Inflector::humanize($action), $admin->singularHumanName); ?></h2>
	<dl><?php echo "<?php \$i = 0; \$class = ' class=\"altrow\"';?>\n";?>
<?php
foreach ($configuration['config']['fields'] as $field => $fieldConfig) {
	$isKey = false;
	if ($fieldConfig['link'] && !empty($admin->associations['belongsTo'])) {
		foreach ($admin->associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				echo sprintf("\t\t<dt<?php if (\$i %% 2 == 0) echo \$class;?>><?php echo __d('%s', '%s'); ?></dt>\n", $admin->plugin, $fieldConfig['label']);
				echo sprintf("\t\t<dd<?php if (\$i++ %% 2 == 0) echo \$class;?>>\n\t\t\t<?php echo \$this->Html->link(\$%s['%s']['%s'], array('controller' => '%s', 'action' => 'view', \$%s['%s']['%s'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n", $admin->singularVar, $alias, $details['displayField'], $details['controller'], $admin->singularVar, $alias, $details['primaryKey']);
				break;
			}
		}
	}
	if ($isKey !== true) {
		echo sprintf("\t\t<dt<?php if (\$i %% 2 == 0) echo \$class;?>><?php echo __d('%s', '%s'); ?></dt>\n", $admin->plugin, $fieldConfig['label']);
		echo sprintf("\t\t<dd<?php if (\$i++ %% 2 == 0) echo \$class;?>>\n\t\t\t<?php echo \$%s['%s']['%s']; ?>\n\t\t\t&nbsp;\n\t\t</dd>\n", $admin->singularVar, $admin->modelName, $field);
	}
}
?>
	</dl>
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
<?php
if (!empty($admin->associations['hasOne'])) :
	foreach ($admin->associations['hasOne'] as $alias => $details): ?>
	<div class="related">
		<h3><?php echo sprintf("<?php echo __d('%s', 'Related %s'); ?>", $admin->plugin, Inflector::humanize($details['controller'])); ?></h3>
	<?php echo sprintf("<?php if (!empty(\$%s['%s'])) : ?>\n"), $admin->singularVar, $alias;?>
		<dl><?php echo "\t<?php \$i = 0; \$class = ' class=\"altrow\"';?>\n";?>
	<?php
			foreach ($details['fields'] as $field) {
				echo sprintf("\t\t<dt<?php if (\$i %% 2 == 0) echo \$class;?>><?php echo __d('%s','%s'); ?></dt>\n", $admin->plugin, Inflector::humanize($field));
				echo sprintf("\t\t<dd<?php if (\$i++ %% 2 == 0) echo \$class;?>>\n\t<?php echo \$%s['%s']['%s']; ?>\n&nbsp;</dd>\n", $admin->singularVar, $alias, $field);
			}
	?>
		</dl>
	<?php echo "<?php endif; ?>\n"; ?>
		<div class="actions">
			<ul>
				<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', 'Edit %s'), array('controller' => '%s', 'action' => 'edit', \$%s['%s']['%s'])); ?></li>\n", $admin->plugin, Inflector::humanize(Inflector::underscore($alias)), $details['controller'], $admin->singularVar, $alias, $details['primaryKey']); ?>
			</ul>
		</div>
	</div>
	<?php
	endforeach;
endif;
if (empty($admin->associations['hasMany'])) {
	$admin->associations['hasMany'] = array();
}
if (empty($admin->associations['hasAndBelongsToMany'])) {
	$admin->associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($admin->associations['hasMany'], $admin->associations['hasAndBelongsToMany']);
$i = 0;
foreach ($relations as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
	?>
<div class="related">
	<h3><?php echo sprintf("<?php echo __d('%s', 'Related %s');?>", $admin->plugin, $otherPluralHumanName); ?></h3>
	<?php echo sprintf("<?php if (!empty(\$%s['%s'])):?>\n", $admin->singularVar, $alias); ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
<?php
			foreach ($details['fields'] as $field) {
				echo sprintf("\t\t<th><?php echo __d('%s', '%s'); ?></th>\n", $admin->plugin, Inflector::humanize($field));
			}
?>
		<th class="actions"><?php echo sprintf("<?php echo __d('%s', 'Actions'); ?>", $admin->plugin); ?></th>
	</tr>
<?php
echo "\t<?php
		\$i = 0;
		foreach (\${$admin->singularVar}['{$alias}'] as \${$otherSingularVar}):
			\$class = null;
			if (\$i++ % 2 == 0) {
				\$class = ' class=\"altrow\"';
			}
		?>\n";
		echo "\t\t<tr<?php echo \$class;?>>\n";

				foreach ($details['fields'] as $field) {
					echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
				}

				echo "\t\t\t<td class=\"actions\">\n";
				echo "\t\t\t\t<?php echo \$this->Html->link(__d('$admin->plugin', 'View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
				echo "\t\t\t\t<?php echo \$this->Html->link(__d('$admin->plugin', 'Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
				echo "\t\t\t\t<?php echo \$this->Html->link(__d('$admin->plugin', 'Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), null, sprintf(__d('$admin->plugin', 'Are you sure you want to delete # %s?'), \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
				echo "\t\t\t</td>\n";
			echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
	</table>
<?php echo "<?php endif; ?>\n\n";?>
	<div class="actions">
		<ul>
			<li><?php echo sprintf("<?php echo \$this->Html->link(__d('%s', 'New %s'), array('controller' => '%s', 'action' => 'add')); ?>", $admin->plugin, Inflector::humanize(Inflector::underscore($alias)), $details['controller']); ?> </li>
		</ul>
	</div>
</div>
<?php endforeach;?>