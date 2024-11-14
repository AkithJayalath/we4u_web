<?php
    class M_Moderator{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function get_requests(){
            $this->db->query('SELECT * FROM request ORDER BY request_date DESC');
            $results = $this->db->resultSet();
            return $results;
        }

        // public function careseekerrequests($data){

        // }
        
    }



?>