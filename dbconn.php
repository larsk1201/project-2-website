<?php
ob_start();
class Database{
    private $dbConf;

    private $dbConn;

    private $host;
    private $dbname;
    private $user;
    private $pass;

    public function __construct(){
        try{
            $this->dbConf = parse_ini_file('config.ini');
            $this->host = $this->dbConf['host'];
            $this->dbname = $this->dbConf['dbname'];
            $this->user = $this->dbConf['username'];
            $this->pass = $this->dbConf['password'];
            $this->dbConn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            }
            catch (PDOException $e){
                echo $e;
        }
    }

    public function checkForUser($username){
        $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if(count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 1){
            return true;
        }
        else{   
            return false;
        }
    }

    public function tryLogin($username, $password){
        $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            if(password_verify($password, $user['password']) == true){
                $this->closeConn();
                return true;
            }
        }
        else{
            $this->closeConn();
            return false;
        }
    }

    public function closeConn(){
        $this->dbConn = null;
    }

}


if($_SERVER['REQUEST_METHOD'] != "POST"){
    die('Invalid request');
}

$db = new Database();
$username;
$password;

if(isset($_POST['username']) && !empty($_POST['username'])){
    $username = $_POST['username'];
}
if(isset($_POST['password']) && !empty($_POST['password'])){
    $password = $_POST['password'];
}

$user = $db->checkForUser($username);
if(!$user){
    header("Location:" . $_SERVER['HTTP_REFERER']. "?msg=Not_Exist");
    exit();
}

$allowUser = $db->tryLogin($username, $password);
if(!$allowUser){
    header("Location:" . $_SERVER['HTTP_REFERER']. "?msg=Incorrect_Creds");
    exit();
}
else{
    session_start();
    $_SESSION['loggedIn'] = true;
    header("Location: ./index.php");
}

?>