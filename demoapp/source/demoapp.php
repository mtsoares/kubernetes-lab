<html>
<style>
body {
    font-family: "Courier New";
}

h2 {
    font-family: "Verdana";
}
</style>
</head>
<body>
<h2>Very simple example application.</h2>
It connects to a MySQL database with the following details:<br/>
<br/>
<br/>
<?php
error_reporting(E_ALL);

$database = getenv('DATABASE_NAME');
$username = getenv('DATABASE_USERNAME');
$servername = getenv('DATABASE_SERVER');
$password = getenv('DATABASE_PASSWORD');

if (!$database) $database="testdb";
if (!$username) $username="root";
if (!$servername) $servername="10.0.20.192";
if (!$password) $password="pegasus";

echo "Database: ".$database."<br/>";
echo "Username: ".$username."<br/>";
echo "Server: ".$servername."<br/>";
echo "Password: ".$password."<br/><br/>";
echo "Pod: ".gethostname()."<br/><br/>";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection to the MySQL database failed: " . $conn->connect_error);
}
echo "Connected successfully to the MySQL database<br /><br />";

$result = $conn->query("SELECT * FROM students;");

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "Student: " . $row["name"]."<br/>";
  }
} else {
  echo "0 results";
}
$conn->close();

?>
</body>
</html>
