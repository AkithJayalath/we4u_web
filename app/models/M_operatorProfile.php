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

    public function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function updateOperatorProfile($data) {
        $this->db->query('UPDATE user SET 
            username = :username,
            email = :email,
            gender = :gender,
            date_of_birth = :date_of_birth,
            profile_picture = :profile_picture,
            contact_info = :contact_info
            WHERE user_id = :user_id');
    
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':profile_picture', $data['profile_picture_name']);
        $this->db->bind(':contact_info', $data['contact_info']);
        $this->db->bind(':user_id', $data['user_id']);
    
        return $this->db->execute();
    }
}

?>




