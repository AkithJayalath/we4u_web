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


    public function getReviews($email){
        $this->db->query('SELECT r.*,u.username,u.profile_picture,r.rating,r.review_date
        FROM review r
        JOIN user u ON r.reviewer_id = u.user_id
        JOIN user c ON r.reviewed_user_id = c.user_id
        WHERE c.email = :email
        AND r.review_role = "Consultant"
        ORDER BY r.review_date DESC');
    
        $this->db->bind(':email',$email);
        return $this->db->resultSet();
    }
    
    public function getAvgRating($email){
        $this->db->query('SELECT AVG(r.rating) AS avg_rating
                          FROM review r
                          JOIN user u ON r.reviewed_user_id = u.user_id
                          WHERE u.email = :email AND r.review_role = "Consultant"');
    
        $this->db->bind(':email',$email);
        $result = $this->db->single();
        return round($result->avg_rating ?? 0, 1);
    }
    
    public function showConsultantProfile($email){
        $this->db->query('SELECT u.*,c.*
        FROM user u
        JOIN consultant c ON u.user_id = c.consultant_id
        WHERE u.email = :email');
    
        $this->db->bind(':email',$email);
        return $this->db->single();
    }

    public function updateConsultantProfile($data){
        $this->db->query('UPDATE user u
        JOIN consultant c ON u.user_id = c.consultant_id
        SET u.username = :username, 
        u.email= :email,
        u.profile_picture = :profile_picture,
        u.updated_at = NOW(),
        c.address = :address,
        c.contact_info = :contact_info,
        c.specializations = :specialty,
        c.qualifications = :qualification,
        c.available_regions = :available_region,
        c.payment_details = :payment_details,
        c.bio = :bio
        WHERE u.email = :email');
    
        $this->db->bind(':username',$data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact_info', $data['contact_info']);
        $this->db->bind(':specialty', $data['specialty']);
        $this->db->bind(':qualification', $data['qualification']);
        $this->db->bind(':available_region', $data['available_region']);
        $this->db->bind(':payment_details', $data['payment_per_hour']);
        $this->db->bind(':bio', $data['bio']);
    
        return $this->db->execute();
    }


    public function getConsultants($region = '', $type = '', $speciality = '', $sortBy = '', $page = 1, $perPage = 8) {
        // Build the SQL query with JOIN between users and caregivers tables
        $sql = "SELECT c.*, u.username, u.profile_picture, u.gender, u.date_of_birth
                FROM consultant c
                JOIN user u ON c.consultant_id = u.user_id
                WHERE u.role = 'Consultant' AND c.is_approved = 'approved'";
        
        // Add filters if provided
        $params = [];
        
        if (!empty($region)) {
            $sql .= " AND c.available_regions LIKE :region";
            $params[':region'] = "%$region%"; // Using LIKE for comma-separated values
        }
        
        
        if (!empty($speciality)) {
            $sql .= " AND c.specializations LIKE :speciality";
            $params[':speciality'] = "%$speciality%"; // Using LIKE for comma-separated values
        }
        
        // Add sorting
        // if (!empty($sortBy)) {
        //     switch ($sortBy) {
        //         case 'rating':
        //             $sql .= " ORDER BY c.rating DESC";
        //             break;
        //         case 'price-asc':
        //             $sql .= " ORDER BY c.payment_rate ASC";
        //             break;
        //         case 'price-desc':
        //             $sql .= " ORDER BY c.payment_rate DESC";
        //             break;
        //         default:
        //             $sql .= " ORDER BY c.rating DESC"; // Default sort
        //             break;
        //     }
        // } else {
        //     $sql .= " ORDER BY c.rating DESC"; // Default sort
        // }
        
        // Add pagination
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT :offset, :limit";
        $params[':offset'] = $offset;
        $params[':limit'] = $perPage;
        
        // Prepare and execute query
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }
    
    public function getConsultantsCount($region = '', $type = '', $speciality = '') {
        // Count query with same filters as main query
        $sql = "SELECT COUNT(*) as count
                FROM consultant c
                JOIN user u ON c.consultant_id = u.user_id
                WHERE u.role = 'Consultant' AND c.is_approved = 'approved'";
        
        // Add filters if provided
        $params = [];
        
        if (!empty($region)) {
            $sql .= " AND c.available_regions LIKE :region";
            $params[':region'] = "%$region%";
        }
        
        
        if (!empty($speciality)) {
            $sql .= " AND c.specializations LIKE :speciality";
            $params[':speciality'] = "%$speciality%";
        }
        
        // Prepare and execute query
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getAllRegions() {
        // Get all unique regions from the database (handling comma-separated values)
        $this->db->query("SELECT DISTINCT available_regions FROM consultant WHERE is_approved = 'approved'");
        $results = $this->db->resultSet();
        
        $allRegions = [];
        foreach ($results as $row) {
            $regions = explode(',', $row->available_regions);
            foreach ($regions as $region) {
                $region = trim($region);
                if (!empty($region) && !in_array($region, $allRegions)) {
                    $allRegions[] = $region;
                }
            }
        }
        
        sort($allRegions); // Sort alphabetically
        return $allRegions;
    }

    public function getConsultantById($id) {
        $this->db->query("SELECT c.*, u.username, u.profile_picture, u.gender, u.email, u.date_of_birth 
                        FROM consultant c
                        JOIN user u ON c.consultant_id = u.user_id
                        WHERE c.consultant_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }


    //view care requests for caregiver
public function getAllConsultRequestsByConsultant($consultantID)
{
    $this->db->query("
        SELECT cr.*, u.username AS requester_name, u.profile_picture,
               cr.created_at AS request_date,
               DATE_FORMAT(cr.created_at, '%e %b %Y') AS formatted_date,
               DATE_FORMAT(cr.created_at, '%h:%i %p') AS formatted_time
        FROM consultantrequests cr
        LEFT JOIN user u ON cr.requester_id = u.user_id
        WHERE cr.consultant_id = :consultant_id
        ORDER BY cr.created_at DESC
    ");
    $this->db->bind(':consultant_id', $consultantID);
    return $this->db->resultSet();
}

public function getFullConsultRequestInfo($requestId)
{
    $this->db->query("SELECT cr.*, 
                             cs.address AS careseeker_address,
                             cs.contact_info AS careseeker_contact,
                             cs.rating AS careseeker_rating,
                             u.profile_picture AS consultant_pic,
                             req_user.username AS careseeker_name,
                             req_user.profile_picture AS careseeker_pic,
                             req_user.email AS careseeker_email,
                           CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS elder_name,
                             e.profile_picture AS elder_pic,
                             e.relationship_to_careseeker
                      FROM consultantrequests cr
                      LEFT JOIN careseeker cs ON cr.requester_id = cs.careseeker_id
                      LEFT JOIN user req_user ON req_user.user_id = cr.requester_id
                      LEFT JOIN user u ON u.user_id = cr.consultant_id
                      LEFT JOIN elderprofile e ON e.elder_id = cr.elder_id
                      WHERE cr.request_id = :request_id");
    $this->db->bind(':request_id', $requestId);

    return $this->db->single();
}


public function getElderProfileById($elderId) {
    $this->db->query("
        SELECT 
            *, 
            CONCAT_WS(' ', first_name, middle_name, last_name) AS full_name 
        FROM elderprofile 
        WHERE elder_id = :elder_id
    ");
    $this->db->bind(':elder_id', $elderId);
    return $this->db->single();
}


//Accept or reject a request
public function updateRequestStatus($request_id, $status) {
    $this->db->query("UPDATE consultantrequests SET status = :status WHERE request_id = :request_id");
    $this->db->bind(':status', $status);
    $this->db->bind(':request_id', $request_id);
    return $this->db->execute();
}

//Get request by ID
public function getRequestById($request_id) {
    $this->db->query("SELECT * FROM consultantrequests WHERE request_id = :request_id");
    $this->db->bind(':request_id', $request_id);
    return $this->db->single();
}
    
//cancel requests

public function cancelRequestWithRefund($requestId, $refundAmount, $shouldFlag = false) {
    // Update request status to cancelled
    $this->db->query("UPDATE consultantrequests 
                      SET status = 'cancelled', 
                          refund_amount = :refund_amount 
                      WHERE request_id = :id");
    
    $this->db->bind(':refund_amount', $refundAmount);
    $this->db->bind(':id', $requestId);
    
    $requestUpdated = $this->db->execute();
    
    // If flagging is required, increment the consultant's flag count
    if ($shouldFlag) {
        // First get the consultant ID from the request
        $this->db->query("SELECT consultant_id FROM consultantrequests WHERE request_id = :id");
        $this->db->bind(':id', $requestId);
        $result = $this->db->single();
        
        if ($result && isset($result->consultant_id)) {
            // Increment the flag count in consultant table
            $this->db->query("UPDATE consultant
                              SET cancellation_flags = COALESCE(cancellation_flags, 0) + 1 
                              WHERE consultant_id = :consultant_id");
            $this->db->bind(':consultant_id', $result->consultant_id);
            $this->db->execute();
        }
    }
    
    // Process refund if necessary
    /*if ($refundAmount > 0) {
        // Update payment status to refunded
        $this->db->query("UPDATE payments 
                         SET status = 'refunded', 
                             refund_amount = :refund_amount,
                             refund_date = NOW() 
                         WHERE request_id = :id AND service_type = 'consultation'");
        $this->db->bind(':refund_amount', $refundAmount);
        $this->db->bind(':id', $requestId);
        $this->db->execute();
    }*/
    
    return $requestUpdated;
}

// handle consultant sessions
public function handleConsultantSession($consultant_id, $elder_id, $careseeker_id, $request_id) {
    // Check if session already exists for this consultant + elder + careseeker
    $this->db->query("SELECT * FROM consultantsessions 
                      WHERE consultant_id = :consultant_id 
                        AND elder_id = :elder_id 
                        AND careseeker_id = :careseeker_id");

    $this->db->bind(':consultant_id', $consultant_id);
    $this->db->bind(':elder_id', $elder_id);
    $this->db->bind(':careseeker_id', $careseeker_id);
    $existing = $this->db->single();

    if ($existing) {
        // Update request_id + updated_at
        $this->db->query("UPDATE consultantsessions 
                          SET request_id = :request_id 
                          WHERE session_id = :session_id");
        $this->db->bind(':request_id', $request_id);
        $this->db->bind(':session_id', $existing->session_id);
        $this->db->execute();
        return $existing->session_id;
    } else {
        // Create new session
        $this->db->query("INSERT INTO consultantsessions 
            (consultant_id, careseeker_id, elder_id, request_id) 
            VALUES (:consultant_id, :careseeker_id, :elder_id, :request_id)");
        $this->db->bind(':consultant_id', $consultant_id);
        $this->db->bind(':careseeker_id', $careseeker_id);
        $this->db->bind(':elder_id', $elder_id);
        $this->db->bind(':request_id', $request_id);
        $this->db->execute();
        return $this->db->lastInsertId();
    }
}

public function getAllConsultantSessions($consultant_id) {
    $this->db->query("SELECT 
                        cs.*, 
                        cr.appointment_date, 
                        cr.start_time,
                        cr.end_time, 
                        cr.status,
                        u.username AS careseeker_name,
                        u.profile_picture AS careseeker_pic,
                        ep.profile_picture AS elder_pic,
                        ep.relationship_to_careseeker,
                        CONCAT(ep.first_name, ' ', ep.middle_name, ' ', ep.last_name) AS elder_name
                      FROM consultantsessions cs
                      JOIN consultantrequests cr ON cs.request_id = cr.request_id
                      JOIN user u ON cs.careseeker_id = u.user_id
                      JOIN elderprofile ep ON cs.elder_id = ep.elder_id
                      WHERE cs.consultant_id = :consultant_id
                      ORDER BY cs.updated_at DESC");

    $this->db->bind(':consultant_id', $consultant_id);
    return $this->db->resultSet();
}


public function getAllConsultantSessionsById($session_id) {
    $this->db->query("SELECT 
                        cs.*, 
                        cr.appointment_date, 
                        cr.start_time,
                        cr.end_time, 
                        cr.status,
                        u.username AS careseeker_name,
                        u.profile_picture AS careseeker_pic,
                        ep.elder_id,
                        ep.profile_picture AS elder_pic,
                        ep.relationship_to_careseeker,
                        CONCAT(ep.first_name, ' ', ep.middle_name, ' ', ep.last_name) AS elder_name
                      FROM consultantsessions cs
                      JOIN consultantrequests cr ON cs.request_id = cr.request_id
                      JOIN user u ON cs.careseeker_id = u.user_id
                      JOIN elderprofile ep ON cs.elder_id = ep.elder_id
                      WHERE cs.session_id = :session_id
                      ORDER BY cs.updated_at DESC");

    $this->db->bind(':session_id', $session_id);
    return $this->db->single();
}


// upload session documents
public function uploadSessionFile($session_id, $uploaded_by, $file_type, $file_value) {
    $this->db->query("INSERT INTO sessionfiles 
                      (session_id, uploaded_by, file_type, file_value) 
                      VALUES (:session_id, :uploaded_by, :file_type, :file_value)");
    $this->db->bind(':session_id', $session_id);
    $this->db->bind(':uploaded_by', $uploaded_by);
    $this->db->bind(':file_type', $file_type);
    $this->db->bind(':file_value', $file_value);
    return $this->db->execute();
}



public function getSessionFiles($session_id) {
    $this->db->query("SELECT * FROM sessionfiles WHERE session_id = :session_id ORDER BY uploaded_at DESC");
    $this->db->bind(':session_id', $session_id);
    return $this->db->resultSet();
}


public function deleteSessionFile($file_id) {
    // First, get the file info
    $this->db->query("SELECT * FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    $file = $this->db->single();

    if ($file && $file->file_type !== 'link') {
        // It's a file, so delete from filesystem
        $file_path = dirname(APPROOT) . '/public/' . $file->file_value;
        if (file_exists($file_path)) {
            unlink($file_path); // delete the physical file
        }
    }

    // Delete from DB
    $this->db->query("DELETE FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    return $this->db->execute();
}

public function getFileById($file_id) {
    $this->db->query("SELECT * FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    return $this->db->single();
}




// Get files by uploader type (careseeker or consultant)
public function getSessionFilesByUploader($session_id, $uploaded_by) {
    $this->db->query("SELECT * FROM sessionfiles 
                    WHERE session_id = :session_id 
                    AND uploaded_by = :uploaded_by 
                    ORDER BY uploaded_at DESC");
    $this->db->bind(':session_id', $session_id);
    $this->db->bind(':uploaded_by', $uploaded_by);
    return $this->db->resultSet();
}



public function getSessionById($session_id) {
    $this->db->query("SELECT * FROM consultantsessions WHERE session_id = :session_id");
    $this->db->bind(':session_id', $session_id);
    return $this->db->single();
}
 

public function getCareseekerById($id) {
    $this->db->query("SELECT c.*, u.username, u.profile_picture, u.gender, u.email, u.date_of_birth 
                    FROM careseeker c
                    JOIN user u ON c.careseeker_id = u.user_id
                    WHERE c.careseeker_id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
}

public function getConsultingHistory($consultantId, $dateSort = 'newest', $statusFilter = 'all', $paymentFilter = 'all'){
    $sql = 'SELECT cr.*, u.username, u.profile_picture 
            FROM consultantrequests cr 
            JOIN user u ON cr.requester_id = u.user_id 
            WHERE cr.consultant_id = :consultantId';

    if ($statusFilter !== 'all') {
        $sql .= ' AND cr.status = :status';
    }

    // Add payment filter conditions
    switch ($paymentFilter) {
        case 'pending':
            $sql .= ' AND (cr.is_paid = 0 OR cr.is_paid IS NULL)';
            break;
        case 'done':
            $sql .= ' AND cr.is_paid = 1 AND (cr.refund_amount = 0 OR cr.refund_amount IS NULL)';
            break;
        case 'refunded':
            $sql .= ' AND cr.refund_amount IS NOT NULL';
            break;
    }
    
    // Add sorting
    if ($dateSort === 'newest') {
        $sql .= ' ORDER BY cr.created_at DESC';
    } else {
        $sql .= ' ORDER BY cr.created_at ASC';
    }
    
    $this->db->query($sql);
    $this->db->bind(':consultantId', $consultantId);

    if ($statusFilter !== 'all') {
        $this->db->bind(':status', $statusFilter);
    }

    return $this->db->resultSet();
}



}
?>