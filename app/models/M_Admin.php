<?php 
class M_Admin {
  private $db;

  public function __construct() {
      $this->db = new Database();
  }

  public function getAllUsers() {
    $this->db->query('SELECT user_id, username, email, role, profile_picture, created_at 
                      FROM user 
                      ORDER BY created_at DESC');
    return $this->db->resultSet();
  }

  public function findUserByEmail($email){ 
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);
      $row = $this->db->single();
      return $this->db->rowCount() > 0;
  }

  public function getAnnouncements() {
    $this->db->query('SELECT * FROM announcement ORDER BY updated_at DESC');
    return $this->db->resultSet();
 }

 public function getAnnouncementById($id) {
    $this->db->query('SELECT * FROM announcement WHERE announcement_id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
}

public function updateAnnouncement($data) {
    $this->db->query('UPDATE announcement SET 
        title = :title, 
        content = :content, 
        status = :status,
        updated_at = CURRENT_DATE()
        WHERE announcement_id = :id');

    $this->db->bind(':title', $data['title']);
    $this->db->bind(':content', $data['content']);
    $this->db->bind(':status', $data['status']);
    $this->db->bind(':id', $data['announcement_id']);

    return $this->db->execute();
}

public function deleteAnnouncement($id) {
    $this->db->query('DELETE FROM announcement WHERE announcement_id = :id');
    $this->db->bind(':id', $id);
    return $this->db->execute();
}

public function addAnnouncement($data) {
    $this->db->query('INSERT INTO announcement (admin_id, title, content, status, publish_date, updated_at) 
                      VALUES (:admin_id, :title, :content, :status, CURRENT_DATE(), CURRENT_DATE())');
    
    $this->db->bind(':admin_id', $_SESSION['user_id']);
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':content', $data['content']);
    $this->db->bind(':status', $data['status']);

    return $this->db->execute();
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







