<?php
    class M_Moderator{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function get_requests(){
            $this->db->query('SELECT * FROM approvalrequest ORDER BY request_date DESC');
            $results = $this->db->resultSet();
            return $results;
        }


        // testing for the interview 

        // public function careseekerrequests($data){

        // }

        // function to update the request status
        public function updateRequestStatus($data) {
            // First check if interview exists for this request
            $this->db->query('SELECT * FROM interviews WHERE request_id = :request_id');
            $this->db->bind(':request_id', $data['request_id']);
            $interview = $this->db->single();
            
            // Update approval request status
            $this->db->query('UPDATE approvalrequest SET 
                              status = :status,
                              comments = :comment
                              WHERE request_id = :request_id');
        
            $this->db->bind(':request_id', $data['request_id']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':comment', $data['comment']);
            return $this->db->execute();

            // Update caregiver/consultant based on type from $data
            if ($data['type'] === 'Caregiver') {
                $this->db->query('UPDATE caregiver SET is_approved = :is_approved WHERE caregiver_id = :caregiver_id');
                $this->db->bind(':is_approved', $data['is_approved']);
                $this->db->bind(':caregiver_id', $data['user_id']);
                $this->db->execute();
            } else if ($data['type'] === 'Consultant') {
                $this->db->query('UPDATE consultant SET is_approved = :is_approved WHERE consultant_id = :user_id');
                $this->db->bind(':is_approved', $data['is_approved']);
                $this->db->bind(':consultant_id', $data['user_id']);
                $this->db->execute();
            }
            
            // If interview exists, update its status to Done
            if ($interview) {
                $this->db->query('UPDATE interviews SET status = "Done" WHERE request_id = :request_id');
                $this->db->bind(':request_id', $data['request_id']);
                $this->db->execute();
            }
        
        }

        // get caregiver full details
        public function get_caregiver($user_id){
            $this->db->query('SELECT * FROM approvalrequest INNER JOIN caregiver ON approvalrequest.user_id = caregiver.caregiver_id WHERE approvalrequest.user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $result = $this->db->single();
            return $result;
        }

        // get consultant details 
        public function get_consultant(){

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

        public function get_documents_by_id($user_id,$request_id){
            $this->db->query('SELECT * FROM document WHERE user_id = :user_id and request_id = :request_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':request_id', $request_id);
            $result = $this->db->resultSet();
            return $result;
        }

        public function get_inteviews(){
            $this->db->query('SELECT *FROM interviews ORDER BY request_date DESC, interview_time DESC');
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
        
        
    }



?>