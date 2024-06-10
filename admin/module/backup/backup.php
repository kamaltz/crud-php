<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "grosir";

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set filename with database name and current date
$filename = $dbname . "_backup_" . date("Y-m-d_H-i-s") . ".sql";

// Output to browser
header("Content-type: text/sql");
header("Content-Disposition: attachment; filename=$filename");

// Open output stream
$fp = fopen('php://output', 'w');

// Get all tables in the database
$tables = array();
$result = mysqli_query($conn, "SHOW TABLES");

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

// Loop through each table
foreach ($tables as $table) {
    // Get the CREATE TABLE statement
    $result = mysqli_query($conn, "SHOW CREATE TABLE $table");
    $row = mysqli_fetch_row($result);
    $create_table = $row[1];
    fwrite($fp, $create_table . ";\n\n");

    // Get all data from the table
    $result = mysqli_query($conn, "SELECT * FROM $table");
    while ($row = mysqli_fetch_row($result)) {
        $values = array();
        foreach ($row as $value) {
            $values[] = "'" . mysqli_real_escape_string($conn, $value) . "'";
        }
        fwrite($fp, "INSERT INTO $table VALUES(" . implode(",", $values) . ");\n");
    }
    fwrite($fp, "\n\n");
}

// Close output stream
fclose($fp);

// Close connection
mysqli_close($conn);
exit;

?>
