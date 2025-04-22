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
        $this->db->query('INSERT INTO cg_long_shedules (caregiver_id, start_date, end_date, request_id, status) VALUES (:caregiver_id, :start_date, :end_date, :request_id, :status)');
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

public function deleteShortSchedule($scheduleId, $caregiverId) {
    // First check if this schedule belongs to the caregiver and is an unavailability
    $this->db->query('SELECT * FROM cg_shedules 
                     WHERE id = :id 
                     AND caregiver_id = :caregiver_id 
                     AND status = :status');
    $this->db->bind(':id', $scheduleId);
    $this->db->bind(':caregiver_id', $caregiverId);
    $this->db->bind(':status', 'unavailable');
    
    $schedule = $this->db->single();
    
    // If no matching schedule found or it's not an unavailability, return false
    if (!$schedule) {
        return false;
    }
    
    // Delete the schedule
    $this->db->query('DELETE FROM cg_shedules 
                     WHERE id = :id 
                     AND caregiver_id = :caregiver_id 
                     AND status = :status');
    $this->db->bind(':id', $scheduleId);
    $this->db->bind(':caregiver_id', $caregiverId);
    $this->db->bind(':status', 'unavailable');
    
    return $this->db->execute();
}

public function deleteLongSchedule($scheduleId, $caregiverId) {
    // First check if this schedule belongs to the caregiver and is an unavailability
    $this->db->query('SELECT * FROM cg_long_shedules 
                     WHERE id = :id 
                     AND caregiver_id = :caregiver_id 
                     AND status = :status');
    $this->db->bind(':id', $scheduleId);
    $this->db->bind(':caregiver_id', $caregiverId);
    $this->db->bind(':status', 'unavailable');
    
    $schedule = $this->db->single();
    
    // If no matching schedule found or it's not an unavailability, return false
    if (!$schedule) {
        return false;
    }
    
    // Delete the schedule
    $this->db->query('DELETE FROM cg_long_shedules 
                     WHERE id = :id 
                     AND caregiver_id = :caregiver_id 
                     AND status = :status');
    $this->db->bind(':id', $scheduleId);
    $this->db->bind(':caregiver_id', $caregiverId);
    $this->db->bind(':status', 'unavailable');
    
    return $this->db->execute();
}

public function updateScheduleStatusByRequestId($request_id, $status) {
    // Update status in short schedules
    $this->db->query('UPDATE cg_shedules SET status = :status WHERE request_id = :request_id');
    $this->db->bind(':status', $status);
    $this->db->bind(':request_id', $request_id);
    $shortResult = $this->db->execute();
    
    // Update status in long schedules
    $this->db->query('UPDATE cg_long_shedules SET status = :status WHERE request_id = :request_id');
    $this->db->bind(':status', $status);
    $this->db->bind(':request_id', $request_id);
    $longResult = $this->db->execute();
    
    // Return true if either query was successful (meaning records were found and updated)
    return $shortResult || $longResult;
}

public function deleteSchedulesByRequestId($request_id) {
    // Delete entries from short schedules table
    $this->db->query('DELETE FROM cg_shedules WHERE request_id = :request_id');
    $this->db->bind(':request_id', $request_id);
    $shortResult = $this->db->execute();
    
    // Delete entries from long schedules table
    $this->db->query('DELETE FROM cg_long_shedules WHERE request_id = :request_id');
    $this->db->bind(':request_id', $request_id);
    $longResult = $this->db->execute();
    
    // Return true if either deletion was successful
    return $shortResult || $longResult;
}




}

?>