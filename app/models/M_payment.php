<?php

class M_payment{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function storeCaregiverPayment($data) { 
        $this->db->query("INSERT INTO care_payments 
            (care_request_id, payer_id, caregiver_id, amount ,stripe_charge_id) 
            VALUES (:requestId, :payerId, :caregiverId, :amount , :stripeChargeId)");
        $this->db->bind(':requestId', $data['care_request_id']);
        $this->db->bind(':payerId', $data['payer_id']);
        $this->db->bind(':caregiverId', $data['caregiver_id']);
        $this->db->bind(':amount', $data['amount']); 
        $this->db->bind(':stripeChargeId', $data['stripe_charge_id']);
        return $this->db->execute();
    }

    public function storeConsultantPayment($data) {
        $this->db->query("INSERT INTO consultant_payments 
            (consultant_request_id, payer_id, consultant_id, amount ,stripe_charge_id) 
            VALUES (:requestId, :payerId, :consultantId, :amount , :stripeChargeId)");
        $this->db->bind(':requestId', $data['consultant_request_id']);
        $this->db->bind(':payerId', $data['payer_id']);
        $this->db->bind(':consultantId', $data['consultant_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':stripeChargeId', $data['stripe_charge_id']);
        return $this->db->execute();
    }

    


}

?>