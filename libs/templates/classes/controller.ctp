<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
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
 * @subpackage    cake.
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "<?php\n";
?>
class <?php echo $controllerName; ?>Controller extends <?php echo Inflector::humanize($admin->plugin); ?>AppController {

    var $name = '<?php echo $controllerName; ?>';
<?php
if (count($admin->helpers)):
    echo "    var \$helpers = array(";
    for ($i = 0, $len = count($admin->helpers); $i < $len; $i++):
        if ($i != $len - 1):
            echo "'" . Inflector::camelize($admin->helpers[$i]) . "', ";
        else:
            echo "'" . Inflector::camelize($admin->helpers[$i]) . "'";
        endif;
    endfor;
    echo ");\n";
endif;

if (count($admin->helpers)):
    echo "    var \$components = array(";
    for ($i = 0, $len = count($admin->helpers); $i < $len; $i++):
        if ($i != $len - 1):
            echo "'" . Inflector::camelize($admin->helpers[$i]) . "', ";
        else:
            echo "'" . Inflector::camelize($admin->helpers[$i]) . "'";
        endif;
    endfor;
    echo ");\n";
endif;

echo "\n{$actions}";
?>
}
