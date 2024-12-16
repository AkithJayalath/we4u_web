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
            $this->db->query('INSERT INTO consultant (consultant_id, national_id, address, contact_info, consultant_type, expertice, qualifications) 
                              VALUES (:consultant_id, :national_id, :address, :contact_info, :consultant_type, :expertice, :qualifications)');
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
    public function addReview($data) {
        // Insert into the User table
        $this->db->query('INSERT INTO review (review_id, reviewer_id, reviewed_user_id,review_role, rating, review_text, review_date, updated_date) 
                          VALUES (:review_id, :reviewer_id, :reviewed_user_id, :review_role, :rating, :review_text, CURRENT_DATE(), CURRENT_DATE())');
        $this->db->bind(':review_id', $data['review_id']);
        $this->db->bind(':reviewer_id', $data['reviewer_id']);
        $this->db->bind(':reviewed_user_id', $data['reviewed_user_id']);
        $this->db->bind(':review_role', $data['review_role']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review_text', $data['review_text']);
        // Execute the query and check if the insertion was successful
        return($this->db->execute());}

        public function getRateAndReviews() {
            // SQL query to connect to the 'review' table
            $this->db->query('SELECT r.*, u.username
                              FROM review r
                              JOIN user u ON r.reviewer_id = u.user_id
                              WHERE r.reviewed_user_id = :consultant_id
                              ORDER BY r.review_date DESC');
            
            // $this->db->query('SELECT * FROM review WHERE reviewer_id = 4');

            // Bind the consultant's ID from the session
            $this->db->bind(':consultant_id', $_SESSION['user_id']);
            
            // Return all matching records
            return $this->db->resultSet();
        }        

        public function getReviewsByConsultantId($consultantId) {
            $this->db->query('SELECT * FROM review WHERE reviewed_user_id = :reviewed_user_id');
            $this->db->bind(':reviewed_user_id', $consultantId);
            return $this->db->resultSet();
        }
        
        public function getReviewById($review_id) {
            $this->db->query('SELECT * FROM review WHERE review_id = :review_id');
            $this->db->bind(':review_id', $review_id);
            return $this->db->single();
        }
        
        public function editreview($data) {
            $this->db->query('UPDATE review SET 
                rating = :rating,
                review_text = :review_text,
                updated_date = CURRENT_TIMESTAMP()
                WHERE review_id = :review_id');
        
            $this->db->bind(':rating', $data['rating']);
            $this->db->bind(':review_text', $data['review_text']);
            $this->db->bind(':review_id', $data['review_id']);
            
            return $this->db->execute();
        }
        

    public function deleteReview($reviewId) {
        // Delete the review from the database
        $this->db->query('DELETE FROM review WHERE review_id = :review_id');
        $this->db->bind(':review_id', $reviewId);
        return $this->db->execute();
    }

}
?>