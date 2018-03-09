<?php


namespace App\Models;


use Core\Container\Container;

class Reviews
{
    /**
     * @var \PDO $db
     */
    private $db;

    public function __construct()
    {
        $this->db = Container::getContainer()->get('db');
    }

    public function create($name,$email,$text)
    {

    }

    public function read()
    {
        $sql = "SELECT review_name,review_email,review_text FROM reviews;";
        $res = $this->db->query($sql);
        return $res->fetchAll();
    }
}