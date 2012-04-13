<?php
$links = array();
foreach ($plugins as $pluginName => $cakeAdmins) {
	foreach ($cakeAdmins as $cakeAdmin) {
		$links[] = array(
			'title' => $cakeAdmin['title'],
			'plugin' => $pluginName,
			'controller' => $cakeAdmin['controller'],
			'action' => $cakeAdmin['action'],
			'link' => "\$this->Html->link('{$cakeAdmin['title']}', array('plugin' => '{$pluginName}', 'controller' => '{$cakeAdmin['controller']}', 'action' => '{$cakeAdmin['action']}'));"
		);
	}
}
echo"
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
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
	<?php echo \$this->Html->charset(); ?>
	<title><?php echo __('%s - Dashboard', \$title_for_layout); ?></title>
	<?php
		echo \$this->Html->meta('icon');

		echo \$this->Html->css('/{$plugin}/css/cake.admin.generic.min');

		echo \$scripts_for_layout;
	?>
</head>
<body class=\"<?php if (\$this->name == 'CakeError') echo 'error-page'; ?>\">
	<div id=\"container\">
		<div id=\"header\">
			<h1><?php echo __('%s Dashboard', Inflector::humanize(\$this->request->params['controller'])); ?></h1>
			<ul class=\"navigation\">\n";
				foreach ($links as $i => $link) {
					if ($i === 0) {
						echo "\t\t\t<li class=\"first<?php if (\$this->request->params['plugin'] === '{$link['plugin']}' && \$this->request->params['controller'] === '{$link['controller']}') echo ' on'?>\">\n\t\t\t\t<?php echo {$link['link']} ?>\n\t\t\t</li>\n";
					} else {
						echo "\t\t\t<li class=\"<?php if (\$this->request->params['plugin'] === '{$link['plugin']}' && \$this->request->params['controller'] === '{$link['controller']}') echo 'on'?>\">\n\t\t\t\t<?php echo {$link['link']} ?>\n\t\t\t</li>\n";
					}
				}
echo "			</ul>
			<div class=\"clear:both\"></div>
		</div>
		<div id=\"content\">

			<?php echo \$this->Session->flash(); ?>

			<?php echo \$content_for_layout; ?>

		</div>
		<div id=\"footer\">
			<?php echo \$this->Html->link(
					\$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework'), 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
</body>
</html>
";
?>