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

      // Get payment method by email
public function getPaymentMethod($email) {
    $this->db->query('SELECT * FROM payment_method WHERE email = :email');
    $this->db->bind(':email', $email);
    return $this->db->single();
}

// Add new payment method
public function addPaymentMethod($data) {
    $this->db->query('INSERT INTO payment_method (email, mobile_number, account_holder_name, 
                      bank_name, branch_name, account_number, payment_type_st, payment_type_lt, 
                      advance_amount, created_at) 
                      VALUES (:email, :mobile, :holder, :bank, :branch, :account, :st_type, 
                      :lt_type, :advance, NOW())');

    $this->db->bind(':email', $data['email']);
    $this->db->bind(':mobile', $data['mobile_number']);
    $this->db->bind(':holder', $data['account_holder_name']);
    $this->db->bind(':bank', $data['bank_name']);
    $this->db->bind(':branch', $data['branch_name']);
    $this->db->bind(':account', $data['account_number']);
    $this->db->bind(':st_type', $data['payment_type_st']);
    $this->db->bind(':lt_type', $data['payment_type_lt']);
    $this->db->bind(':advance', $data['advance_amount']);

    return $this->db->execute();
}

// Update payment method
public function updatePaymentMethod($data) {
    $this->db->query('UPDATE payment_method SET mobile_number = :mobile, 
                      account_holder_name = :holder, bank_name = :bank, 
                      branch_name = :branch, account_number = :account, 
                      payment_type_st = :st_type, payment_type_lt = :lt_type, 
                      advance_amount = :advance, updated_at = NOW() 
                      WHERE email = :email');

    $this->db->bind(':email', $data['email']);
    $this->db->bind(':mobile', $data['mobile_number']);
    $this->db->bind(':holder', $data['account_holder_name']);
    $this->db->bind(':bank', $data['bank_name']);
    $this->db->bind(':branch', $data['branch_name']);
    $this->db->bind(':account', $data['account_number']);
    $this->db->bind(':st_type', $data['payment_type_st']);
    $this->db->bind(':lt_type', $data['payment_type_lt']);
    $this->db->bind(':advance', $data['advance_amount']);

    return $this->db->execute();
}

// Delete payment method
public function deletePaymentMethod($email) {
    $this->db->query('DELETE FROM payment_method WHERE email = :email');
    $this->db->bind(':email', $email);
    return $this->db->execute();
}

public function submitReview($data) 
{
    $this->db->query('INSERT INTO review (review_id, reviewer_id, reviewed_user_id, review_role, review_text, review_date)
    VALUES (:review_id, :reviewer_id, :reviewed_user_id, :review_role, :review_text, NOW())'); 
    
    $this->db->bind(':review_id', $data['review_id']);
    $this->db->bind(':reviewer_id', $data['reviewer_id']);
    $this->db->bind(':reviewed_user_id', $data['reviewed_user_id']);
    $this->db->bind(':review_role', 'caregiver');
    $this->db->bind(':review_text', $data['review_text']);

    return $this->db->execute();

}

public function getReviews($email){
    $this->db->query('SELECT r.*,u.username,u.profile_picture,r.rating,r.review_date
    FROM review r
    JOIN user u ON r.reviewer_id = u.user_id
    JOIN user c ON r.reviewed_user_id = c.user_id
    WHERE c.email = :email
    AND r.review_role = "Caregiver"
    ORDER BY r.review_date DESC');

    $this->db->bind(':email',$email);
    return $this->db->resultSet();
}

public function getAvgRating($email){
    $this->db->query('SELECT AVG(r.rating) AS avg_rating
                      FROM review r
                      JOIN user u ON r.reviewed_user_id = u.user_id
                      WHERE u.email = :email AND r.review_role = "Caregiver"');

    $this->db->bind(':email',$email);
    $result = $this->db->single();
    return round($result->avg_rating ?? 0, 1);
}

public function showCaregiverProfile($email){
    $this->db->query('SELECT u.*,c.*
    FROM user u
    JOIN caregiver c ON u.user_id = c.caregiver_id
    WHERE u.email = :email');

    $this->db->bind(':email',$email);
    return $this->db->single();
}

}
?>

