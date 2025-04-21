<?php

class M_payment{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function storeCaregiverPayment($data) {
        $this->db->query("INSERT INTO care_payments 
            (care_request_id, payer_id, caregiver_id, amount) 
            VALUES (:requestId, :payerId, :caregiverId, :amount)");
        $this->db->bind(':requestId', $data['care_request_id']);
        $this->db->bind(':payerId', $data['payer_id']);
        $this->db->bind(':caregiverId', $data['caregiver_id']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }

    public function storeConsultantPayment($data) {
        $this->db->query("INSERT INTO consultant_payments 
            (consultant_request_id, payer_id, consultant_id, amount) 
            VALUES (:requestId, :payerId, :consultantId, :amount)");
        $this->db->bind(':requestId', $data['consultant_request_id']);
        $this->db->bind(':payerId', $data['payer_id']);
        $this->db->bind(':consultantId', $data['consultant_id']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }
}

?>