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
        return 'approved'; // Other roles don’t require approval
    }

    $this->db->query($sql);
    $this->db->bind(':user_id', $user_id);
    $row = $this->db->single();

    return $row ? $row->is_approved : 'pending'; // Return the status or 'pending' if no record is found
}


}

  