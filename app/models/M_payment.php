<?php

class M_payment{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function insertpayment($data) {
        $this->db->query('INSERT INTO payment (service_type,payer_id,payer_name,receiver_id,receiver_name,amount_paid,payment_status)
                      VALUES (:service_type, :payer_id, :payer_name, :receiver_id, :receiver_name, :amount_paid, :payment_status)');

        $this->db->bind(':service_type', $data['service_type']);
        $this->db->bind(':payer_id', $data['payer_id']);
        $this->db->bind(':payer_name', $data['payer_name']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':receiver_name', $data['receiver_name']);
        $this->db->bind(':amount_paid', $data['amount_paid']);
        $this->db->bind(':payment_status', $data['payment_status']);

        return $this->db->execute();
        
    }
}

?>