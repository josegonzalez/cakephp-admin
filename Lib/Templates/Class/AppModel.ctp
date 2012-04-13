<?php
echo "<?php\n";
?>
class <?php echo Inflector::camelize($admin->plugin); ?>AppModel extends AppModel {

	var $actsAs = array('Containable');
	var $recursive = -1;

	function related($type) {
		if (isset($this->relatedMethods[$type]) && $this->relatedMethods[$type] === true) {
			return $this->{'_related' . Inflector::camelize($type)}();
		}

		trigger_error(
			__('(Model::related(%s)) Invalid related find for %s', $type, $this->alias),
			E_USER_WARNING
		);
	}

	function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		$find = '_findCount';
		if (isset($extra['type'])) {
			$extra['operation'] = 'count';
			$find = '_find' . Inflector::camelize($extra['type']);
			$params = $this->$find('before', array_merge($parameters, $extra));
			unset($params['fields']);
			unset($params['limit']);
			return $this->find('count', $params);
		}
		return $this->find('count', array_merge($parameters, $extra));
	}

}