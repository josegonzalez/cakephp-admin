<?php echo "<?php\n"; ?>
class <?php echo Inflector::camelize($admin->plugin) ?>AppController extends AppController {
}

App::uses('ErrorHandler', 'Error');
class AppError extends ErrorHandler {
    public function _outputMessage($template) {
        $this->controller->plugin = '<?php echo Inflector::camelize($admin->plugin); ?>';
        return parent::_outputMessage($template);
    }
}