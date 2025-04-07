<?php

class M_Shedules{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllShedulesForCaregiver($caregiverID){
        $this->db->query('SELECT * From cg_shedules WHERE provider_id = :caregiverID');
        $this->db->bind(':caregiverID', $caregiverID);
        return $this->db->resultSet();
    }

    public function getAllLongShedulesForCaregiver($caregiverID){
        $this->db->query('SELECT * From cg_long_bookings WHERE caregiver_id = :caregiverID');
        $this->db->bind(':caregiverID', $caregiverID);
        return $this->db->resultSet();
    }

    public function getAllShedulesForCaregiverByDate($caregiverID, $date){
        $this->db->query('SELECT * From cg_shedules WHERE provider_id = :caregiverID AND sheduled_date = :date');
        $this->db->bind(':caregiverID', $caregiverID);
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

// only checking the date without the time this can be use for fetching what are the user having as a schedule for the day along with the short ones
    public function getAllLongShedulesForCaregiverByDate($caregiverID, $date){
        $this->db->query('SELECT * FROM cg_long_bookings WHERE caregiver_id = :caregiverID AND DATE(:date) BETWEEN DATE(start_date_time) AND DATE(end_date_time)');
        $this->db->bind(':caregiverID', $caregiverID);
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    // this is for checking the date of the long shedules but when it got a date and time range- this is for when user is filtering the shedules by date and time range
    public function getAllLongShedulesForCaregiverByDateAndTime($caregiverID, $startDate, $endDate){
        $this->db->query('SELECT * FROM cg_long_bookings WHERE caregiver_id = :caregiverID AND start_date_time <= :endDate AND end_date_time >= :startDate');
        $this->db->bind(':caregiverID', $caregiverID);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        return $this->db->resultSet();
    }

    public function getAllShedulesForCaregiverByDateAndShift($caregiverID, $date, $shift = null){
        $query = 'SELECT * From cg_shedules WHERE provider_id = :caregiverID AND sheduled_date = :date';
        
        if ($shift !== null) {
            $query .= ' AND shift = :shift';
        }
        
        $this->db->query($query);
        $this->db->bind(':caregiverID', $caregiverID);
        $this->db->bind(':date', $date);
        
        if ($shift !== null) {
            $this->db->bind(':shift', $shift);
        }
        
        return $this->db->resultSet();
    }

    public function createSchedule($caregiverID, $date, $shift){
        $this->db->query('INSERT INTO cg_shedules (provider_id, sheduled_date, shift) VALUES (:caregiverID, :date, :shift)');
        $this->db->bind(':caregiverID', $caregiverID);
        $this->db->bind(':date', $date);
        $this->db->bind(':shift', $shift);
        return $this->db->execute();
    }

}

?>