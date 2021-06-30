<?php
   require_once 'login.php';
    session_start();
    
    if(isset($_SESSION['username']))
    {
        if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
        {
            die (different_user());
        } else
        {
            $username = $_SESSION['username'];
            if($_SESSION['picture']){
                $image = $_SESSION['picture'];
                echo "<img src = '$image' ";
            }
            if($_SESSION['poem']){
                $text = $_SESSION['poem'];
                echo "$text";
                
            }
            
            destroy_session_and_data();
           
        }
       
    }
    else
    {
        echo "Please <a href='final_admin.php'>click here</a> to log in.";
    }
    
    echo "</body></html>";
    
    function destroy_session_and_data()
    {
        $_SESSION = array();//delete all info in the array
        setcookie(session_name(),'',time()-2592000,'/');
        session_destroy();
    }
    //THIS FUNCTION IS USED WHEN ERRORS OCCUR
    function mysql_fatal_error()
    {
        echo "Oops";
    }
    function mysql_entities_fix_string($connection, $string){
        return htmlentities(mysql_fix_string($connection,$string));
    }
    
    function mysql_fix_string($connection, $string){
        if(get_magic_quotes_gpc())
            $string = stripslashes($string);
        return $connection->real_escape_string($string);
    }
    function different_user(){
        destroy_session_and_data();
        echo "Please <a href='final_admin.php'>click here</a> to log in.";
    }
?>
