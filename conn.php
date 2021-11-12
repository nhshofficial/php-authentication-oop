<?php

class dbconnect {
    private $con;
    function connect() {
        include_once dirname(__FILE__) . '/config.php';
        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if(mysqli_connect_errno()){
            echo "Failed to connect database" . mysqli_connect_error();
            return null;
        }
        return $this->con;
    }
    // fun close connection
    function Close(){  
        $this->con->close();  
    }  
}