<?php 
// 
$servername = "";
$username = "";
$password = "";
$dbname = "";

$tableName = "";
$filePath = "";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Read CSV file
$csvData = array();
if (($file = fopen($filePath, "r")) !== FALSE) {
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        array_push($csvData, $data);
    }
    fclose($file);
}
//Get all keys from csvData
$csvKeys = $csvData[0];
//Trim first key in csv data array (non visible white spaces)
$csvKeys[0] = preg_replace('/\PL/u', '', $csvKeys[0]);
// Get CSV Values from csv data array
$csvValues = array_slice($csvData, 1);
//Create Key Value Pairs for csv Keys and csv Values
$csvKeyValuePairs = array();
foreach($csvValues as $key=>$value) {
    $csvKeyValuePairs[$key] = array_combine($csvKeys, $value);
}
//Surround csvValues with quotes
foreach($csvKeyValuePairs as $key=>$value) {
    foreach($value as $key2=>$value2) {
        $csvKeyValuePairs[$key][$key2] = '"' . $value2 . '"';
    }
}
//Insert values into table
foreach($csvKeyValuePairs as $key=>$value) {
    $sql = "INSERT INTO ${tableName} (" . implode(',', array_keys($value)) . ") VALUES (";
    $sql .= implode(',', $value) . ")";
    $result = mysqli_query($conn, $sql);
    if(!$result) {
        echo '<div id="error">
        <h3 id="error-title"> Error </h3>
        <p>' . mysqli_error($conn) . '</p>
        </div>';
    }
}
?>