<?php
class User{
    private $conn;
    private $table = "users";
    public $uid;
    public $fname;
    public $lname;
    public $gender;
    public $bdate; // dd-mm-yyyy
    public $join_date;
    public $country;
    public $spec;
    public $about;
    public $type;
    public $phone;
    public $email;
    public $password;

    function __construct($db){
        $this->conn =$db;
    }

    public function create(){
        // add user
        if($this->checkAvalUser()){
            $q = "INSERT INTO ".$this->table." (user_about, gender_id, user_bdate, user_country,
                                        user_email, user_fname, user_lname, user_phone,
                                        user_pwd, user_spec, user_type) 
                                VALUE (
                                        :about, :gender, :bdate, :country,
                                            :email, :fname, :lname, :phone, 
                                            :pwd, :spec, :typ
                                )";
            $stmt = $this->conn->prepare($q);
            $stmt->execute(array(
                ':about' =>  $this->about,
                ':gender' =>  $this->gender,
                ':bdate' =>  $this->bdate,
                ':country' =>  $this->country,
                ':email' =>  $this->email,
                ':fname' =>  $this->fname,
                ':lname' =>  $this->lname,
                ':phone' =>  $this->phone,
                ':pwd' =>  $this->password,
                ':spec' =>  $this->spec,
                ':typ' =>  $this->type
            ));

            // gets the added user id for further operatoins
            $uid_q = "SELECT user_id FROM `".$this->table."` WHERE user_email = ? ";
            $uid_stmt = $this->conn->prepare($uid_q);
            $uid_stmt->execute(array($this->email));
            $this->uid = $uid_stmt->fetch()[0];
            return true;
        } 
        return false;
    }
    
    public function signinAuth(){
        if(!$this->checkAvalUser()){
            $pwd_q = "SELECT user_pwd, user_id, user_fname, user_lname, user_phone,
                             user_country, user_spec, gender_id, user_bdate,
                             join_date, user_about
                    FROM ".$this->table." WHERE user_email = ?";
            $stmt = $this->conn->prepare($pwd_q);
            $stmt->execute(array($this->email));
            $res = $stmt->fetchAll(PDO::FETCH_NUM)[0];
            $pwd_hash = $res[0];
            // set user props if records exist
            if(password_verify($this->password, $pwd_hash)){
                $this->uid = $res[1];
                $this->fname = $res[2];
                $this->lname = $res[3];
                $this->phone = $res[4];
                $this->country = $res[5];
                $this->spec = $res[6];
                $this->gender = $res[7] === 1 ? "Male" : "Female";
                $this->bdate = $res[8];
                $this->join_date = $res[9];
                $this->about = $res[10];
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }

    // check if user email already exists and updates type for further operations
    public function checkAvalUser(){
        $check_email_q = "SELECT user_type FROM `".$this->table."` WHERE user_email = ?";
        $check = $this->conn->prepare($check_email_q);
        $check->execute(array($this->email));
        if($this->countRows("user_email = '".$this->email."'") === 0){
            return true;
        }
        // if user already exists then get the type
        $this->type = $check->fetch()[0];
        return false;
    }

    public function countRows($where_criteria){
        $count_q = "SELECT COUNT(*) FROM ".$this->table." WHERE ".$where_criteria;
        $count = $this->conn->query($count_q)->fetchColumn();
        return intval($count);
    }
    
    // getters
    public function getUID(){
        return $this->uid;
    }
}