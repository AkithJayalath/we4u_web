<?php 
class M_Consultant {
    private $db;

    public function __construct() {
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

    public function register($data, $uploadedFiles) {
        // Insert into the User table
        $this->db->query('INSERT INTO user (username, email, gender, date_of_birth, password, role) 
                          VALUES (:username, :email, :gender, :dob, :password, :role)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dob', $data['dob']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'Consultant'); 

        // Execute the query and check if the insertion was successful
        if ($this->db->execute()) {
            // Get the ID of the newly created user
            $newUserId = $this->db->lastInsertId();

            // Insert into the Consultant table
            $this->db->query('INSERT INTO consultant (consultant_id, national_id, address, contact_info, consultant_type) 
                              VALUES (:consultant_id, :national_id, :address, :contact_info, :consultant_type)');
            $this->db->bind(':c_id', $newUserId);
            $this->db->bind(':national_id', $data['national_id']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':contact_info', $data['contact_info']);
            $this->db->bind(':consultant_type', $data['type_of_consultant']);
            $this->db->bind(':expertice', $data['specifications']);
            $this->db->bind(':qualifications', $data['qualifications']);

            // Execute the caregiver-specific insertion
            // if ($this->db->execute()) {
            //     // Insert into the Approval Request table
            //     $this->db->query('INSERT INTO approvalrequest (user_id, request_type, request_date, status, comments) 
            //                       VALUES (:user_id, :request_type, :request_date, :status, :comments)');
            //     $this->db->bind(':user_id', $newUserId);
            //     $this->db->bind(':request_type', 'Consultant'); // or any appropriate type
            //     $this->db->bind(':request_date', date('Y-m-d H:i:s'));
            //     $this->db->bind(':status', 'Pending'); // Initial status
            //     $this->db->bind(':comments', '');

            // }
        }

        return false; // Return false if any step fails
    }
}
?>