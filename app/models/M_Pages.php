<?php
    class M_Pages{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function getUsers(){
            try {
                $this->db->query('SELECT * FROM user');
                return $this->db->resultSet();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }
        
    }



?>