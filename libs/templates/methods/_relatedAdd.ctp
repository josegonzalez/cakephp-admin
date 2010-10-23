    function _related<?php echo $find; ?>() {
<?php
// Create a model object

App::import('Model', 'Model', false);
$modelObj =& new Model(array(
    'name' => $admin->modelName,
    'table' => $admin->useTable,
    'ds' => $admin->dbConnection
));
$compacts = array();

foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
    foreach ($modelObj->{$assoc} as $associationName => $relation):
        if (!empty($associationName)):
            $otherModelName = Inflector::camelize(Inflector::singularize($associationName));
            $otherPluralName = Inflector::variable(Inflector::pluralize($associationName));
            echo "        \${$otherPluralName} = \$this->{$otherModelName}->find('list');\n";
            $compact[] = "'{$otherPluralName}'";
        endif;
    endforeach;
endforeach;
if (!empty($compact)):
    echo "        return compact(".join(', ', $compact).");\n";
endif;

?>
    }