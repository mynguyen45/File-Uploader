<?php //authentication
    require_once 'login.php';
    //create connection
    $connection1 = new mysqli($hn, $un, $pw, $db);
    //checking connection
    if($connection1->connect_error) die(mysql_fatal_error()." 101");
    //authentication
    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
    {
        $un_temp = mysql_entities_fix_string($connection1, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = mysql_entities_fix_string($connection1, $_SERVER['PHP_AUTH_PW']);
//        CREATE TABLE final(
//        username VARCHAR(32) NOT NULL UNIQUE,
//        password CHAR(32) NOT NULL,
//        salt CHAR(4) NOT NULL,
//        pepper CHAR(4) NOT NULL)
        $query1 = "SELECT * FROM final WHERE username='$un_temp'";
        $result1 = $connection1->query($query1);
        $check = $result1->num_rows;
        if(!$result1)
        {
            die(mysql_fatal_error(). " 202");
        }
        elseif ($result1->num_rows)
        {
            $row = $result1->fetch_array(MYSQLI_NUM);
            $result1->close();
            $salt = $row[2];//for taste
            $pepper = $row[3];//spice it up
            $token = hash('ripemd128',"$salt.$pw_temp.$pepper");
            $to_check = hash('ripemd128',"$salt.$row[1].$pepper");
            echo "$to_check";
            if($token == $to_check)
            {
                session_start();
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                if(!isset($_SESSION['initiated']))
                {
                    session_regenerate_id();
                    $_SESSION['initiated'] = 1;
                }
                if(!isset($_SESSION['count']))
                    $_SESSION['count'] = 0;
                else ++$_SESSION['count'];
                $_SESSION['username'] = $un_temp;
//                $_SESSION['poem']='a';
//                $_SESSION['picture'] ='b';
                echo "Hi! You are now logged in";//users upload file
                echo <<<_END
                <form method='post' action = 'final_admin.php' enctype = 'multipart/form-data'><pre>
                Select a JPEG file:
                <input type='file' name='filename' size ='10'>
                Write something here:
                <input type="text" name="subject" >
                <input type = "submit" value= "Upload">
                </pre></form>
_END;

                    if($_FILES)
                    {
                        $name = $_FILES['filename']['name'];
                        if($_FILES['filename']['type'] == 'image/jpeg'){
                         $ext = 'jpg';
                        }
                        if($ext)
                        {
                            $n = "image.$ext";
                            move_uploaded_file($_FILES['filename']['tmp_name'],$n);
                            echo "Uploaded image successfully. ";
                            $_SESSION['picture'] = $n;
                        }
                    }
                if(isset($_POST['subject']))
                {
                    echo "Uploaded text successfully";
                    $_SESSION['poem'] =  mysql_entities_fix_string($connection1,$_POST['subject']);
                }
                die ("<p><a href=final_user.php>Click here to continue</a></p>");
            }
            else die("Invalid username/password combination");
        }else die("Invalid username/password combination");
    }
     else{
        header('WWW-Authenticate: Basic realm = "Restricted Section"');
        header('HTTP/1.0 401 Unauthorized');
        die("Please enter your username and password");
     }
    function destroy_session_and_data()
    {
        $_SESSION = array();//delete all info in the array
        setcookie(session_name(),'',time()-2592000,'/');
        session_destroy();
    }
    
    function mysql_entities_fix_string($connection, $string){
        return htmlentities(mysql_fix_string($connection,$string));
    }
    
    function mysql_fix_string($connection, $string){
        if(get_magic_quotes_gpc())
            $string = stripslashes($string);
        return $connection->real_escape_string($string);
    }
    
    function get_post($conn,$var)
    {
        return $conn->real_escape_string($_POST[$var]);
    }
    
    function mysql_fatal_error()
    {
        echo "Oops";
    }
    echo "</body></html>";
    
    
?>
