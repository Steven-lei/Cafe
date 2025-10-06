<?php
// ====== Load RDS credentials ======

		// Get the application environment parameters from the Parameter Store.
		include ('getAppParameters.php');

		// Display the server metadata information if the showServerInfo parameter is true.
		include('serverInfo.php');

  // ====== Connect to RDS ======
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  echo "Connected successfully to RDS!\n";

// ====== Read the SQL dump ======
$sqlUrl = 'https://raw.githubusercontent.com/Steven-lei/Cafe/main/CafeDbDump.sql';
$sql = file_get_contents($sqlFile);

// Split statements by semicolon
$statements = explode(';', $sql);

// ====== Execute each statement ======
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if ($stmt) {
        if (!$conn->query($stmt)) {
            echo "Error executing statement: " . $conn->error . "\n";
        }
    }
}

echo "Database initialized successfully.\n";

// ====== Close connection ======
$conn->close();
?>
