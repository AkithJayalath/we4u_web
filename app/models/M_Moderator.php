<?php
    class M_Moderator{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function get_requests(){
            $this->db->query('SELECT ar.*, u.email, u.username 
                             FROM approvalrequest ar 
                             JOIN user u ON ar.user_id = u.user_id 
                             ORDER BY ar.request_date DESC');
            $results = $this->db->resultSet();
            return $results;
        }

        public function get_pending_requests() {
            $this->db->query('SELECT ar.*, u.email, u.username 
                             FROM approvalrequest ar 
                             JOIN user u ON ar.user_id = u.user_id 
                             WHERE ar.status = "Pending" 
                             ORDER BY ar.request_date DESC');
            return $this->db->resultSet();
        }

        public function get_accepted_requests() {
            $this->db->query('SELECT ar.*, u.email, u.username 
                             FROM approvalrequest ar 
                             JOIN user u ON ar.user_id = u.user_id 
                             WHERE ar.status = "Approved" 
                             ORDER BY ar.request_date DESC');
            return $this->db->resultSet();
        }

        public function get_rejected_requests() {
            $this->db->query('SELECT ar.*, u.email, u.username 
                             FROM approvalrequest ar 
                             JOIN user u ON ar.user_id = u.user_id 
                             WHERE ar.status = "Declined" 
                             ORDER BY ar.request_date DESC');
            return $this->db->resultSet();
        }
        
          public function updateRequestStatus($data) {
              // First check if interview exists for this request
            //   $this->db->query('SELECT * FROM interviews WHERE request_id = :request_id');
            //   $this->db->bind(':request_id', $data['request_id']);
            //   $interview = $this->db->single();

              //check interview exists
              $interview = $this->checkInterviewExists($data['request_id']);
            
              // Update approval request status
              $this->db->query('UPDATE approvalrequest SET 
                                status = :status,
                                comments = :comment
                                WHERE request_id = :request_id');

              $this->db->bind(':request_id', $data['request_id']);
              $this->db->bind(':status', $data['status']);
              $this->db->bind(':comment', $data['comment']);
              $this->db->execute();

              // Update caregiver/consultant based on type from $data
              if ($data['role'] === 'Caregiver') {
                  $this->db->query('UPDATE caregiver SET is_approved = :is_approved WHERE caregiver_id = :user_id');
                  $this->db->bind(':is_approved', $data['is_approved']);
                  $this->db->bind(':user_id', $data['user_id']);
                  $this->db->execute();
              } else if ($data['role'] === 'Consultant') {
                  $this->db->query('UPDATE consultant SET is_approved = :is_approved WHERE consultant_id = :user_id');
                  $this->db->bind(':is_approved', $data['is_approved']);
                  $this->db->bind(':user_id', $data['user_id']);
                  $this->db->execute();
              }
            
              // If interview exists, update its status to Done
              if ($interview) {
                  $this->db->query('UPDATE interviews SET status = "Done" WHERE request_id = :request_id');
                  $this->db->bind(':request_id', $data['request_id']);
                  return $this->db->execute();
              }

              return true;
          }

          // UPDATE CAREgiver requests manually for testing
          public function updateCaregiverRequestStatus($caregiver_id) {
              $this->db->query('UPDATE caregiver SET is_approved = :is_approved WHERE caregiver_id = :caregiver_id');
              $this->db->bind(':is_approved', 'rejected');
              $this->db->bind(':caregiver_id', $caregiver_id);
              return true;
            }


        // get caregiver full details
        public function get_caregiver($user_id){
            $this->db->query('SELECT * FROM approvalrequest INNER JOIN caregiver ON approvalrequest.user_id = caregiver.caregiver_id WHERE approvalrequest.user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $result = $this->db->single();
            return $result;
        }

        // get consultant details 
        public function get_consultant($user_id){
            $this->db->query('SELECT * FROM approvalrequest INNER JOIN consultant ON approvalrequest.user_id = consultant.consultant_id WHERE approvalrequest.user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $result = $this->db->single();
            return $result;
        }

        public function get_users_by_id($user_id){
            $this->db->query('SELECT username, email, profile_picture, gender, date_of_birth FROM user WHERE user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $result = $this->db->single();
            return $result;
        }


        

        public function get_requests_by_id($request_id){
            $this->db->query('SELECT user.user_id, user.username, user.email, user.profile_picture,user.gender, user.date_of_birth, user.role,approvalrequest.*
                                         FROM user 
                                         INNER JOIN approvalrequest ON user.user_id = approvalrequest.user_id 
                                         WHERE approvalrequest.request_id = :request_id');
            $this->db->bind(':request_id', $request_id);
            $result = $this->db->single();
            return $result;
        }

        public function get_documents_by_id($user_id, $request_id) {
            $this->db->query('SELECT * FROM document WHERE user_id = :user_id AND request_id = :request_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':request_id', $request_id);
            return $this->db->resultSet();
        }
        

        public function get_inteviews(){
            $this->db->query('SELECT *FROM interviews ORDER BY request_date ASC, interview_time ASC');
            $results = $this->db->resultSet();
            return $results;
        }
        
        public function checkInterviewExists($request_id) {
            $this->db->query('SELECT * FROM interviews WHERE request_id = :request_id');
            $this->db->bind(':request_id', $request_id);
            $result = $this->db->single();
            return $result;
        }
        
        // Add method to get interview details
        public function getInterviewDetails($request_id) {
            $this->db->query('SELECT * FROM interviews WHERE request_id = :request_id');
            $this->db->bind(':request_id', $request_id);
            return $this->db->single();
        }

        // Add method to update interview
        public function updateInterview($data) {
            $this->db->query('UPDATE interviews SET request_date = :request_date, interview_time = :interview_time, 
                            platform = :platform, meeting_link = :meeting_link 
                            WHERE request_id = :request_id');

            $this->db->bind(':request_id', $data['request_id']);
            $this->db->bind(':request_date', $data['request_date']);
            $this->db->bind(':interview_time', $data['interview_time']);
            $this->db->bind(':platform', $data['platform']);
            $this->db->bind(':meeting_link', $data['meeting_link']);

            return $this->db->execute();
        }

        

        public function scheduleInterview($data) {
            $this->db->query('INSERT INTO interviews (request_id, request_date, interview_time, service, platform, meeting_link, provider_id, provider_name, provider_email) VALUES (:request_id, :request_date, :interview_time, :service, :platform, :meeting_link, :provider_id, :provider_name, :provider_email)');
    
            // Bind values
            $this->db->bind(':request_id', $data['request_id']);
            $this->db->bind(':request_date', $data['request_date']);
            $this->db->bind(':interview_time', $data['interview_time']);
            $this->db->bind(':service', $data['service']);
            $this->db->bind(':platform', $data['platform']);
            $this->db->bind(':meeting_link', $data['meeting_link']);
            $this->db->bind(':provider_id', $data['provider_id']);
            $this->db->bind(':provider_name', $data['provider_name']);
            $this->db->bind(':provider_email', $data['provider_email']);
    
            return $this->db->execute();
        }

        public function deleteInterview($request_id) {
            $this->db->query('DELETE FROM interviews WHERE request_id = :request_id');
            $this->db->bind(':request_id', $request_id);
            return $this->db->execute();
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

public function getCaregiverPayments() {
    $this->db->query('
        SELECT 
            cp.care_request_id,
            cp.caregiver_id,
            cp.amount,
            cp.payment_date,
            cp.is_paid,
            cp.we4u_earn,
            pm.account_holder_name,
            pm.bank_name,
            pm.branch_name,
            pm.account_number,
            pm.mobile_number,
            u.username as caregiver_name,
            cr.status as request_status
        FROM 
            care_payments cp
        JOIN 
            carerequests cr ON cp.care_request_id = cr.request_id
        JOIN 
            payment_method pm ON cp.caregiver_id = pm.user_id
        JOIN
            user u ON cp.caregiver_id = u.user_id
        WHERE 
            cr.status = "completed" AND
            cr.is_paid = 1
        ORDER BY 
            cp.payment_date DESC
    ');
    
    return $this->db->resultSet();
}

public function markPaymentAsPaid($care_request_id, $caregiver_id) {
    $this->db->query('
        UPDATE care_payments 
        SET is_paid = 1
        WHERE care_request_id = :care_request_id 
        AND caregiver_id = :caregiver_id
    ');
    
    $this->db->bind(':care_request_id', $care_request_id);
    $this->db->bind(':caregiver_id', $caregiver_id);
    
    // Execute the query
    return $this->db->execute();
}

public function getConsultantPayments() {
    $this->db->query('
        SELECT 
            cp.consultant_request_id,
            cp.consultant_id,
            cp.amount,
            cp.payment_date,
            cp.is_paid,
            cp.we4u_earn,
            pm.account_holder_name,
            pm.bank_name,
            pm.branch_name,
            pm.account_number,
            pm.mobile_number,
            u.username as consultant_name,
            cr.status as request_status
        FROM 
            consultant_payments cp
        JOIN 
            consultantrequests cr ON cp.consultant_request_id = cr.request_id
        JOIN 
            payment_method pm ON cp.consultant_id = pm.user_id
        JOIN
            user u ON cp.consultant_id = u.user_id
        WHERE 
            cr.status = "completed" AND cr.is_paid = 1
        ORDER BY 
            cp.payment_date DESC
    ');
    
    return $this->db->resultSet();
}

public function markConsultantPaymentAsPaid($payment_id, $consultant_id) {
    $this->db->query('
        UPDATE consultant_payments 
        SET is_paid = 1
        WHERE consultant_request_id = :payment_id 
        AND consultant_id = :consultant_id
    ');
    
    $this->db->bind(':payment_id', $payment_id);
    $this->db->bind(':consultant_id', $consultant_id);
    
    // Execute the query
    return $this->db->execute();
}


        
        
        
        
        
        
    }



?>






