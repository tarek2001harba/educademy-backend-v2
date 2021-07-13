<?php
class Admin 
{
    private $conn;
    private $table = 'admin';
    private $utop;
    private $admin_pwd;
    public $admin_id;
    private $admin_username;
    public $admin_fname;
    public $admin_lname;
    public $admin_pos;

    public function __construct($db){
        $this->conn = $db;
    }

    public function add($aid)
    {
        $this->setup();
        if($aid === $this->utop){
            $admin_id = $this->enc($this->admin_username);
            $q = "INSERT INTO ".$this->table." 
                (admin_id, admin_fname, admin_lname, 
                admin_username, admin_pwd, admin_pos) VALUE 
                (:aid, :fname, :lname,:uname, :pwd, :pos)";
            $stmt = $this->conn->prepare($q);
            $stmt->execute(array(
                ':aid' => $admin_id,
                ':fname' => $this->admin_fname,
                ':lname' => $this->admin_lname,
                ':uname' => $this->admin_username,
                ':pwd' => $this->admin_pwd,
                ':pos' => $this->admin_pos
            ));
        }
    }

    public function authorize()
    {
        
        $q = "SELECT admin_pwd, admin_fname, admin_lname, admin_id, admin_pos
            FROM ".$this->table.' WHERE admin_username = ?';
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->admin_username));
        $res = $stmt->fetch(PDO::FETCH_NUM);
        
        if(count($res) > 0){
            $hash = $res[0];
            if($this->vail($this->admin_pwd, $hash)){
                $this->admin_fname = $res[1];
                $this->admin_lname = $res[2];
                $this->admin_id = $res[3];
                $this->admin_pos = $res[4];
                return true;
            }
        }
        return false; 
    }

    public function vaildate()
    {
        $q = "SELECT admin_id FROM ".$this->table." WHERE admin_id = ?";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array($this->admin_id));
        return $exec ? true : false; 
    }

    private function setup(){
        $q = "SELECT admin_id FROM ".$this->table." LIMIT 1";
        $res = $this->conn->query($q);
        $a = $res->fetch()[0];
        $this->utop = $a;
    }

    public function setPwd($pwd)
    {
        $this->admin_pwd = $pwd;
    }

    public function setUname($uname)
    {
        $this->admin_username = $uname;
    }

    function enc($plain){
        return password_hash(md5($plain), PASSWORD_DEFAULT);
    }

    function vail($plain, $hash){
        return password_verify(md5($plain), $hash);
    }
}
