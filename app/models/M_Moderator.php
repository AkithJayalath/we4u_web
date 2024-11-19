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

        // public function careseekerrequests($data){

        // }

        // function to update the request status(reject)
        public function updateRequestStatus($data) {
            $this->db->query('UPDATE approvalrequest SET 
                              status = :status, 
                            --   updated_at = NOW() ,
                              comments = :comment
                              WHERE request_id = :request_id');
        
            $this->db->bind(':request_id', $data['request_id']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':comment', $data['comment']);
        
            return $this->db->execute();
        }
        

        public function get_requests_by_id($request_id){
            $this->db->query('SELECT user.*, approvalrequest.* 
                              FROM user 
                              INNER JOIN approvalrequest ON user.user_id = approvalrequest.user_id 
                              WHERE approvalrequest.request_id = :request_id');
            $this->db->bind(':request_id', $request_id);
            $result = $this->db->single();
            return $result;
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