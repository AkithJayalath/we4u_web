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
   // Get all blogs (admin can see all)
   public function getAllBlogs() {
    $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC");
    return $this->db->resultSet();
  }

  // Add a new blog
  public function addBlog($data) {
    $this->db->query("INSERT INTO blogs (user_id, title, content, image_path) 
                      VALUES (:user_id, :title, :content, :image_path)");
    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':content', $data['content']);
    $this->db->bind(':image_path', $data['image_path']);
    return $this->db->execute();
  }

  // Get a single blog by ID
  public function getBlogById($blogId) {
    $this->db->query("SELECT * FROM blogs WHERE blog_id = :blog_id");
    $this->db->bind(':blog_id', $blogId);
    return $this->db->single();
  }

  // Update any blog (admin can edit any blog)
  public function updateBlog($data) {
    $this->db->query("UPDATE blogs 
                      SET title = :title, content = :content, image_path = :image_path 
                      WHERE blog_id = :blog_id");
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':content', $data['content']);
    $this->db->bind(':image_path', $data['image_path']);
    $this->db->bind(':blog_id', $data['blog_id']);
    return $this->db->execute();
  }

  // Delete any blog (admin can delete any blog)
  public function deleteBlog($blogId) {
    $this->db->query("DELETE FROM blogs WHERE blog_id = :blog_id");
    $this->db->bind(':blog_id', $blogId);
    return $this->db->execute();
  }
}

?>







