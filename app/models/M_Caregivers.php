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

public function updateCaregiverProfile($data){
    $this->db->query('UPDATE user u
    JOIN caregiver c ON u.user_id = c.caregiver_id
    SET u.username = :username, 
    u.email= :email,
    u.profile_picture = :profile_picture,
    u.updated_at = NOW(),
    c.address = :address,
    c.contact_info = :contact_info,
    c.caregiver_type = :caregiver_type,
    c.specialty = :specialty,
    c.skills = :skills,
    c.qualification = :qualification,
    c.available_region = :available_region,
    c.price_per_session = :price_per_session,
    c.price_per_day = :price_per_day,
    c.bio = :bio
    WHERE u.email = :email');

    $this->db->bind(':username',$data['username']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':profile_picture', $data['profile_picture']);
    $this->db->bind(':address', $data['address']);
    $this->db->bind(':contact_info', $data['contact_info']);
    $this->db->bind(':caregiver_type', $data['caregiver_type']);
    $this->db->bind(':specialty', $data['specialty']);
    $this->db->bind(':skills', $data['skills']);
    $this->db->bind(':qualification', $data['qualification']);
    $this->db->bind(':available_region', $data['available_region']);
    $this->db->bind(':price_per_session', $data['payment_per_session']);
    $this->db->bind(':price_per_day', $data['payment_per_day']);
    $this->db->bind(':bio', $data['bio']);

    return $this->db->execute();
}


public function getCaregivers($region = '', $type = '', $speciality = '', $sortBy = '', $page = 1, $perPage = 8) {
    // Build the SQL query with JOIN between users and caregivers tables
    $sql = "SELECT c.*, u.username, u.profile_picture, u.gender, u.date_of_birth
            FROM caregiver c
            JOIN user u ON c.caregiver_id = u.user_id
            WHERE u.role = 'Caregiver' AND c.is_approved = 'approved'";
    
    // Add filters if provided
    $params = [];
    
    if (!empty($region)) {
        $sql .= " AND c.available_region LIKE :region";
        $params[':region'] = "%$region%"; // Using LIKE for comma-separated values
    }
    
    if (!empty($type)) {
        $sql .= " AND c.caregiver_type = :type";
        $params[':type'] = $type;
    }
    
    if (!empty($speciality)) {
        $sql .= " AND c.specialty LIKE :speciality";
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

public function getCaregiversCount($region = '', $type = '', $speciality = '') {
    // Count query with same filters as main query
    $sql = "SELECT COUNT(*) as count
            FROM caregiver c
            JOIN user u ON c.caregiver_id = u.user_id
            WHERE u.role = 'Caregiver' AND c.is_approved = 'approved'";
    
    // Add filters if provided
    $params = [];
    
    if (!empty($region)) {
        $sql .= " AND c.available_region LIKE :region";
        $params[':region'] = "%$region%";
    }
    
    if (!empty($type)) {
        $sql .= " AND c.caregiver_type = :type";
        $params[':type'] = $type;
    }
    
    if (!empty($speciality)) {
        $sql .= " AND c.specialty LIKE :speciality";
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
    $this->db->query("SELECT DISTINCT available_region FROM caregiver WHERE is_approved = 'approved'");
    $results = $this->db->resultSet();
    
    $allRegions = [];
    foreach ($results as $row) {
        $regions = explode(',', $row->available_region);
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

public function getCaregiverById($id) {
    $this->db->query("SELECT c.*, u.username, u.profile_picture, u.gender, u.email, u.date_of_birth 
                    FROM caregiver c
                    JOIN user  ON c.caregiver_id = u.user_id
                    WHERE c.caregiver_id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
}


//view care requests for caregiver
public function getAllCareRequestsByCaregiver($caregiverId)
{
    $this->db->query("
        SELECT cr.*, u.username AS requester_name, u.profile_picture,
               cr.created_at AS request_date,
               DATE_FORMAT(cr.created_at, '%e %b %Y') AS formatted_date,
               DATE_FORMAT(cr.created_at, '%h:%i %p') AS formatted_time
        FROM carerequests cr
        LEFT JOIN user u ON cr.requester_id = u.user_id
        WHERE cr.caregiver_id = :caregiver_id
        ORDER BY cr.created_at DESC
    ");
    $this->db->bind(':caregiver_id', $caregiverId);
    return $this->db->resultSet();
}


// To get all request details
public function getFullCareRequestInfo($requestId)
{
    $this->db->query("SELECT cr.*, 
                             cs.address AS careseeker_address,
                             cs.contact_info AS careseeker_contact,
                             cs.rating AS careseeker_rating,
                             u.profile_picture AS caregiver_pic,
                             req_user.username AS careseeker_name,
                             req_user.profile_picture AS careseeker_pic,
                             req_user.email AS careseeker_email,
                           CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS elder_name,
                             e.profile_picture AS elder_pic,
                             e.relationship_to_careseeker
                      FROM carerequests cr
                      LEFT JOIN careseeker cs ON cr.requester_id = cs.careseeker_id
                      LEFT JOIN user req_user ON req_user.user_id = cr.requester_id
                      LEFT JOIN user u ON u.user_id = cr.caregiver_id
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
    $this->db->query("UPDATE carerequests SET status = :status WHERE request_id = :request_id");
    $this->db->bind(':status', $status);
    $this->db->bind(':request_id', $request_id);
    return $this->db->execute();
}

//Get request by ID
public function getRequestById($request_id) {
    $this->db->query("SELECT * FROM carerequests WHERE request_id = :request_id");
    $this->db->bind(':request_id', $request_id);
    return $this->db->single();
}

public function cancelRequestWithRefund($requestId, $refundAmount, $shouldFlag = false) {
    // Update request status to cancelled
    $this->db->query("UPDATE carerequests 
                      SET status = 'cancelled', 
                          refund_amount = :refund_amount 
                      WHERE request_id = :id");
    
    $this->db->bind(':refund_amount', $refundAmount);
    $this->db->bind(':id', $requestId);
    
    $requestUpdated = $this->db->execute();
    
    // If flagging is required, increment the caregiver's flag count
    if ($shouldFlag) {
        // First get the caregiver ID from the request
        $this->db->query("SELECT caregiver_id FROM carerequests WHERE request_id = :id");
        $this->db->bind(':id', $requestId);
        $result = $this->db->single();
        
        if ($result && isset($result->caregiver_id)) {
            // Increment the flag count in caregivers table
            $this->db->query("UPDATE caregiver
                              SET cancellation_flags = COALESCE(cancellation_flags, 0) + 1 
                              WHERE caregiver_id = :caregiver_id");
            $this->db->bind(':caregiver_id', $result->caregiver_id);
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
                         WHERE request_id = :id");
        $this->db->bind(':refund_amount', $refundAmount);
        $this->db->bind(':id', $requestId);
        $this->db->execute();
    }*/
    
    return $requestUpdated;
}

public function getPaymentHistory($caregiverId){
    $this->db->query('SELECT cp.*,u.username,u.profile_picture
    FROM care_payments cp
    JOIN user u ON cp.payer_id = u.user_id
    
    WHERE cp.caregiver_id = :caregiverId
   
    ORDER BY cp.payment_date DESC');

    $this->db->bind(':caregiverId', $caregiverId);

    return $this->db->resultSet();
}




}
?>

