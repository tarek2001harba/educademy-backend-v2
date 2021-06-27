<?php
class Category{
    private $conn = null;
    private $table = 'category';
    public $category_id;
    public $category_name;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function get(){
        $q = "SELECT ctg_id, ctg_name FROM ".$this->table;
        $res = $this->conn->query($q);
        $categories = $res->fetchAll(PDO::FETCH_NUM);
        return $categories;
    }
}