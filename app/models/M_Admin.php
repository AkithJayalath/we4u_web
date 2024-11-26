<?php 
class M_Admin {
  private $db;

  public function __construct() {
      $this->db = new Database();
  }

  public function findUserByEmail($email){ 
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);
      $row = $this->db->single();
      return $this->db->rowCount() > 0;
  }

  public function addUser($data) {
      $this->db->query('INSERT INTO user (username, email, password, role) 
                       VALUES (:username, :email, :password, :role)');
        
      $this->db->bind(':username', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':password', $data['password']);
      $this->db->bind(':role', $data['role']);

      if($this->db->execute()) {
          $userId = $this->db->lastInsertId();
            
          if($data['role'] == 'Careseeker') {
              $this->db->query('INSERT INTO careseeker(careseeker_id) VALUES(:careseeker_id)');
              $this->db->bind(':careseeker_id', $userId);
              return $this->db->execute();
          }
            
          if($data['role'] == 'Moderator') {
              $this->db->query('INSERT INTO moderator(moderator_id) VALUES(:moderator_id)');
              $this->db->bind(':moderator_id', $userId);
              return $this->db->execute();
          }
            
          return true;
      }
      return false;
  }
}
?>