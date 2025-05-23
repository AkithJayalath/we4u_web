<?php 
class M_Careseekers{
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    public function createElderProfile($data) { 
        $query = "INSERT INTO elderprofile (
            careseeker_id,
            first_name,
            middle_name,
            last_name,
            relationship_to_careseeker,
            age,
            gender,
            weight,
            height,
            blood_pressure,
            emergency_contact,
            hbc,
            chronic_disease,
            current_health_issues,
            allergies,
            surgical_history,
            family_diseases,
            current_medications,
            special_needs,
            dietary_restrictions,
            profile_picture
        ) VALUES (
            :careseeker_id,
            :first_name,
            :middle_name,
            :last_name,
            :relationship_to_careseeker,
            :age,
            :gender,
            :weight,
            :height,
            :blood_pressure,
            :emergency_contact,
            :hbc,
            :chronic_disease,
            :current_health_issues,
            :allergies,
            :surgical_history,
            :family_diseases,
            :current_medications,
            :special_needs,
            :dietary_restrictions,
            :profile_pic
        )";

        // Bind values
        $this->db->query($query);
        $this->db->bind(':careseeker_id', $data['careseeker_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':middle_name', $data['middle_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':relationship_to_careseeker', $data['relationship_to_careseeker']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':height', $data['height']);
        $this->db->bind(':blood_pressure', $data['blood_pressure']);
        $this->db->bind(':emergency_contact', $data['emergency_contact']);
        $this->db->bind(':hbc', $data['hbc']);
        $this->db->bind(':chronic_disease', $data['chronic_disease']);
        $this->db->bind(':current_health_issues', $data['current_health_issues']);
        $this->db->bind(':allergies', $data['allergies']);
        $this->db->bind(':surgical_history', $data['surgical_history']);
        $this->db->bind(':family_diseases', $data['family_diseases']);
        $this->db->bind(':current_medications', $data['current_medications']);
        $this->db->bind(':special_needs', $data['special_needs']);
        $this->db->bind(':dietary_restrictions', $data['dietary_restrictions']);
        $this->db->bind(':profile_pic', $data['profile_picture_name']);

       
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

   
    public function getAllElders() {
        $query = "SELECT * FROM elders";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    
    public function getElderProfilesByCareseeker($careseeker_id) {
        $this->db->query('SELECT * FROM elderprofile WHERE careseeker_id = :careseeker_id');
        $this->db->bind(':careseeker_id', $careseeker_id);
    
        return $this->db->resultSet();
    }
    
    public function getElderProfilesData($elderID) {
        $this->db->query('SELECT * FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id=:careseeker_id');
        $this->db->bind(':careseeker_id', $_SESSION['user_id']);
        $this->db->bind(':elder_id', $elderID);
    
        return $this->db->single();
    }

    
    public function getElderProfileById($elderID, $careseeker_id)
{
    $this->db->query('SELECT * FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id = :careseeker_id');
    $this->db->bind(':elder_id', $elderID);
    $this->db->bind(':careseeker_id', $careseeker_id);

    $row = $this->db->single();

    
    if ($this->db->rowCount() > 0) {
        return $row; 
    } else {
        return false; 
    }
}


    public function deleteElderProfile($elderId) {
      
        $query = 'DELETE FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id = :careseeker_id';

        
        $this->db->query($query);
        $this->db->bind(':elder_id', $elderId);
        $this->db->bind(':careseeker_id', $_SESSION['user_id']);  

        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function updateElderProfile($data,$elderID){
        $sql = "UPDATE elderprofile 
            SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                relationship_to_careseeker = :relationship_to_careseeker,
                age = :age,
                gender = :gender,
                weight = :weight,
                height = :height,
                blood_pressure = :blood_pressure,
                emergency_contact = :emergency_contact,
                chronic_disease = :chronic_disease,
                current_health_issues = :current_health_issues,
                allergies = :allergies,
                health_barriers = :health_barriers,
                surgical_history = :surgical_history,
                family_diseases = :family_diseases,
                current_medications = :current_medications,
                special_needs = :special_needs,
                dietary_restrictions = :dietary_restrictions,
                profile_picture = :profile_picture
            WHERE elder_id = :elder_id AND careseeker_id=:careseeker_id";

   
    $this->db->query($sql);

    
    $this->db->bind(':first_name', $data['first_name']);
    $this->db->bind(':middle_name', $data['middle_name']);
    $this->db->bind(':last_name', $data['last_name']);
    $this->db->bind(':relationship_to_careseeker', $data['relationship_to_careseeker']);
    $this->db->bind(':age', $data['age']);
    $this->db->bind(':gender', $data['gender']);
    $this->db->bind(':weight', $data['weight']);
    $this->db->bind(':height', $data['height']);
    $this->db->bind(':blood_pressure', $data['blood_pressure']);
    $this->db->bind(':emergency_contact', $data['emergency_contact']);
    $this->db->bind(':chronic_disease', $data['chronic_disease']);
    $this->db->bind(':current_health_issues', $data['current_health_issues']);
    $this->db->bind(':allergies', $data['allergies']);
    $this->db->bind(':health_barriers', $data['health_barriers']);
    $this->db->bind(':surgical_history', $data['surgical_history']);
    $this->db->bind(':family_diseases', $data['family_diseases']);
    $this->db->bind(':current_medications', $data['current_medications']);
    $this->db->bind(':special_needs', $data['special_needs']);
    $this->db->bind(':dietary_restrictions', $data['dietary_restrictions']);
    $this->db->bind(':profile_picture', $data['profile_picture_name']);
    $this->db->bind(':elder_id', $elderID);
    $this->db->bind(':careseeker_id', $_SESSION['user_id']);

    
    if ($this->db->execute()) {
       return true;
    } else {
        return false;
    }
    


}

public function sendCareRequest($data) {
    $this->db->query('INSERT INTO carerequests 
    (requester_id, elder_id, caregiver_id, duration_type, start_date, end_date, time_slots, expected_services, additional_notes, status, payment_details,service_address) 
    VALUES 
    (:careseeker_id, :elder_id, :caregiver_id, :duration_type, :start_date, :end_date, :time_slots, :expected_services, :additional_notes, :status, :payment_details,:service_address)');

    
    $this->db->bind(':careseeker_id', $data['careseeker_id']);
    $this->db->bind(':elder_id', $data['elder_id']);
    $this->db->bind(':caregiver_id', $data['caregiver_id']);
    $this->db->bind(':duration_type', $data['duration_type']);
    $this->db->bind(':time_slots', is_string($data['time_slots']) ? $data['time_slots'] : json_encode($data['time_slots']));
    $this->db->bind(':expected_services', $data['expected_services']);
    $this->db->bind(':additional_notes', $data['additional_notes']);
    $this->db->bind(':status', $data['status']);
    $this->db->bind(':payment_details', $data['total_payment']);
    $this->db->bind(':service_address', $data['service_address']);

   
    if ($data['duration_type'] === 'long-term') {
        $this->db->bind(':start_date', $data['from_date']);
        $this->db->bind(':end_date', $data['to_date']);
    } else {
        $this->db->bind(':start_date', $data['from_date_short']);
        $this->db->bind(':end_date', $data['from_date_short']); 
    }
    
    if ($this->db->execute()) {

        return [
            'success' => true,
            'id' => $this->db->lastInsertId()
        ];
    } else {
        return [
            'success' => false,
            'id' => null
        ];
    }
}

public function getCaregiverById($caregiverId) {
    $this->db->query('
        SELECT c.*, u.email 
        FROM caregiver c
        JOIN user u ON c.caregiver_id = u.user_id
        WHERE c.caregiver_id = :caregiver_id
    ');
    $this->db->bind(':caregiver_id', $caregiverId);
    
    return $this->db->single();
}

public function sendConsultantRequest($data) {
    
    $startTime = sprintf('%02d:00:00', $data['from_time']);
    $endTime = sprintf('%02d:00:00', $data['to_time']);
    $this->db->query('INSERT INTO consultantrequests 
    (requester_id, elder_id, consultant_id, appointment_date, start_time,end_time, expected_services, additional_notes, payment_details, status) 
    VALUES 
    (:careseeker_id, :elder_id, :consultant_id, :appointment_date, :start_time,:end_time, :expected_services, :additional_notes, :payment_amount, :status)');

   
    $this->db->bind(':careseeker_id', $data['careseeker_id']);
    $this->db->bind(':elder_id', $data['elder_id']);
    $this->db->bind(':consultant_id', $data['consultant_id']);
    $this->db->bind(':appointment_date', $data['appointment_date']);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    $this->db->bind(':expected_services', $data['expected_services']);
    $this->db->bind(':additional_notes', $data['additional_notes']);
    $this->db->bind(':payment_amount', $data['total_amount']);
    $this->db->bind(':status', $data['status']);
    
    return $this->db->execute();
}

public function getAllCareRequestsByUser($userId)
{
    $this->db->query("
        SELECT cr.*, u.username AS caregiver_name
        FROM carerequests cr
        LEFT JOIN caregiver cg ON cr.caregiver_id = cg.caregiver_id
        LEFT JOIN user u ON cr.caregiver_id = u.user_id
        WHERE cr.requester_id = :user_id
        ORDER BY cr.created_at DESC
    ");
    $this->db->bind(':user_id', $userId);
    return $this->db->resultSet();
}


public function getAllConsultRequestsByUser($userId)
{
    $this->db->query("
        SELECT cr.*, u.username AS consultant_name
        FROM consultantrequests cr
        LEFT JOIN consultant cs ON cr.consultant_id = cs.consultant_id
        LEFT JOIN user u ON cr.consultant_id = u.user_id
        WHERE cr.requester_id = :user_id
        ORDER BY cr.created_at DESC
    ");
    $this->db->bind(':user_id', $userId);
    return $this->db->resultSet();
}

public function getFullCareRequestInfo($requestId)
{ 
    $this->db->query("SELECT cr.*, 
                             cg.caregiver_id, 
                             u.username AS caregiver_name, 
                             u.email AS caregiver_email, 
                             u.profile_picture AS caregiver_pic,
                             req_user.profile_picture AS requester_pic,
                           CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS elder_name,
                             e.profile_picture AS elder_pic,
                             e.relationship_to_careseeker
                      FROM carerequests cr
                      LEFT JOIN caregiver cg ON cr.caregiver_id = cg.caregiver_id
                      LEFT JOIN user req_user ON req_user.user_id = cr.requester_id
                      LEFT JOIN user u ON u.user_id = cr.caregiver_id
                      LEFT JOIN elderprofile e ON e.elder_id = cr.elder_id
                      WHERE cr.request_id = :request_id");
    $this->db->bind(':request_id', $requestId);

    return $this->db->single();
}


public function getFullConsultRequestInfo($requestId)
{
    $this->db->query("SELECT cr.*, 
                             cs.consultant_id, 
                             u.username AS consultant_name, 
                             u.email AS consultant_email, 
                             u.profile_picture AS consultant_pic,
                             req_user.profile_picture AS requester_pic,
                           CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS elder_name,
                             e.profile_picture AS elder_pic,
                             e.relationship_to_careseeker
                      FROM consultantrequests cr
                      LEFT JOIN consultant cs ON cr.consultant_id = cs.consultant_id
                      LEFT JOIN user req_user ON req_user.user_id = cr.requester_id
                      LEFT JOIN user u ON u.user_id = cr.consultant_id
                      LEFT JOIN elderprofile e ON e.elder_id = cr.elder_id
                      WHERE cr.request_id = :request_id");
    $this->db->bind(':request_id', $requestId);

    return $this->db->single();
}





public function getReviews($caregiver_id) {
    $this->db->query('SELECT r.*, u.username, u.profile_picture, r.rating, r.review_date, r.updated_date
                      FROM review r
                      JOIN user u ON r.reviewer_id = u.user_id
                      WHERE r.reviewed_user_id = :caregiver_id
                      AND r.review_role = "Caregiver"
                      ORDER BY COALESCE(r.updated_date,r.review_date) DESC');

    $this->db->bind(':caregiver_id', $caregiver_id);
    return $this->db->resultSet();
}

public function getAvgRating($user_id, $role) {
    $this->db->query('SELECT AVG(r.rating) AS avg_rating
                      FROM review r
                      WHERE r.reviewed_user_id = :user_id
                      AND r.review_role = :role');

    $this->db->bind(':user_id', $user_id);
    $this->db->bind(':role', $role);
    $result = $this->db->single();
    return round($result->avg_rating ?? 0, 1);
}

public function showCaregiverProfile($caregiver_id) {
    $this->db->query('SELECT u.*, c.*
                      FROM user u
                      JOIN caregiver c ON u.user_id = c.caregiver_id
                      WHERE u.user_id = :caregiver_id');

    $this->db->bind(':caregiver_id', $caregiver_id);
    return $this->db->single();
}




public function showCareseekerProfile($careseeker_id) {
    $this->db->query('SELECT u.*, c.*
                      FROM user u
                      JOIN careseeker c ON u.user_id = c.careseeker_id
                      WHERE u.user_id = :careseeker_id');

    $this->db->bind(':careseeker_id', $careseeker_id);
    return $this->db->single();
}




public function getReviewsConsultant($consultant_id) {
    $this->db->query('SELECT r.*, u.username, u.profile_picture, r.rating, r.review_date
                      FROM review r
                      JOIN user u ON r.reviewer_id = u.user_id
                      WHERE r.reviewed_user_id = :consultant_id
                      AND r.review_role = "Consultant"
                      ORDER BY r.review_date DESC');

    $this->db->bind(':consultant_id', $consultant_id);
    return $this->db->resultSet();
}

public function getAvgRatingConsultant($consultant_id) {
    $this->db->query('SELECT AVG(r.rating) AS avg_rating
                      FROM review r
                      WHERE r.reviewed_user_id = :consultant_id 
                      AND r.review_role = "Consultant"');

    $this->db->bind(':consultant_id', $consultant_id);
    $result = $this->db->single();
    return round($result->avg_rating ?? 0, 1);
}

public function showConsultantProfile($consultant_id) {
    $this->db->query('SELECT u.*, c.*
                      FROM user u
                      JOIN consultant c ON u.user_id = c.consultant_id
                      WHERE u.user_id = :consultant_id');

    $this->db->bind(':consultant_id', $consultant_id);
    return $this->db->single();
}


public function getRequestById($id) {
    $this->db->query("SELECT * FROM carerequests
     WHERE request_id = :id");
    $this->db->bind(':id', $id);

    return $this->db->single();
}

public function cancelRequestWithFineAndRefund($requestId, $fineAmount, $refundAmount) {
    $this->db->query("UPDATE carerequests 
                      SET status = 'cancelled', 
                          fine_amount = :fine_amount, 
                          refund_amount = :refund_amount,
                          is_paid = 0
                      WHERE request_id = :id");

    $this->db->bind(':fine_amount', $fineAmount);
    $this->db->bind(':refund_amount', $refundAmount);
    $this->db->bind(':id', $requestId);

    return $this->db->execute();
}

public function markFineAsPaid($requestId) {
    $this->db->query("UPDATE carerequests 
                      SET is_paid = 1 
                      WHERE request_id = :id");

    $this->db->bind(':id', $requestId);
    return $this->db->execute();
}



public function getConsultantRequestById($id) {
    $this->db->query("SELECT * FROM consultantrequests WHERE request_id = :id");
    $this->db->bind(':id', $id);

    return $this->db->single();
}

public function getConsultantById($consultantId) {
    $this->db->query('SELECT c.*, u.email 
                      FROM consultant c
                      JOIN user u ON c.consultant_id = u.user_id
                      WHERE c.consultant_id = :consultant_id');
    $this->db->bind(':consultant_id', $consultantId);
    
    return $this->db->single();
}

public function cancelConsultRequestWithFineAndRefund($requestId, $fineAmount, $refundAmount) {
    $this->db->query("UPDATE consultantrequests 
                      SET status = 'cancelled', 
                          fine_amount = :fine_amount, 
                          refund_amount = :refund_amount,
                          is_paid = 0
                      WHERE request_id = :id");

    $this->db->bind(':fine_amount', $fineAmount);
    $this->db->bind(':refund_amount', $refundAmount);
    $this->db->bind(':id', $requestId);

    return $this->db->execute();
}

public function deleteRequest($requestId) {
    $this->db->query('DELETE FROM carerequests WHERE request_id = :request_id');
    $this->db->bind(':request_id', $requestId);
    
   
    if ($this->db->execute()) {
        return true;
    } else {
        return false;
    }
}

public function markCareRequestAsPaid($requestId) {
    $this->db->query("UPDATE carerequests SET is_paid = 1 WHERE request_id = :id");
    $this->db->bind(':id', $requestId);
    $this->db->execute();
}

public function markConsultRequestAsPaid($requestId) {
    $this->db->query("UPDATE consultantrequests SET is_paid = 1 WHERE request_id = :id");
    $this->db->bind(':id', $requestId);
    $this->db->execute();
}





public function getAllConsultantSessions($careseeker_id) {
    $this->db->query("SELECT 
                        cs.*, 
                        cr.appointment_date, 
                        cr.start_time,
                        cr.end_time, 
                        cr.status,
                        u.username AS consultant_name,
                        u.profile_picture AS consultant_pic,
                        ep.profile_picture AS elder_pic,
                        ep.relationship_to_careseeker,
                        CONCAT(ep.first_name, ' ', ep.middle_name, ' ', ep.last_name) AS elder_name
                      FROM consultantsessions cs
                      JOIN consultantrequests cr ON cs.request_id = cr.request_id
                      JOIN user u ON cs.consultant_id = u.user_id
                      JOIN elderprofile ep ON cs.elder_id = ep.elder_id
                      WHERE cs.careseeker_id = :careseeker_id
                      ORDER BY cs.updated_at DESC");

    $this->db->bind(':careseeker_id', $careseeker_id);
    return $this->db->resultSet();
}


public function getAllConsultantSessionsById($session_id) {
    $this->db->query("SELECT 
                        cs.*, 
                        cr.appointment_date, 
                        cr.start_time,
                        cr.end_time, 
                        cr.status,
                        u.username AS consultant_name,
                        u.profile_picture AS consultant_pic,
                        ep.elder_id,
                        ep.profile_picture AS elder_pic,
                        ep.relationship_to_careseeker,
                        CONCAT(ep.first_name, ' ', ep.middle_name, ' ', ep.last_name) AS elder_name
                      FROM consultantsessions cs
                      JOIN consultantrequests cr ON cs.request_id = cr.request_id
                      JOIN user u ON cs.consultant_id = u.user_id
                      JOIN elderprofile ep ON cs.elder_id = ep.elder_id
                      WHERE cs.session_id = :session_id
                      ORDER BY cs.updated_at DESC");

    $this->db->bind(':session_id', $session_id);
    return $this->db->single();
}



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
    
    $this->db->query("SELECT * FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    $file = $this->db->single();

    if ($file && $file->file_type !== 'link') {
        
        $file_path = dirname(APPROOT) . '/public/' . $file->file_value;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

   
    $this->db->query("DELETE FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    return $this->db->execute();
}

public function deleteSchedulesByRequestId($request_id) {
    
    $this->db->query('DELETE FROM cg_shedules WHERE request_id = :request_id');
    $this->db->bind(':request_id', $request_id);
    $shortResult = $this->db->execute();
    
    // Delete entries from long schedules table
    $this->db->query('DELETE FROM cg_long_shedules WHERE request_id = :request_id');
    $this->db->bind(':request_id', $request_id);
    $longResult = $this->db->execute();
    
    // Return true if either deletion was successful
    return $shortResult || $longResult;
}

public function getFileById($file_id) {
    $this->db->query("SELECT * FROM sessionfiles WHERE file_id = :file_id");
    $this->db->bind(':file_id', $file_id);
    return $this->db->single();
}





public function getSessionFilesByUploader($session_id, $uploaded_by) {
    $this->db->query("SELECT * FROM sessionfiles 
                    WHERE session_id = :session_id 
                    AND uploaded_by = :uploaded_by 
                    ORDER BY uploaded_at DESC");
    $this->db->bind(':session_id', $session_id);
    $this->db->bind(':uploaded_by', $uploaded_by);
    return $this->db->resultSet();
}


public function getPaymentsByCareseekerId($careseekerId){
    $this->db->query("SELECT cp.*,u.username AS name From care_payments cp
                        JOIN user u ON cp.caregiver_id = u.user_id
                        WHERE cp.payer_id = :careseekerId");
    $this->db->bind(':careseekerId', $careseekerId);
    return $this->db->resultSet();
}

public function getConsultPaymentsByCareseekerId($careseekerId){
    $this->db->query("SELECT cp.*,u.username AS name From consultant_payments cp
                        JOIN user u ON cp.consultant_id = u.user_id
                        WHERE cp.payer_id = :careseekerId");
    $this->db->bind(':careseekerId', $careseekerId);
    return $this->db->resultSet();
}

public function getCareseekerCaregivingHistory($careseekerId){
    $this->db->query('SELECT cr.*, u.username, u.profile_picture, e.first_name, e.middle_name, e.last_name, e.relationship_to_careseeker, e.profile_picture AS elder_pic
    FROM carerequests cr
    JOIN user u ON cr.caregiver_id = u.user_id
    JOIN elderprofile e ON cr.elder_id = e.elder_id
    WHERE cr.requester_id = :careseekerId
    AND (cr.status = "completed")
    ORDER BY cr.created_at DESC');

    $this->db->bind(':careseekerId', $careseekerId);

    return $this->db->resultSet();
}

public function getCareseekerConsultHistory($careseekerId){
    $this->db->query('SELECT cr.*, u.username, u.profile_picture, e.first_name, e.middle_name, e.last_name, e.relationship_to_careseeker, e.profile_picture AS elder_pic
    FROM consultantrequests cr
    JOIN user u ON cr.consultant_id = u.user_id
    JOIN elderprofile e ON cr.elder_id = e.elder_id
    WHERE cr.requester_id = :careseekerId
    AND (cr.status = "completed")
    ORDER BY cr.created_at DESC');

    $this->db->bind(':careseekerId', $careseekerId);

    return $this->db->resultSet();
}



public function addReview($data) {
    $this->db->query('INSERT INTO review (reviewer_id, reviewed_user_id, review_role, rating, review_text, review_date,updated_date) 
                      VALUES (:reviewer_id, :reviewed_user_id, :review_role, :rating, :review_text, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
    $this->db->bind(':reviewer_id', $data['reviewer_id']);
    $this->db->bind(':reviewed_user_id', $data['reviewed_user_id']);
    $this->db->bind(':review_role', $data['review_role']);
    $this->db->bind(':rating', $data['rating']);
    $this->db->bind(':review_text', $data['review_text']);
    return $this->db->execute();
}

public function getReviewById($review_id) {
    $this->db->query('SELECT * FROM review WHERE review_id = :review_id');
    $this->db->bind(':review_id', $review_id);
    return $this->db->single();
}

public function editReview($data) {
    $this->db->query('UPDATE review SET 
                      rating = :rating,
                      review_text = :review_text,
                      updated_date = CURRENT_TIMESTAMP
                      WHERE review_id = :review_id');
    $this->db->bind(':rating', $data['rating']);
    $this->db->bind(':review_text', $data['review_text']);
    $this->db->bind(':review_id', $data['review_id']);
    return $this->db->execute();
}

public function deleteReview($review_id) {
    $this->db->query('DELETE FROM review WHERE review_id = :review_id');
    $this->db->bind(':review_id', $review_id);
    return $this->db->execute();
}

public function updateReview($data) {
    $this->db->query("UPDATE review 
                      SET review_text = :review_text, 
                          rating = :rating, 
                          updated_date = CURRENT_TIMESTAMP 
                      WHERE review_id = :review_id");
    $this->db->bind(':review_text', $data['review_text']);
    $this->db->bind(':rating', $data['rating']);
    $this->db->bind(':review_id', $data['review_id']);
    return $this->db->execute();
}

}
?>