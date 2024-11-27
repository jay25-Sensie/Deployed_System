<?php   

    $host = "localhost";
    $username = "u149793347_JE";
    $password = "Jay03252002";
    $database = "u149793347_db_imsclinic";

    $con = new mysqli($host, $username, $password, $database);

    if($con->connect_error){
        echo $con->connect_error;
    }else{
        return $con;
    }

?>