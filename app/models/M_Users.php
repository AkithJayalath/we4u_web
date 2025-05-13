<?php
  class M_Users{
    private $db; 
    
    public function __construct()
    {
      $this->db = new Database();
    }

    // find the user by user email
    public function findUserByEmail($email){ 
      //:indicate a bind value
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);

      $row = $this->db->single();

      if($this->db->rowCount() > 0){
        return true;
      }
      else{
        return false;
      }
    }

    public function validateDate($date) {
      $d = DateTime::createFromFormat('Y-m-d', $date);
      return $d && $d->format('Y-m-d') === $date;
  }

    // Register the User
    public function register($data) {
      // Insert into the User table
      $this->db->query('INSERT INTO user(username, email, gender, date_of_birth, password, role) 
                        VALUES(:username, :email, :gender, :dob, :password, :role)');
      
      $this->db->bind(':username', $data['username']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':gender', $data['gender']);
      $this->db->bind(':dob', $data['dob']);
      $this->db->bind(':password', $data['password']);
      $this->db->bind(':role', 'Careseeker');
  
      // Execute the query and check if the insertion was successful
      if ($this->db->execute()) {
          // Get the ID of the newly created user
          $newUserId = $this->db->lastInsertId();
  
          // Insert only the careseeker_id into the Careseeker table
          $this->db->query('INSERT INTO careseeker(careseeker_id) VALUES(:careseeker_id)');
          $this->db->bind(':careseeker_id', $newUserId);
          
          // Execute the query and return true if successful
          return $this->db->execute();
      } else {
          return false;
      }
  }
  

    // Login the User
    public function login($email, $password){
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);
      // this will return the entire row that means the password also comes with this so there is a problem 
      $row = $this->db->single();

      $hashed_password =  $row->password;

      if(password_verify($password, $hashed_password)){
        return $row;
      }
      else {
        return false;
      }
    }


    public function getCareseekerProfile($userId){
      $this->db->query('SELECT * FROM careseeker_profile WHERE user_id=:user_id');
      $this->db->bind(':user_id',$userId);
      $results = $this->db->resultSet();

      return $results;
    }

    // update profile
    public function updateCareseekerProfile($data) {
      // Update `user` table
      $this->db->query('UPDATE user SET 
                          username = :username,
                          email = :email,
                          date_of_birth = :date_of_birth,
                          gender = :gender
                        WHERE user_id = :user_id');
  
      $this->db->bind(':username', $data['username']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':date_of_birth', $data['date_of_birth']);
      $this->db->bind(':gender', $data['gender']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->execute(); // Execute the user update query

      // If there's a profile_pic update, execute it separately
      if (!empty($data['profile_picture'])) {
        $this->db->query('UPDATE user SET profile_picture = :profile_picture WHERE user_id = :user_id');
        $this->db->bind(':profile_picture', $data['profile_picture_name']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->execute(); // Execute the profile_picture update query
    }
  
      // If there's a password update, execute it separately
      if (!empty($data['password'])) {
          $this->db->query('UPDATE user SET password = :password WHERE user_id = :user_id');
          $this->db->bind(':password', $data['password']);
          $this->db->bind(':user_id', $data['user_id']);
          $this->db->execute(); // Execute the password update query
      }
  
      // Update `careseeker` table
      $this->db->query('UPDATE careseeker SET 
                          address = :address,
                          contact_info = :contact_info
                        WHERE careseeker_id = :user_id');
  
      $this->db->bind(':address', $data['address']);
      $this->db->bind(':contact_info', $data['contact_info']);
      $this->db->bind(':user_id', $data['user_id']);
      return $this->db->execute(); // Execute and return the result of the careseeker update query
  }


  public function deleteUser($userId){
    $this->db->query('DELETE FROM user WHERE user_id =:user_id');
    $this->db->bind(':user_id',$userId);
    return $this->db->execute();
  }


  public function getApprovalStatus($user_id, $role) {
    if ($role == 'Caregiver') {
        $sql = "SELECT is_approved FROM caregiver WHERE caregiver_id = :user_id";
    } elseif ($role == 'Consultant') {
        $sql = "SELECT is_approved FROM consultant WHERE consultant_id = :user_id";
    } else {
        return 'approved'; // Other roles don't require approval
    }

    $this->db->query($sql);
    $this->db->bind(':user_id', $user_id);
    $row = $this->db->single();

    return $row ? $row->is_approved : 'pending'; // Return the status or 'pending' if no record is found
}

    public function getFilteredConsultants($filters, $page, $perPage) {
        $sql = 'SELECT c.*, u.username, u.gender, u.profile_picture 
                FROM consultant c 
                JOIN user u ON c.consultant_id = u.user_id 
                WHERE u.role = "Consultant"';

        $params = $this->buildFilterParams($filters);
        $sql .= $this->buildFilterConditions($filters);
        $sql .= $this->buildSortCondition($filters['sort'] ?? '');

        // Pagination
        $offset = ($page - 1) * $perPage;
        $sql .= ' LIMIT :offset, :limit';
        $params[':offset'] = $offset;
        $params[':limit'] = $perPage;

        $this->db->query($sql);
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    public function getTotalFilteredConsultants($filters) {
        $sql = 'SELECT COUNT(*) as total 
                FROM consultant c 
                JOIN user u ON c.consultant_id = u.user_id 
                WHERE u.role = "Consultant"';

        $params = $this->buildFilterParams($filters);
        $sql .= $this->buildFilterConditions($filters);

        $this->db->query($sql);
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        $result = $this->db->single();
        return $result->total;
    }

    public function getAllRegions() {
        $this->db->query('SELECT DISTINCT available_regions FROM consultant WHERE available_regions IS NOT NULL');
        $results = $this->db->resultSet();

        $regions = [];
        foreach ($results as $result) {
            if (!empty($result->available_regions)) {
                $regionArray = explode(',', $result->available_regions);
                foreach ($regionArray as $region) {
                    $trimmedRegion = trim($region);
                    if (!empty($trimmedRegion) && !in_array($trimmedRegion, $regions)) {
                        $regions[] = $trimmedRegion;
                    }
                }
            }
        }

        sort($regions);
        return $regions;
    }

    public function getAllSpecialities() {
        $this->db->query('SELECT specializations, expertise FROM consultant');
        $results = $this->db->resultSet();

        $specialities = [];
        foreach ($results as $result) {
            if (!empty($result->specializations)) {
                $specialitiesArray = explode(',', $result->specializations);
                foreach ($specialitiesArray as $speciality) {
                    $trimmedSpeciality = trim($speciality);
                    if (!empty($trimmedSpeciality) && !in_array($trimmedSpeciality, $specialities)) {
                        $specialities[] = $trimmedSpeciality;
                    }
                }
            }
        }

        sort($specialities);
        return $specialities;
    }

    public function getConsultantsCount($region, $type, $speciality) {
        $query = "SELECT COUNT(*) as total FROM consultant WHERE 1=1";

        if (!empty($region)) {
            $query .= " AND available_regions LIKE :region";
        }
        if (!empty($type)) {
            $query .= " AND type = :type";
        }
        if (!empty($speciality)) {
            $query .= " AND specializations LIKE :speciality";
        }

        $this->db->query($query);

        if (!empty($region)) {
            $this->db->bind(':region', '%' . $region . '%');
        }
        if (!empty($type)) {
            $this->db->bind(':type', $type);
        }
        if (!empty($speciality)) {
            $this->db->bind(':speciality', '%' . $speciality . '%');
        }

        return $this->db->single()->total;
    }

    public function getConsultants($region, $type, $speciality, $sortBy, $page, $perPage) {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT c.*, u.username, u.profile_picture, u.gender 
                  FROM consultant c 
                  JOIN user u ON c.consultant_id = u.user_id 
                  WHERE u.role = 'Consultant'";

        if (!empty($region)) {
            $query .= " AND c.available_regions LIKE :region";
        }
        if (!empty($type)) {
            $query .= " AND c.type = :type";
        }
        if (!empty($speciality)) {
            $query .= " AND c.specializations LIKE :speciality";
        }

        // Sorting
        if ($sortBy === 'rating') {
            $query .= " ORDER BY c.rating DESC";
        } elseif ($sortBy === 'price-asc') {
            $query .= " ORDER BY c.payment_details ASC";
        } elseif ($sortBy === 'price-desc') {
            $query .= " ORDER BY c.payment_details DESC";
        } else {
            $query .= " ORDER BY c.consultant_id DESC";
        }

        $query .= " LIMIT :offset, :perPage";

        $this->db->query($query);

        if (!empty($region)) {
            $this->db->bind(':region', '%' . $region . '%');
        }
        if (!empty($type)) {
            $this->db->bind(':type', $type);
        }
        if (!empty($speciality)) {
            $this->db->bind(':speciality', '%' . $speciality . '%');
        }

        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // === Helper Methods ===

    private function buildFilterParams($filters) {
        $params = [];

        if (!empty($filters['username'])) {
            $params[':username'] = '%' . $filters['username'] . '%';
        }
        if (!empty($filters['region'])) {
            $params[':region'] = '%' . $filters['region'] . '%';
        }
        if (!empty($filters['speciality'])) {
            $params[':speciality'] = '%' . $filters['speciality'] . '%';
        }

        return $params;
    }

    private function buildFilterConditions($filters) {
        $conditions = '';

        if (!empty($filters['username'])) {
            $conditions .= ' AND u.username LIKE :username';
        }
        if (!empty($filters['region'])) {
            $conditions .= ' AND c.available_regions LIKE :region';
        }
        if (!empty($filters['speciality'])) {
            $conditions .= ' AND c.specializations LIKE :speciality';
        }

        return $conditions;
    }

    private function buildSortCondition($sort) {
        switch ($sort) {
            case 'price_asc':
                return ' ORDER BY c.payment_details ASC';
            case 'price_desc':
                return ' ORDER BY c.payment_details DESC';
            case 'rating':
                return ' ORDER BY c.rating DESC';
            default:
                return '';
        }
    }

    

 
public function findUserByEmailCode($email) {
    $this->db->query('SELECT * FROM user WHERE email = :email');
    $this->db->bind(':email', $email);
    return $this->db->single();
}



    
public function storeResetCode($email, $code, $expiryTime) {
   
    $user = $this->findUserByEmailCode($email);
    if (!$user) {
        return false;
    }
    
    $userId = $user->user_id;
    
   
    $this->db->query("SELECT * FROM password_resets WHERE user_id = :user_id");
    $this->db->bind(':user_id', $userId);
    $existingReset = $this->db->single();
    
    if ($existingReset) {
       
        $this->db->query("UPDATE password_resets SET 
                        reset_code = :reset_code, 
                        expiry_time = :expiry_time, 
                        created_at = NOW(),
                        is_used = 0
                        WHERE user_id = :user_id");
    } else {
        
        $this->db->query("INSERT INTO password_resets (user_id, reset_code, expiry_time, created_at) 
                        VALUES (:user_id, :reset_code, :expiry_time, NOW())");
    }
    
    $this->db->bind(':user_id', $userId);
    $this->db->bind(':reset_code', $code);
    $this->db->bind(':expiry_time', $expiryTime);
    
    return $this->db->execute();
}


public function verifyResetCode($email, $code) {
    
    $user = $this->findUserByEmailCode($email);
    if (!$user) {
        return false;
    }
    
    $userId = $user->user_id;
    
    
    $this->db->query("SELECT * FROM password_resets 
                    WHERE user_id = :user_id 
                    AND reset_code = :reset_code 
                    AND is_used = 0");
    
    $this->db->bind(':user_id', $userId);
    $this->db->bind(':reset_code', $code);
    
    $reset = $this->db->single();
    
    return $reset ? $reset : false;
}


public function updatePassword($email, $hashedPassword) {
    $this->db->query("UPDATE user SET password = :password WHERE email = :email");
    $this->db->bind(':email', $email);
    $this->db->bind(':password', $hashedPassword);
    
    return $this->db->execute();
}


public function invalidateResetCodes($email) {
    
    $user = $this->findUserByEmailCode($email);
    if (!$user) {
        return false;
    }
    
    $userId = $user->user_id;
    
    
    $this->db->query("UPDATE password_resets SET is_used = 1 WHERE user_id = :user_id");
    $this->db->bind(':user_id', $userId);
    
    return $this->db->execute();
}

public function getAllBlogs($page, $perPage) {
    
    $offset = ($page - 1) * $perPage;

    
    $this->db->query('SELECT b.*, u.username, u.profile_picture
                      FROM blogs b
                      JOIN user u ON b.user_id = u.user_id
                      ORDER BY b.created_at DESC
                      LIMIT :offset, :perPage'); 
    $this->db->bind(':offset', $offset, PDO::PARAM_INT);
    $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);

    return $this->db->resultSet();
}

// Fetch a single blog by its ID for detailed view
public function getBlogById($blogId) {
    $this->db->query('SELECT b.*, u.username, u.profile_picture 
                      FROM blogs b
                      JOIN user u ON b.user_id = u.user_id
                      WHERE b.blog_id = :blog_id'); // Fetch blog by ID
    $this->db->bind(':blog_id', $blogId);
    return $this->db->single();
}

// Announcement methods
public function getActiveAnnouncements() {
    $this->db->query('SELECT * FROM announcement 
                     WHERE status = "Published" 
                     ORDER BY publish_date DESC');
    return $this->db->resultSet();
}

public function getAnnouncementById($id) {
    $this->db->query('SELECT * FROM announcement 
                     WHERE announcement_id = :id 
                     AND status = "Published"');
    $this->db->bind(':id', $id);
    return $this->db->single();
}

}

