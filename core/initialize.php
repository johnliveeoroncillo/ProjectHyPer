<?php
include('model.php');
$tables = $db->getAllTables();
foreach ($tables as $table) {
    $table_name = str_replace(DB_PREFIX, '', $table['TABLE_NAME']);

    $classString = 'class '.pascalCase($table_name).' extends Model {
        function __construct() {
            parent::__construct();
            $this->table = "'.$table_name.'";
            $this->instantiate();
        }
    }';
    eval($classString);
}


