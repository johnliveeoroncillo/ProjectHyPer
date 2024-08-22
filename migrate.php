<?php
session_start();
date_default_timezone_set('Asia/Manila');
include('./config/constant.php');
include('./core/render.php');
include('./core/database.php');
include('./core/utils.php');
include('./core/guard.php');
$db = new Database();
$db->connect();

$migration_table = $db->parse_table("migration");

$response = $db->query("SELECT * FROM information_schema.tables WHERE TABLE_NAME = '{$migration_table}' and TABLE_SCHEMA = '".DB_NAME."' ", true);
if (empty($response)) {
    $db->query("CREATE TABLE `{$migration_table}` (timestamp varchar(50) not null)", true);
}

$migrations = glob('./migrations/*.sql');
if (!empty($migrations)) {
    $mapped = array_map(function ($value) {
        return str_replace('.sql', '', basename($value));
    }, $migrations);

    $migrated = $db->query("SELECT * FROM " . $migration_table, true);
    $mapped_migrated = array_map(function ($value) {
        return $value['timestamp'];
    }, $migrated);
    $diffs = array_diff($mapped, $mapped_migrated);
    
    if (!empty($diffs)) {
        foreach ($diffs as $diff) {
            try {
                $sql = file_get_contents('./migrations/'.$diff.'.sql');

                // PATTERNS
                $create_table_pattern = '/CREATE TABLE `([^`]+)`\s*\((.*?)\);/s';
                $alter_table_pattern = '/ALTER TABLE `([^`]+)`\s*(.*?);/';
                $delete_table_pattern = '/DROP TABLE `([^`]+)`\s*;/';

                $insert_table_pattern = '/INSERT INTO `([^`]+)`\s*\((.*?)\) VALUES \((.*?)\);/';

                $pattern = '';
                $mode = 'create';
                if (strpos($sql, "CREATE TABLE") !== false) {
                    $pattern = $create_table_pattern;
                } else if (strpos($sql, "ALTER TABLE") !== false) {
                    $pattern = $alter_table_pattern;
                    $mode = 'alter';
                } else if (strpos($sql, "DROP TABLE") !== false) {
                    $pattern = $delete_table_pattern;
                    $mode = 'delete';
                } else if (strpos($sql, "INSERT INTO") !== false) {
                    $pattern = $insert_table_pattern;
                    $mode = 'insert';
                }



                preg_match($pattern, $sql, $matches);

                if ($mode === 'create' && count($matches) >= 3) {
                    $tableName = $matches[1];
                
                    // Replace the table name with the prefix
                    $newTableName = $db->parse_table($tableName);
                    $sql = preg_replace("/CREATE TABLE `$tableName`/", "CREATE TABLE `$newTableName`", $sql);         
                } else if ($mode === 'alter' && count($matches) >= 3) {
                    $tableName = $matches[1];
                    // Replace the table name with the prefix
                    $newTableName = $db->parse_table($tableName);
                    $sql = preg_replace("/ALTER TABLE `$tableName`/", "ALTER TABLE `$newTableName`", $sql);
                }  else if ($mode === 'delete' && count($matches) >= 2) {
                    $tableName = $matches[1];

                    // Replace the table name with the prefix
                    $newTableName = $db->parse_table($tableName);
                    $sql = preg_replace("/DROP TABLE `$tableName`/", "DROP TABLE `$newTableName`", $sql);
                } else if ($mode === 'insert' && count($matches) >= 4) {
                    $tableName = $matches[1];

                    // Replace the table name with the prefix
                    $newTableName = $db->parse_table($tableName);
                    $sql = preg_replace("/INSERT INTO `$tableName`/", "INSERT INTO `$newTableName`", $sql);
                }
                $db->insert("migration", array('timestamp' => $diff));
                $db->query($sql, true);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }
    }
    redirect('/');
}

;?>