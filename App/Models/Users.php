<?php


namespace App\Models;


use Core\Container\Container;

class Users
{
    /**
     * @var \PDO $db
     */
    private $db;

    public function __construct()
    {
        $this->db = Container::getContainer()->get('db');
    }

    /**
     * @param $login
     * @param $password
     * @param int $role
     * @return bool
     */
    public function addUser($login, $password, $role = 10)
    {
        if(!$this->isUniqueLogin($login)) return false;
        $password = md5($password);
        $sql = "INSERT INTO users (user_name, user_password, user_role) 
                VALUES (:login,:password,:role);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    /**
     * @param $login
     * @return bool
     */
    public function isUniqueLogin($login)
    {
        $sql = "SELECT user_name FROM users";
        $users = $this->db->query($sql);
        foreach ($users as $user){
            if($login === $user['user_name']) return false;
        }
        return true;
    }

    /**
     * @param $login
     * @return bool|mixed
     */
    public function getUser($login)
    {
        $sql = "SELECT user_name,user_password,user_role
                FROM users WHERE user_name = :login";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':login',$login);
        $res = $stmt->execute();
        if(!$res) return false;
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public function userIdentifi($login,$password)
    {
        $sql = "SELECT user_name,user_password,user_role FROM users";
        $users = $this->db->query($sql,\PDO::FETCH_ASSOC);
        foreach ($users as $user){
            if($login === $user['user_name'] && $password === $user['user_password']){
                return $user;
            }
        }
        return false;
    }
}