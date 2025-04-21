<?php

class M_Shedules{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // caregiver shedule endpoints
    public function createShortShedule($data){
        $this->db->query('INSERT INTO cg_shedules (caregiver_id, sheduled_date, shift, request_id ,status) VALUES (:caregiver_id, :sheduled_date, :shift, :request_id, :status)');
        $this->db->bind(':caregiver_id', $data['caregiver_id']);
        $this->db->bind(':sheduled_date', $data['sheduled_date']);
        $this->db->bind(':shift', $data['shift']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':request_id', $data['request_id']);
        return $this->db->execute();
    }

    public function createLongShedule($data){
        $this->db->query('INSERT INTO cg_long_shedules (caregiver_id, start_date, end_date, requset_id, status) VALUES (:caregiver_id, :start_date, :end_date, :request_id, :status)');
        $this->db->bind(':caregiver_id', $data['caregiver_id']);
        $this->db->bind(':start_date', $data['from_date']);
        $this->db->bind(':end_date', $data['to_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':request_id', $data['request_id']);
        return $this->db->execute();
    }


    public function getAllShortShedulesForCaregiver($caregiverID){
        $this->db->query('SELECT * From cg_shedules WHERE caregiver_id = :caregiverID');
        $this->db->bind(':caregiverID', $caregiverID);
        return $this->db->resultSet();
    }

    public function getAllLongShedulesForCaregiver($caregiverID){
        $this->db->query('SELECT * From cg_long_shedules WHERE caregiver_id = :caregiverID');
        $this->db->bind(':caregiverID', $caregiverID);
        return $this->db->resultSet();
    }


    // check if the date is already in the database
    // first for timeslots
    public function isTimeSlotAvailable($sheduled_date, $shift, $caregiver_id){
        // First check if this date falls within any long schedule for this caregiver
        $this->db->query('SELECT COUNT(*) as count FROM cg_long_shedules 
                          WHERE caregiver_id = :caregiver_id 
                          AND :sheduled_date BETWEEN start_date AND end_date');
        $this->db->bind(':caregiver_id', $caregiver_id);
        $this->db->bind(':sheduled_date', $sheduled_date);
        $result = $this->db->single();
        
        // If the date falls within a long schedule, the slot is not available
        if ($result->count > 0) {
            return false;
        }
        
        // Then check if there's a specific short schedule for this date and shift
        $this->db->query('SELECT COUNT(*) as count FROM cg_shedules 
                          WHERE caregiver_id = :caregiver_id 
                          AND sheduled_date = :sheduled_date 
                          AND shift = :shift');
        $this->db->bind(':caregiver_id', $caregiver_id);
        $this->db->bind(':sheduled_date', $sheduled_date);
        $this->db->bind(':shift', $shift);
        $result = $this->db->single();
        
        // If count is 0, the slot is available
        return ($result->count == 0);
    }
    
    // then for long schedules
    public function isDateRangeAvailable($start_date, $end_date, $caregiver_id){
        // Check if this range overlaps with any existing long schedule
        $this->db->query('SELECT COUNT(*) as count FROM cg_long_shedules 
                          WHERE caregiver_id = :caregiver_id 
                          AND (
                              (start_date <= :end_date AND end_date >= :start_date) OR
                              (start_date >= :start_date AND start_date <= :end_date) OR
                              (end_date >= :start_date AND end_date <= :end_date)
                          )');
        $this->db->bind(':caregiver_id', $caregiver_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $result = $this->db->single();
        
        // If there's an overlap with a long schedule, the range is not available
        if ($result->count > 0) {
            return false;
        }
        
        // Check if there are any short schedules within this date range
        $this->db->query('SELECT COUNT(*) as count FROM cg_shedules 
                          WHERE caregiver_id = :caregiver_id 
                          AND sheduled_date BETWEEN :start_date AND :end_date');
        $this->db->bind(':caregiver_id', $caregiver_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $result = $this->db->single();
        
        // If there are short schedules within this range, it's not available
        return ($result->count == 0);
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

    //end of caregiver shedule endpoints

    // consultant shedule endpoints
    public function getAllShedulesForConsultant($consultantID){
        $this->db->query('SELECT * From co_shedules WHERE consultant_id = :consultantID');
        $this->db->bind(':consultantID', $consultantID);
        return $this->db->resultSet();
    }

    public function getAllShedulesForConsultantByDate($consultantID, $date){
        $this->db->query('SELECT * FROM co_shedules WHERE consultant_id = :consultantID AND sheduled_date = :date');
        $this->db->bind(':consultantID', $consultantID);
    }
    // end of consultant shedule endpoints

}

?>