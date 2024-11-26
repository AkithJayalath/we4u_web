<?php
class M_operatorProfile{
  private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function getOperatorProfile($userId){
        $this->db->query('SELECT * FROM user WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }


    public function getProfileData($userId){
        $this->db->query('SELECT * FROM user WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
}

?>