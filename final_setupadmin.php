<?php
require_once 'login.php';
$connection = new mysqli($hn, $un, $pw, $db);
if($connection->connect_error)
{
    die(mysql_fatal_error()." 101");
    
}
$query1 = "CREATE TABLE IF NOT EXISTS final(
username VARCHAR(32) NOT NULL UNIQUE,
password CHAR(32) NOT NULL,
salt CHAR(4) NOT NULL,
pepper CHAR(4) NOT NULL)";
$result1 = $connection->query($query1);
if(!$result1)
{
    die(mysql_fatal_error()." 202");
}
$salt = random_bytes(4);
$pepper = random_bytes(4);
    //assuming user name and password of admin is created.
    //This is for testing purpose.
$username = 'johndoe';
$password = 'whyask';
$token = hash('ripemd128',"$salt$password$pepper");
$query2 = "INSERT INTO final (username, password, salt, pepper) VALUES('$username','$password', '$salt', '$pepper')";
$result2 = $connection->query($query2);
if(!$result2)
{
    die(mysql_fatal_error()." 303");
    //die($connection->connect_error);
}
//    $query="CREATE TABLE malware_files(
//      malware_name VARCHAR(32) NOT NULL UNIQUE,
//      sequence_bytes CHAR(20))" ;
//
//     $result = $connection->query($query);
//     if(!$result)
//     {
//         die(mysql_fatal_error()." 404");
//    }

function mysql_fatal_error()
    {
        echo "Oops";
    }
?>
