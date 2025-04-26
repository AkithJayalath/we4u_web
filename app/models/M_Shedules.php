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

// for consultants 
// Add these functions to M_Shedules.php

// Get all availability patterns for a consultant
public function getAllAvailabilityPatternsForConsultant($consultantId) {
    $this->db->query('SELECT * FROM co_availability_patterns 
                     WHERE consultant_id = :consultant_id 
                     ORDER BY day_of_week, start_time');
    $this->db->bind(':consultant_id', $consultantId);
    return $this->db->resultSet();
}

// Get all availability instances for a consultant
public function getAllAvailabilityInstancesForConsultant($consultantId) {
    $this->db->query('SELECT * FROM co_availability_instances 
                     WHERE consultant_id = :consultant_id 
                     ORDER BY available_date, start_time');
    $this->db->bind(':consultant_id', $consultantId);
    return $this->db->resultSet();
}

// Create a new availability pattern
public function createAvailabilityPattern($data) {
    $this->db->query('INSERT INTO co_availability_patterns 
                     (consultant_id, day_of_week, start_time, end_time, is_active, start_date, end_date) 
                     VALUES (:consultant_id, :day_of_week, :start_time, :end_time, :is_active, :start_date, :end_date)');
    
    $this->db->bind(':consultant_id', $data['consultant_id']);
    $this->db->bind(':day_of_week', $data['day_of_week']);
    $this->db->bind(':start_time', $data['start_time']);
    $this->db->bind(':end_time', $data['end_time']);
    $this->db->bind(':is_active', $data['is_active'] ?? true);
    $this->db->bind(':start_date', $data['start_date']);
    $this->db->bind(':end_date', $data['end_date']);
    
    return $this->db->execute();
}

// Create a specific availability instance
public function createAvailabilityInstance($data) {
    $this->db->query('INSERT INTO co_availability_instances 
                     (consultant_id, available_date, start_time, end_time) 
                     VALUES (:consultant_id, :available_date, :start_time, :end_time)');
    
    $this->db->bind(':consultant_id', $data['consultant_id']);
    $this->db->bind(':available_date', $data['available_date']);
    $this->db->bind(':start_time', $data['start_time']);
    $this->db->bind(':end_time', $data['end_time']);
    
    return $this->db->execute();
}

// Delete an availability pattern
public function deleteAvailabilityPattern($patternId, $consultantId) {
    $this->db->query('DELETE FROM co_availability_patterns 
                     WHERE id = :id AND consultant_id = :consultant_id');
    
    $this->db->bind(':id', $patternId);
    $this->db->bind(':consultant_id', $consultantId);
    
    return $this->db->execute();
}

// Delete an availability instance
public function deleteAvailabilityInstance($instanceId, $consultantId) {
    $this->db->query('DELETE FROM co_availability_instances 
                     WHERE id = :id AND consultant_id = :consultant_id');
    
    $this->db->bind(':id', $instanceId);
    $this->db->bind(':consultant_id', $consultantId);
    
    return $this->db->execute();
}

// Check if a time slot is already defined in a pattern for a specific day of week
public function isTimeSlotAvailableForPattern($consultantId, $dayOfWeek, $startTime, $endTime, $startDate, $endDate) {
    $this->db->query('SELECT COUNT(*) as count FROM co_availability_patterns 
                     WHERE consultant_id = :consultant_id 
                     AND day_of_week = :day_of_week
                     AND (
                         (start_time < :end_time AND end_time > :start_time)
                         OR (start_time = :start_time OR end_time = :end_time)
                     )
                     AND (
                         (start_date <= :end_date AND end_date >= :start_date)
                     )');
    
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':day_of_week', $dayOfWeek);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    $this->db->bind(':start_date', $startDate);
    $this->db->bind(':end_date', $endDate);
    
    $result = $this->db->single();
    
    // If count is 0, the slot is available
    return ($result->count == 0);
}

// Check if a specific date/time is already defined as an availability instance
public function isTimeSlotAvailableForInstance($consultantId, $date, $startTime, $endTime) {
    $this->db->query('SELECT COUNT(*) as count FROM co_availability_instances 
                     WHERE consultant_id = :consultant_id 
                     AND available_date = :available_date
                     AND (
                         (start_time < :end_time AND end_time > :start_time)
                         OR (start_time = :start_time OR end_time = :end_time)
                     )');
    
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':available_date', $date);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    
    $result = $this->db->single();
    
    // If count is 0, the slot is available
    return ($result->count == 0);
}

// Get Bookings for a consultant (only current and future appointments, excluding cancelled)
public function getConsultantBookings($consultantId) {
    // Get today's date in YYYY-MM-DD format
    $today = date('Y-m-d');
    
    $this->db->query('SELECT request_id, elder_id, consultant_id, appointment_date, 
                      start_time, end_time, status, expected_services 
                      FROM consultantrequests 
                      WHERE consultant_id = :consultant_id 
                      AND appointment_date >= :today
                      AND (status = "pending" OR status = "accepted")
                      ORDER BY appointment_date, start_time');
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':today', $today);
    return $this->db->resultSet();
}




// Get a specific booking by request ID
public function getConsultantBookingById($requestId) {
    $this->db->query('SELECT request_id, elder_id, appointment_date, time_slot, status, 
                     expected_service, additional_notes, payment_details, is_paid 
                     FROM consultantrequests 
                     WHERE request_id = :request_id');
    $this->db->bind(':request_id', $requestId);
    return $this->db->single();
}

// for fetching availability detais for calander view
public function getAvailabilityPatterns($consultantId) {
    $this->db->query('SELECT * FROM co_availability_patterns WHERE consultant_id = :consultant_id AND is_active = 1');
    $this->db->bind(':consultant_id', $consultantId);
    return $this->db->resultSet();
}

public function getAvailabilityInstances($consultantId) {
    $this->db->query('SELECT * FROM co_availability_instances WHERE consultant_id = :consultant_id AND available_date >= CURDATE()');
    $this->db->bind(':consultant_id', $consultantId);
    return $this->db->resultSet();
}

public function getActiveAppointments($consultantId) {
    $this->db->query('SELECT * FROM consultantrequests WHERE consultant_id = :consultant_id AND (status = "pending" OR status = "accepted") AND appointment_date >= CURDATE()');
    $this->db->bind(':consultant_id', $consultantId);
    return $this->db->resultSet();
}


/**
 * Check if a consultant is available at a specific date and time
 */
public function isConsultantAvailable($consultantId, $date, $startTime, $endTime) {
    // Check instances first
    $instanceQuery = "SELECT * FROM co_availability_instances 
                     WHERE consultant_id = :consultant_id 
                     AND available_date = :date
                     AND start_time <= :start_time 
                     AND end_time >= :end_time";
    
    $this->db->query($instanceQuery);
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':date', $date);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    
    $instances = $this->db->resultSet();
    
    if (!empty($instances)) {
        return true;
    }
    
    // Check patterns
    $dayOfWeek = date('w', strtotime($date));
    
    $patternQuery = "SELECT * FROM co_availability_patterns 
                    WHERE consultant_id = :consultant_id 
                    AND day_of_week = :day_of_week
                    AND start_date <= :date 
                    AND end_date >= :date
                    AND start_time <= :start_time 
                    AND end_time >= :end_time
                    AND is_active = 1";
    
    $this->db->query($patternQuery);
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':day_of_week', $dayOfWeek);
    $this->db->bind(':date', $date);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    
    $patterns = $this->db->resultSet();
    
    if (!empty($patterns)) {
        return true;
    }
    
    return false;
}

public function hasExistingBookings($consultantId, $date, $startTime, $endTime) {
    // Check for overlapping appointments in the consultantrequests table
    $this->db->query('SELECT * FROM consultantrequests 
                     WHERE consultant_id = :consultant_id 
                     AND appointment_date = :date
                     AND status IN ("pending", "accepted")
                     AND (
                         (start_time < :end_time AND end_time > :start_time)
                     )');
    
    $this->db->bind(':consultant_id', $consultantId);
    $this->db->bind(':date', $date);
    $this->db->bind(':start_time', $startTime);
    $this->db->bind(':end_time', $endTime);
    
    $bookings = $this->db->resultSet();
    // If we found any bookings, the time slot is already booked
    return !empty($bookings);
}










}

?>