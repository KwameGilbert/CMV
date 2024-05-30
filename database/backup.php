<?php
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password
$database = 'voting_db'; // Database name

// Path to store the backup file
$backupFile = './' . $database . '_backup_' . date('Y-m-d_H-i-s') . '.sql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Open the output file for writing
    $output = fopen($backupFile, 'w');
    if (!$output) {
        throw new Exception("Could not open the file for writing.");
    }

    // Write statements to create the database if it doesn't exist
    fwrite($output, "CREATE DATABASE IF NOT EXISTS `$database`;\n");
    fwrite($output, "USE `$database`;\n\n");

    // Get all tables in the database
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    // Array to store foreign key constraints
    $foreignKeys = [];

    foreach ($tables as $table) {
        // Write statement to drop the table if it exists
        fwrite($output, "DROP TABLE IF EXISTS `$table`;\n");

        // Get CREATE TABLE statement
        $createTableStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $createTableSql = $createTableStmt['Create Table'];

        // Extract foreign key constraints and remove them from the CREATE TABLE statement
        if (preg_match_all('/,\s*CONSTRAINT `[^`]+` FOREIGN KEY \(`[^`]+`\) REFERENCES `[^`]+` \(`[^`]+`\)(?=,|$)/', $createTableSql, $matches)) {
            foreach ($matches[0] as $match) {
                $foreignKeys[$table][] = $match;
                $createTableSql = str_replace($match, '', $createTableSql);
            }
        }

        // Write the modified CREATE TABLE statement
        fwrite($output, $createTableSql . ";\n\n");

        // Get INSERT statements for all rows in the table
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $columns = array_keys($row);
            $values = array_map(array($pdo, 'quote'), array_values($row));
            $insertStmt = sprintf(
                'INSERT INTO `%s` (%s) VALUES (%s);',
                $table,
                implode(', ', $columns),
                implode(', ', $values)
            );
            fwrite($output, $insertStmt . "\n");
        }
        fwrite($output, "\n\n");
    }

    // Write foreign key constraints after data insertion
    foreach ($foreignKeys as $table => $constraints) {
        foreach ($constraints as $constraint) {
            fwrite($output, "ALTER TABLE `$table` ADD $constraint;\n");
        }
        fwrite($output, "\n");
    }

    fclose($output);
    echo "Backup created successfully at $backupFile";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the database connection
    $pdo = null;
}
?>
