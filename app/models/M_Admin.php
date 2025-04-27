<?php 
class M_Admin {
  private $db;

  public function __construct() {
      $this->db = new Database();
  }

  public function getAllUsers() {
    $this->db->query('SELECT user_id, username, email, role, profile_picture, created_at, is_deactive
                      FROM user 
                      ORDER BY created_at DESC');
    return $this->db->resultSet();
  }

  public function getUserDetails($user_id) {
    // First get the basic user data
    $this->db->query('SELECT * FROM user WHERE user_id = :user_id');
    $this->db->bind(':user_id', $user_id);
    $user = $this->db->single();
    
    if(!$user) {
        return false;
    }
    
    // For admin and moderator, just return the base user data
    if($user->role == 'Admin' || $user->role == 'Moderator') {
        return $user;
    }
    
    // For other roles, fetch their specific additional data
    switch($user->role) {
        case 'Caregiver':
            $this->db->query('SELECT * FROM caregiver WHERE caregiver_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $additionalData = $this->db->single();
            break;
            
        case 'Careseeker':
            $this->db->query('SELECT * FROM careseeker WHERE careseeker_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $additionalData = $this->db->single();
            break;
            
        case 'Consultant':
            $this->db->query('SELECT * FROM consultant WHERE consultant_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $additionalData = $this->db->single();
            break;
            
        default:
            $additionalData = null;
    }
    
    // Combine the data and return
    if($additionalData) {
        $userData = (object) array_merge((array) $user, (array) $additionalData);
        return $userData;
    }
    
    return $user;
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


    public function getTotalUsersCount() {
        $this->db->query('SELECT COUNT(*) as count FROM user');
        $result = $this->db->single();
        return $result->count;
    }

    public function getCaregiversCount() {
        $this->db->query('SELECT COUNT(*) as count FROM user WHERE role = "Caregiver"');
        $result = $this->db->single();
        return $result->count;
    }

    public function getConsultantsCount() {
        $this->db->query('SELECT COUNT(*) as count FROM user WHERE role = "Consultant"');
        $result = $this->db->single();
        return $result->count;
    }

    public function getCareseekerCount() {
        $this->db->query('SELECT COUNT(*) as count FROM user WHERE role = "Careseeker"');
        $result = $this->db->single();
        return $result->count;
    }

    public function getPendingUsersCount() {
        // Count pending caregivers
        $this->db->query('SELECT COUNT(*) as count FROM caregiver 
                        INNER JOIN user ON caregiver.caregiver_id = user.user_id 
                        WHERE caregiver.is_approved = "pending"');
        $pendingCaregivers = $this->db->single()->count;
        
        // Count pending consultants
        $this->db->query('SELECT COUNT(*) as count FROM consultant 
                        INNER JOIN user ON consultant.consultant_id = user.user_id 
                        WHERE consultant.is_approved = "pending"');
        $pendingConsultants = $this->db->single()->count;
        
        return $pendingCaregivers + $pendingConsultants;
    }

    public function getRejectedUsersCount() {
        // Count rejected caregivers
        $this->db->query('SELECT COUNT(*) as count FROM caregiver 
                        INNER JOIN user ON caregiver.caregiver_id = user.user_id 
                        WHERE caregiver.is_approved = "rejected"');
        $rejectedCaregivers = $this->db->single()->count;
        
        // Count rejected consultants
        $this->db->query('SELECT COUNT(*) as count FROM consultant 
                        INNER JOIN user ON consultant.consultant_id = user.user_id 
                        WHERE consultant.is_approved = "rejected"');
        $rejectedConsultants = $this->db->single()->count;
        
        return $rejectedCaregivers + $rejectedConsultants;
    }


    public function getFlaggedUsersCount() {
        // Count flagged caregivers (cancellation_flags > 5)
        $this->db->query('SELECT COUNT(*) as count FROM caregiver 
                         INNER JOIN user ON caregiver.caregiver_id = user.user_id 
                         WHERE caregiver.cancellation_flags > 5');
        $flaggedCaregivers = $this->db->single()->count;
        
        // Count flagged consultants (cancellation_flags > 5)
        $this->db->query('SELECT COUNT(*) as count FROM consultant 
                         INNER JOIN user ON consultant.consultant_id = user.user_id 
                         WHERE consultant.cancellation_flags > 5');
        $flaggedConsultants = $this->db->single()->count;
        
        return $flaggedCaregivers + $flaggedConsultants;
    }
    
    public function getFlaggedUsers() {
        // Get flagged caregivers
        $this->db->query('SELECT user.user_id, user.username, user.email, user.role, caregiver.cancellation_flags 
                         FROM caregiver 
                         INNER JOIN user ON caregiver.caregiver_id = user.user_id 
                         WHERE caregiver.cancellation_flags > 5');
        $flaggedCaregivers = $this->db->resultSet();
        
        // Get flagged consultants
        $this->db->query('SELECT user.user_id, user.username, user.email, user.role, consultant.cancellation_flags 
                         FROM consultant 
                         INNER JOIN user ON consultant.consultant_id = user.user_id 
                         WHERE consultant.cancellation_flags > 5');
        $flaggedConsultants = $this->db->resultSet();
        
        // Combine the results
        return array_merge($flaggedCaregivers, $flaggedConsultants);
    }


    // things for admin dashboard
    public function getCompletedJobsCount() {
        // Count completed jobs from both carerequests and consultantrequests
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests WHERE status = 'completed') +
                         (SELECT COUNT(*) FROM consultantrequests WHERE status = 'completed') as count");
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getRejectedJobsCount() {
        // Count rejected jobs from both carerequests and consultantrequests
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests WHERE status = 'rejected') +
                         (SELECT COUNT(*) FROM consultantrequests WHERE status = 'rejected') as count");
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getPendingJobsCount() {
        // Count pending jobs from both carerequests and consultantrequests
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests WHERE status = 'pending') +
                         (SELECT COUNT(*) FROM consultantrequests WHERE status = 'pending') as count");
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getCancelledJobsCount() {
        // Count cancelled jobs from both carerequests and consultantrequests
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests WHERE status = 'cancelled') +
                         (SELECT COUNT(*) FROM consultantrequests WHERE status = 'cancelled') as count");
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getLastWeekCompletedCount() {
        // Count completed jobs from last week from both tables
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests 
                          WHERE status = 'completed' 
                          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)) +
                         (SELECT COUNT(*) FROM consultantrequests 
                          WHERE status = 'completed' 
                          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)) as count");
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getLastMonthCompletedCount() {
        // Count completed jobs from last month from both tables
        $this->db->query("SELECT 
                         (SELECT COUNT(*) FROM carerequests 
                          WHERE status = 'completed' 
                          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) +
                         (SELECT COUNT(*) FROM consultantrequests 
                          WHERE status = 'completed' 
                          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) as count");
        $result = $this->db->single();
        return $result->count;
    }
     
         // getting all request details
         public function getAllCareRequests() {
            $this->db->query('
                SELECT cr.*, 
                       cg_user.username AS provider_name,
                       cs_user.username AS careseeker_name,
                       "Caregiving" AS service_category
                FROM carerequests cr
                LEFT JOIN caregiver cg ON cr.caregiver_id = cg.caregiver_id
                LEFT JOIN user cg_user ON cg.caregiver_id = cg_user.user_id
                LEFT JOIN user cs_user ON cr.requester_id = cs_user.user_id
                ORDER BY cr.created_at DESC
            ');
            
            return $this->db->resultSet();
        }
        
        public function getAllConsultRequests() {
            $this->db->query('
                SELECT cr.*, 
                       cons_user.username AS provider_name,
                       cs_user.username AS careseeker_name,
                       "Consultation" AS service_category
                FROM consultantrequests cr
                LEFT JOIN consultant cons ON cr.consultant_id = cons.consultant_id
                LEFT JOIN user cons_user ON cons.consultant_id = cons_user.user_id
                LEFT JOIN user cs_user ON cr.requester_id = cs_user.user_id
                ORDER BY cr.created_at DESC
            ');
            
            return $this->db->resultSet();
        }
    
    public function getActiveUsersCount() {
        // Count active users
        $this->db->query("SELECT COUNT(*) as count FROM user");
        $result = $this->db->single();
        return $result->count;
    }

    /**
 * Get all payment details for caregivers and consultants
 * 
 * @return array Payment records with provider details
 */
public function getAllPayments() {
    // Get caregiver payments
    $this->db->query("SELECT 
                     cr.request_id, 
                     cr.caregiver_id as provider_id,
                     u.username as provider_name,
                     cr.payment_details,
                     cr.is_paid,
                     cr.fine_amount,
                     cr.refund_amount,
                     cr.status,
                     cr.created_at,
                     'Caregiving' as service_type,
                     ROUND(cr.payment_details * 0.08) as we4u_commission
                     FROM carerequests cr
                     JOIN user u ON cr.caregiver_id = u.user_id
                     WHERE cr.is_paid = 1 AND cr.status = 'completed'");
    
    $caregiverPayments = $this->db->resultSet();
    
    // Get consultant payments
    $this->db->query("SELECT 
                     cr.request_id, 
                     cr.consultant_id as provider_id,
                     u.username as provider_name,
                     cr.payment_details,
                     cr.is_paid,
                     cr.fine_amount,
                     cr.refund_amount,
                     cr.status,
                     cr.created_at,
                     'Consultation' as service_type,
                     ROUND(cr.payment_details * 0.08) as we4u_commission
                     FROM consultantrequests cr
                     JOIN user u ON cr.consultant_id = u.user_id
                     WHERE cr.is_paid = 1 AND cr.status = 'completed'");
    
    $consultantPayments = $this->db->resultSet();
    
    // Combine both result sets
    $allPayments = array_merge($caregiverPayments, $consultantPayments);
    
    // Sort by created_at (newest first)
    usort($allPayments, function($a, $b) {
        return strtotime($b->created_at) - strtotime($a->created_at);
    });
    
    return $allPayments;
}

public function getTotalUserEarnings() {
    $this->db->query("SELECT 
                     (SELECT COALESCE(SUM(payment_details - (payment_details * 0.08)), 0) FROM carerequests WHERE is_paid = 1 AND status ='completed' ) +
                     (SELECT COALESCE(SUM(payment_details - (payment_details * 0.08)), 0) FROM consultantrequests WHERE is_paid = 1 AND status ='completed' ) as total");
    $result = $this->db->single();
    return $result->total;
}


    public function getTotalWE4UEarnings() {
        $this->db->query("SELECT 
                        (SELECT COALESCE(SUM(payment_details * 0.08), 0) FROM carerequests WHERE is_paid = 1 AND status ='completed' ) +
                        (SELECT COALESCE(SUM(payment_details * 0.08), 0) FROM consultantrequests WHERE is_paid = 1 AND status = 'completed') as total");
        $result = $this->db->single();
        return $result->total;
    }


    public function getLastMonthUserEarnings() {
        $this->db->query("SELECT 
                        (SELECT COALESCE(SUM(payment_details - (payment_details * 0.08)), 0) FROM carerequests 
                        WHERE is_paid = 1 AND status ='completed'
                        AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) +
                        (SELECT COALESCE(SUM(payment_details - (payment_details * 0.08)), 0) FROM consultantrequests 
                        WHERE is_paid = 1 AND status = 'completed'
                        AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) as total");
        $result = $this->db->single();
        return $result->total;
    }

    public function getLastMonthWE4UEarnings() {
        $this->db->query("SELECT 
                        (SELECT COALESCE(SUM(payment_details * 0.08), 0) FROM carerequests 
                        WHERE is_paid = 1 AND status ='completed'
                        AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) +
                        (SELECT COALESCE(SUM(payment_details * 0.08), 0) FROM consultantrequests 
                        WHERE is_paid = 1 AND status = 'completed'
                        AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) as total");
        $result = $this->db->single();
        return $result->total;
    }

    public function getTotalFineAmount() {
        $this->db->query("SELECT 
                        (SELECT COALESCE(SUM(fine_amount), 0) FROM carerequests WHERE fine_amount > 0) +
                        (SELECT COALESCE(SUM(fine_amount), 0) FROM consultantrequests WHERE fine_amount > 0) as total");
        $result = $this->db->single();
        return $result->total;
    }


    //blogs
    // Get all blogs (admin can see all)
   public function getAllBlogs() {
    $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC");
    return $this->db->resultSet();
  }

  // Add a new blog
  public function addBlog($data) {
    $this->db->query("INSERT INTO blogs (user_id, title, content, image_path,created_at,updated_at) 
                      VALUES (:user_id, :title, :content, :image_path,:created_at,:updated_at)");
    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':content', $data['content']);
    $this->db->bind(':image_path', $data['image_path']);
    $this->db->bind(':created_at', date('Y-m-d H:i:s'));
    $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
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

  public function activateUser($userId) {
    $this->db->query('UPDATE user SET is_deactive = 0 WHERE user_id = :user_id');
    $this->db->bind(':user_id', $userId);
    return $this->db->execute();
}

public function deactivateUser($userId) {
    $this->db->query('UPDATE user SET is_deactive = 1 WHERE user_id = :user_id');
    $this->db->bind(':user_id', $userId);
    return $this->db->execute();
}



    
    
}
?>







