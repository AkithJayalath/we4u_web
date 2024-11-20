<?php 
class M_Caregivers {
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
        $this->db->bind(':role', 'Caregiver'); 

        // Execute the query and check if the insertion was successful
        if ($this->db->execute()) {
            // Get the ID of the newly created user
            $newUserId = $this->db->lastInsertId();

            // Insert into the Caregiver table
            $this->db->query('INSERT INTO caregiver (caregiver_id, national_id, address, contact_info, caregiver_type) 
                              VALUES (:caregiver_id, :national_id, :address, :contact_info, :caregiver_type)');
            $this->db->bind(':caregiver_id', $newUserId);
            $this->db->bind(':national_id', $data['national_id']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':contact_info', $data['contact_info']);
            $this->db->bind(':caregiver_type', $data['type_of_caregiver']);

            // Execute the caregiver-specific insertion
            if ($this->db->execute()) {
                // Insert into the Approval Request table
                $this->db->query('INSERT INTO approvalrequest (user_id, request_type, request_date, status, comments) 
                                  VALUES (:user_id, :request_type, :request_date, :status, :comments)');
                $this->db->bind(':user_id', $newUserId);
                $this->db->bind(':request_type', 'Caregiver'); // or any appropriate type
                $this->db->bind(':request_date', date('Y-m-d H:i:s'));
                $this->db->bind(':status', 'Pending'); // Initial status
                $this->db->bind(':comments', '');

                if ($this->db->execute()) {
                    $request_id = $this->db->lastInsertId();

                    // Insert documents into the 'document' table
                    foreach ($uploadedFiles as $file) {
                        $this->db->query('INSERT INTO document (request_id, user_id, document_type, file_path, upload_date) 
                                          VALUES (:request_id, :user_id, :document_type, :file_path, :upload_date)');
                        $this->db->bind(':request_id', $request_id);
                        $this->db->bind(':user_id', $newUserId);
                        $this->db->bind(':document_type', 'Caregiver Document'); // or any specific document type
                        $this->db->bind(':file_path', '/documents/approvalDocuments/' . $file);
                        $this->db->bind(':upload_date', date('Y-m-d H:i:s'));

                        if (!$this->db->execute()) {
                            return false; // Fail if any document insertion fails
                        }
                    }

                    return true; // Success
                }
            }
        }

        return false; // Return false if any step fails
    }
}
?>
