<?php
$links = array();
foreach ($plugins as $pluginName => $cakeAdmins) {
	foreach ($cakeAdmins as $cakeAdmin) {
		$links[] = array(
			'title' => $cakeAdmin['title'],
			'plugin' => $pluginName,
			'controller' => $cakeAdmin['controller'],
			'action' => $cakeAdmin['action'],
			'link' => "\$this->Html->link('{$cakeAdmin['title']}', array('plugin' => '{$pluginName}', 'controller' => '{$cakeAdmin['controller']}', 'action' => '{$cakeAdmin['action']}'), array('rel' => 'external'));"
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
<!DOCTYPE html> 
<html>
<head>
	<?php echo \$this->Html->charset(); ?>
	<title><?php printf(__('%s - Dashboard', true), \$title_for_layout); ?></title>
	<?php
		echo \$this->Html->meta('icon');

		echo \$this->Html->css('/{$plugin}/css/cake.admin.generic', null, array('media' => 'only screen and (min-device-width: 1024px)'));
		echo \$this->Html->script(array(
			'http://code.jquery.com/jquery-1.4.3.min.js',
			'/{$plugin}/js/common',
		));
		echo \$scripts_for_layout;
	?>
</head>
<body>
	<div id=\"container\" data-role=\"page\">
		<div id=\"header\" class=\"clearfix\" data-role=\"header\">
			<h1><?php printf(__('%s Dashboard', true), Inflector::humanize(\$this->params['controller'])); ?></h1>
			<?php echo \$this->Html->link('Actions', '#actions-dialog', array('data-rel' => 'dialog', 'data-transition' => 'pop', 'data-icon' => 'gear', 'class' => 'ui-btn-right')); ?>
			<div class=\"mobile-navbar\" data-role=\"navbar\">
				<ul class=\"navigation\">\n";
				foreach ($links as $i => $link) {
					if ($i === 0) {
						echo "\t\t\t\t<li class=\"first<?php if (\$this->params['plugin'] === '{$link['plugin']}' && \$this->params['controller'] === '{$link['controller']}') echo ' on'?>\">\n\t\t\t\t\t<?php echo {$link['link']} ?>\n\t\t\t\t</li>\n";
					} else {
						echo "\t\t\t\t<li class=\"<?php if (\$this->params['plugin'] === '{$link['plugin']}' && \$this->params['controller'] === '{$link['controller']}') echo 'on'?>\">\n\t\t\t\t\t<?php echo {$link['link']} ?>\n\t\t\t\t</li>\n";
					}
				}
echo "				</ul>
			</div>
		</div>
		<div id=\"content\" data-role=\"content\">

			<?php echo \$this->Session->flash(); ?>

			<?php echo \$content_for_layout; ?>

		</div>
		<div id=\"footer\" data-role=\"footer\">
			<?php echo \$this->Html->link(
					\$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework', true), 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false, 'rel' => 'external', 'class' => 'mobile-hide')
				);
			?>
		</div>
	</div>
	<div data-role=\"page\" id=\"actions-dialog\"> 
		<div data-role=\"header\" data-position=\"inline\">
			<h1>Actions</h1>
		</div>
		<div data-role=\"content\" id=\"actions-dialog-content\">
			
		</div> 
		<div data-role=\"footer\"></div> 
	</div> 
	
	<?php echo \$this->element('sql_dump'); ?>
</body>
</html>
";
?>