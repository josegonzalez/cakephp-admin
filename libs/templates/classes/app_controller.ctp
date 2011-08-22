<?php echo "<?php\n"; ?>
class <?php echo Inflector::humanize($admin->plugin); ?>AppController extends AppController {
}

if (!class_exists('ErrorHandler')) {
	  App::import('Core', 'Error');
}

class AppError extends ErrorHandler {
    function _outputMessage($template) {
        $this->controller->plugin = '<?php echo $admin->plugin; ?>';
        return parent::_outputMessage($template);
    }
}