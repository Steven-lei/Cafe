<?php
// Disable output buffering
while (ob_get_level() > 0) ob_end_flush();
ob_implicit_flush(true);

// Optional: for long scripts
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
// ====== Load RDS credentials ======
include('getAppParameters.php');
include('serverInfo.php');

// ====== Connect to RDS ======
echo "Connecting to RDS...\n";
echo "DB URL: $db_url<br>\n";
echo "DB User: $db_user<br>\n";
// echo "DB Password: $db_password\n"; // Uncomment only for debugging
echo "DB Name: $db_name<br>\n";

$conn = new mysqli($db_url, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to RDS!<br>\n";

// ====== Read the SQL dump from GitHub ======
$sqlUrl = 'https://raw.githubusercontent.com/Steven-lei/Cafe/main/CafeDbDump.sql';
echo "Downloading SQL file from GitHub...<br>\n";
flush(); // flush output buffer

$sql = file_get_contents($sqlUrl);
if ($sql === false) {
    die("Failed to download SQL file from GitHub.\n");
}
echo "SQL file downloaded successfully.<br>\n";
flush();

// ====== Split statements by semicolon ======
$statements = explode(';', $sql);

// ====== Execute each statement ======
$total = count($statements);
echo "Executing $total SQL statements...<br>\n";
flush();

$counter = 0;
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if ($stmt) {
        if (!$conn->query($stmt)) {
            echo "Error executing statement: " . $conn->error . "<br>\n";
        } else {
            $counter++;
            echo "Executed statement $counter/$total<br>\n";
            flush(); // send output to browser/console immediately
        }
    }
}

echo "Database initialized successfully.\n";

// ====== Close connection ======
$conn->close();
?>
