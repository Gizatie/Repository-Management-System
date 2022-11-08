<?php
/*
 * MySQLi database only one connection is allowed at a time
 */
class database {
private $_connection;
private static $_instance;
public static function getInstance(){
    if (!self::$_instance){
        self::$_instance=new self();
    }
    return self::$_instance;
}
public function __construct()
{
    $this->_connection=new mysqli('localhost','root','yayasoles','repository');
    if (mysqli_connect_error()){
        trigger_error("failed to connect to MySQLi".mysqli_connect_error(),E_USER_ERROR);
    }
}
/*
 * prevent the colon magic method from duplicating  this object
 */
    private function __clone(){}
    /*
     * get the MySQLi connection
     */
    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}