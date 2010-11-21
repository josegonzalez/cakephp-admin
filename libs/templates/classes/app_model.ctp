<?php
echo "<?php\n";
?>
class <?php echo Inflector::humanize($admin->plugin); ?>AppModel extends AppModel {

	function related($type) {
		if (isset($this->_relatedMethods[$type]) && $this->_relatedMethods[$type] === true) {
			return $this->{'_related' . Inflector::camelize($type)}();
		}

		trigger_error(
			sprintf(__('(Model::related(%s)) Invalid related find for %s', true), $type, $this->alias),
			E_USER_WARNING
		);
	}

}