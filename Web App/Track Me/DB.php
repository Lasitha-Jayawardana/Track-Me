<?php

class Database
{

    public $result, $con;

    private
    $dbhost,
    $uname,
    $psw,
    $db , $err;

    public function __construct()
    {
    include("DBInit.php");
    $this->dbhost = $servername;
    $this->uname = $username;
    $this->psw = $password;
    $this->db = $dbname;
    $this->con = mysqli_connect($this->dbhost, $this->uname, $this->psw, $this->db);
        if (!$this->con) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function __destruct()
    {

        mysqli_close($this->con);

    }

    public function geterrno()
    {
        return $this->err;
    }

    public function Query($q)
    {

        $this->result = mysqli_query($this->con, $q);
        $this->err = mysqli_errno($this->con);
        return
        $this->result;
    }
}







?>
