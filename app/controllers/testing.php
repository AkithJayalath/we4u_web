<?php
    class testing extends controller{
        
        private $sheduleModel;

        public function __construct(){
            $this->sheduleModel = $this->model('M_Shedules');
        }

        public function index(){
            $id = 26;
            $date = '2025-04-10 00:00:00';

            $shedules = $this->sheduleModel->getAllLongShedulesForCaregiverByDate($id, $date);


            $data = [
                'shedules' => $shedules,
                'date' => $date
            ];
            
            $this->view('testing', $data);
        }

        public function getSchedules() {
            $id = 26;
            $shedules = $this->sheduleModel->getAllLongShedulesForCaregiver($id);

            $data = [
                'shedules' => $shedules,
            ];
            
            $this->view('testing', $data);
        }

        public function createSchedule(){
            // $id = $_SESSION['user_id'];
            $id = 27;
            $date = '2025-04-16';
            $shift = 'day';
            $is_active = $this->sheduleModel->getAllLongShedulesForCaregiverByDate($id, $date);
            if (empty($is_active)){
                $this->sheduleModel->createSchedule($id, $date, $shift);
                echo "Schedule created successfully.";
            } else {
                echo "Schedule already exists for this date.";
            }
            
        }

        public function checkDateRangeAvailability() {
            // Get caregiver ID (hardcoded for testing)
            $caregiverId = 26;
            
            // Manually set start and end dates for testing
            $startDate = '2025-04-12 00:00:00';
            $endDate = '2025-04-20 00:00:00';
            
            // Get schedules for this caregiver within the date range
            $schedules = $this->sheduleModel->getAllLongShedulesForCaregiverByDateAndTime($caregiverId, $startDate, $endDate);
            
            // Prepare data for the view
            $data = [
                'caregiver_id' => $caregiverId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'schedules' => $schedules,
                'is_available' => empty($schedules) ? 'Available' : 'Not Available'
            ];
            
            // Load the view with the data
            $this->view('testing', $data);
        }
        
        


    }
?>