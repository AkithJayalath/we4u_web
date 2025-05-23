<?php

class careseeker extends controller
{
    private $careseekersModel;
    private $scheduleModel;
    private $caregiversModel;
    public function __construct()
    {
        if (!$_SESSION['user_id']) {
            redirect('users/login');
        } else {

            $this->careseekersModel = $this->model('M_Careseekers');
            $this->scheduleModel = $this->model('M_Shedules');
            $this->caregiversModel = $this->model('M_Caregivers');
        }
    }

    public function index()
    {
        $this->showElderProfiles();
    }


    public function createProfile()
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        $careseeker_id = $_SESSION['user_id'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

           
            $data = [
                'careseeker_id' =>  $careseeker_id,
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'last_name' => trim($_POST['last_name']),
                'relationship_to_careseeker' => trim($_POST['relationship_to_careseeker']),
                'age' => trim($_POST['age']),
                'gender' => trim($_POST['gender']),
                'weight' => trim($_POST['weight']),
                'height' => trim($_POST['height']),
                'blood_pressure' => trim($_POST['blood_pressure']),
                'emergency_contact' => trim($_POST['emergency_contact']),
                'hbc'=>trim($_POST['hbc']),
                'chronic_disease' => trim($_POST['chronic_disease']),
                'current_health_issues' => trim($_POST['current_health_issues']),
                'allergies' => trim($_POST['allergies']),
                'health_barriers' => trim($_POST['health_barriers']),
                'surgical_history' => trim($_POST['surgical_history']),
                'family_diseases' => trim($_POST['family_diseases']),
                'current_medications' => trim($_POST['current_medications']),
                'special_needs' => trim($_POST['special_needs']),
                'dietary_restrictions' => trim($_POST['dietary_restrictions']),
                'profile_picture' => $_FILES['profile_picture'],
                'profile_picture_name' => time() . '_' . $_FILES['profile_picture']['name'], 

                
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];

            
            if (empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter the first name.';
            }

            if (empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter the last name.';
            }

            if (empty($data['age'])) {
                $data['age_err'] = 'Please enter the age.';
            } elseif (!is_numeric($data['age']) || $data['age'] <= 0) {
                $data['age_err'] = 'Age must be a positive number.';
            }



            if (empty($data['emergency_contact'])) {
                $data['emergency_contact_err'] = 'Please enter an emergency contact.';
            } elseif (!preg_match('/^[0-9]{10}$/', $data['emergency_contact'])) {
                $data['emergency_contact_err'] = 'Emergency contact must be a 10-digit number.';
            }

            if (empty($data['relationship_to_careseeker'])) {
                $data['relationship_to_careseeker_err'] = 'Please specify the relationship to the careseeker.';
            }

            
            if (!empty($data['profile_picture']['name'])) {
                $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];

               
                if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                    $data['profile_pic'] = $profileImagePath; 
                } else {
                    $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                }
            } else {
                $data['profile_picture_name'] = null;
            }


           
            if (
                empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err'])
                && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err']) &&
                empty($data['profile_picture_err'])
            ) {

                
                if ($this->careseekersModel->createElderProfile($data)) {
                    
                    createNotification($careseeker_id, 'Your Elder Profile has been Created.', false);
                   
                    flash('success', 'Elder profile created successfully.');
                    redirect('careseeker/showElderProfiles'); 
                } else {
                    echo "Error creating profile!";
                }
            } else {
                
                $this->view('careseeker/v_create', $data);
            }
        } else {
           
            $data = [
                'first_name' => '',
                'middle_name' => '',
                'last_name' => '',
                'relationship_to_careseeker' => '',
                'age' => '',
                'gender' => '',
                'weight' => '',
                'height' => '',
                'blood_pressure' => '',
                'emergency_contact' => '',
                'hbc'=>'',
                'chronic_disease' => '',
                'current_health_issues' => '',
                'allergies' => '',
                'health_barriers' => '',
                'surgical_history' => '',
                'family_diseases' => '',
                'current_medications' => '',
                'special_needs' => '',
                'dietary_restrictions' => '',
                'profile_picture' => '',
                'profile_picture_name' => '',

                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];
            $this->view('careseeker/v_create', $data);
        }
    }

    public function showElderProfiles()
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        $careseeker_id = $_SESSION['user_id'] ?? '';

        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

       
        $data = [
            'elders' => $elders
        ];

        $this->view('careseeker/v_createProfile', $data);
    }

    public function showCaregiverRequestForm($caregiver_id)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
       
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

       
        $careseeker_id = $_SESSION['user_id'];

       
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);
        $careseekerProfile = $this->careseekersModel->showCareseekerProfile($careseeker_id);
        $caregiverProfile = $this->careseekersModel->showCaregiverProfile($caregiver_id);

        $dob = new DateTime($caregiverProfile->date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        $data = [
            'elders' => $elders,
            'careseeker' => $careseekerProfile,
            'caregiver' => $caregiverProfile,
            'age' => $age,
            'caregiver_id' => $caregiver_id,
            'elder_profile' => '',
            'duration_type' => '',
            'from_date' => '',
            'to_date' => '',
            'timeslot' => [],
            'expected_services' => '',
            'additional_notes' => '',
            'error' => ''
        ];

        $this->view('careseeker/v_requestCaregiver', $data);
    }

    public function showConsultantRequestForm($consultant_id)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        
        $careseeker_id = $_SESSION['user_id'];

        
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);
        $careseekerProfile = $this->careseekersModel->showCareseekerProfile($careseeker_id);
        $consultantProfile = $this->careseekersModel->showConsultantProfile($consultant_id);

        $dob = new DateTime($consultantProfile->date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        $data = [
            'elders' => $elders,
            'careseeker' => $careseekerProfile,
            'consultant' => $consultantProfile,
            'age' => $age,
            'consultant_id' => $consultant_id,
            'elder_profile' => '',
            'from_time' => '',
            'to_time' => '',
            'expected_services' => '',
            'additional_notes' => '',
            'error' => ''
        ];

        $this->view('careseeker/v_requestConsultant', $data);
    }

    public function calculateAge($dob)
    {
        $birthDate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthDate->diff($today)->y;
        return $age;
    }

    public function requestCaregiver($caregiver_id)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        
        $careseeker_id = $_SESSION['user_id'];

        
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        
        $caregiver = $this->careseekersModel->showCaregiverProfile($caregiver_id);
       
        if (!$caregiver) {
            flash('error', 'Caregiver not found');
            redirect('careseeker/ViewRequests');
        }
        $caregiverEmail= $caregiver->email;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            
            $data = [
                'elders' => $elders,
                'caregiver' => $caregiver,
                'age' => $this->calculateAge($caregiver->date_of_birth),
                'caregiver_id' => $caregiver_id,
                'careseeker_id' => $careseeker_id,
                'elder_profile' => isset($_POST['elder_profile']) ? trim($_POST['elder_profile']) : '',
                'duration_type' => isset($_POST['duration-type']) ? trim($_POST['duration-type']) : '',
                'from_date' => isset($_POST['from_date']) ? trim($_POST['from_date']) : null,
                'from_date_short' => isset($_POST['from_date_short']) ? trim($_POST['from_date_short']) : null,
                'to_date' => isset($_POST['to_date']) ? trim($_POST['to_date']) : null,
                'time_slots' => isset($_POST['timeslot']) ? $_POST['timeslot'] : [],
                'total_payment' => isset($_POST['total_payment']) ? (int)trim($_POST['total_payment']) : 0,
                'service_address' => isset($_POST['service_address']) ? trim($_POST['service_address']) : '',
                'expected_services' => isset($_POST['expected_services']) ? trim($_POST['expected_services']) : '',
                'additional_notes' => isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '',
                'error' => ''
            ];

           
            if (empty($data['elder_profile'])) {
                $data['error'] = 'Please select an elder profile';
            }
           
            elseif (empty($data['duration_type'])) {
                $data['error'] = 'Please select a duration type';
            }
           
            elseif ($data['duration_type'] === 'long-term') {
               
                if (empty($data['from_date'])) {
                    $data['error'] = 'Please select a start date for long-term care';
                } elseif (empty($data['to_date'])) {
                    $data['error'] = 'Please select an end date for long-term care';
                } else {
                  
                    if ($caregiver->caregiver_type !== 'long' && $caregiver->caregiver_type !== 'both') {
                        $data['error'] = 'This caregiver does not offer long-term care services';
                    } else {
                       
                        $from_date = new DateTime($data['from_date']);
                        $to_date = new DateTime($data['to_date']);
                        $today = new DateTime();

                       
                        $tomorrow = new DateTime();
                        $tomorrow->setTime(0, 0, 0);
                        $tomorrow->modify('+1 day');

                        $from_date_normalized = clone $from_date;
                        $from_date_normalized->setTime(0, 0, 0);

                        if ($from_date_normalized < $tomorrow) {
                            $data['error'] = 'Start date must be at least tomorrow';
                        }
                        
                        elseif ($to_date < $from_date) {
                            $data['error'] = 'End date must be after or equal to start date';
                        } else {
                           
                            $interval = $from_date->diff($to_date);
                            $days = $interval->days + 1; 
                           
                            if ($days > 5) {
                                $data['error'] = 'Maximum care duration is 5 days';
                            }

                          
                            if (empty($data['error'])) {
                               
                                $fromDate = date('Y-m-d', strtotime($data['from_date']));
                                $toDate = date('Y-m-d', strtotime($data['to_date']));

                                $isAvailable = $this->scheduleModel->isDateRangeAvailable(
                                    $fromDate,
                                    $toDate,
                                    $data['caregiver_id']
                                );

                                if (!$isAvailable) {
                                    $data['error'] = 'The selected date range overlaps with existing bookings. Please choose another date range.';
                                }
                            }
                        }
                    }
                }
            } elseif ($data['duration_type'] === 'short-term') {
               
                if (empty($data['from_date_short'])) {
                    $data['error'] = 'Please select a date for short-term care';
                }
              
                elseif (empty($data['time_slots'])) {
                    $data['error'] = 'Please select at least one time slot';
                } else {
                    
                    if ($caregiver->caregiver_type !== 'short' && $caregiver->caregiver_type !== 'both') {
                        $data['error'] = 'This caregiver does not offer short-term care services';
                    } else {
                       
                        $from_date_short = new DateTime($data['from_date_short']);
                        $from_date_short->setTime(0, 0, 0);

                        $tomorrow = new DateTime();
                        $tomorrow->setTime(0, 0, 0);
                        $tomorrow->modify('+1 day');


                        if ($from_date_short < $tomorrow) {
                            $data['error'] = 'Date for short-term care must be at least tomorrow';
                        }

                        
                        $fullDaySelected = in_array('full-day', $data['time_slots']);
                        $otherSlotsSelected = array_diff($data['time_slots'], ['full-day']);

                        if ($fullDaySelected && !empty($otherSlotsSelected)) {
                            $data['error'] = 'Cannot select Full Day and other time slots together';
                        }

                        
                        if (empty($data['error'])) {
                            $allSlotsAvailable = true;

                           
                            $slotMapping = [
                                'morning' => 'morning',
                                'evening' => 'evening',
                                'overnight' => 'overnight',
                                'full-day' => 'fullday'
                            ];

                            foreach ($data['time_slots'] as $timeSlot) {
                               
                                $dbSlot = isset($slotMapping[$timeSlot]) ? $slotMapping[$timeSlot] : $timeSlot;

                                $isAvailable = $this->scheduleModel->isTimeSlotAvailable(
                                    $data['from_date_short'],
                                    $dbSlot,
                                    $data['caregiver_id']
                                );

                                if (!$isAvailable) {
                                    $allSlotsAvailable = false;
                                    break;
                                }
                            }

                            if (!$allSlotsAvailable) {
                                $data['error'] = 'One or more selected time slots are already booked or fall within a long-term booking period. Please choose different time slots.';
                            }
                        }
                    }
                }
            }

          
            if (empty($data['error']) && $data['total_payment'] <= 0) {
                $data['error'] = 'Invalid payment amount';
            }

            
            if (empty($data['error']) && empty($data['service_address'])) {
                $data['error'] = 'Please enter a service address';
            }

           
            if (empty($data['error'])) {
                $requestData = [
                    'careseeker_id' => $careseeker_id,
                    'elder_id' => $data['elder_profile'],
                    'caregiver_id' => $data['caregiver_id'],
                    'duration_type' => $data['duration_type'],
                    'from_date' => $data['duration_type'] === 'long-term' ? $data['from_date'] : null,
                    'from_date_short' => $data['duration_type'] === 'short-term' ? $data['from_date_short'] : null,
                    'to_date' => $data['duration_type'] === 'long-term' ? $data['to_date'] : null,
                    'time_slots' => $data['duration_type'] === 'short-term' ? json_encode($data['time_slots']) : null,
                    'total_payment' => $data['total_payment'],
                    'service_address' => $data['service_address'],
                    'expected_services' => $data['expected_services'],
                    'additional_notes' => $data['additional_notes'],
                    'status' => 'pending'
                ];

               
                $result = $this->careseekersModel->sendCareRequest($requestData);
                if ($result['success']) {

                    flash('success', 'Care request sent successfully');
                   
                    $emailBody = '<h1>New Request !</h1>
                    <p>You have a new request.Please visit the website for more details</p>';
                
                   $this->sendEmail(
                    $caregiverEmail,
                    'New Request - We4u',
                    $emailBody
                    );

                   
                    createNotification($caregiver_id, 'A new care request has been sent to you. Please review the details and respond.', false);


                   
                    if ($data['duration_type'] === 'short-term') {
                        
                        foreach ($data['time_slots'] as $time_slot) {
                            $sheduleData = [
                                'caregiver_id' => $data['caregiver_id'],
                                'sheduled_date' => $data['from_date_short'],
                                'shift' => $time_slot,
                                'status' => 'pending',
                                'request_id' => $result['id']
                            ];
                            $this->scheduleModel->createShortShedule($sheduleData);
                        }
                    } elseif ($data['duration_type'] === 'long-term') {
                        $sheduleData = [
                            'caregiver_id' => $data['caregiver_id'],
                            'from_date' => $data['from_date'],
                            'to_date' => $data['to_date'],
                            'status' => 'pending',
                            'request_id' => $result['id']
                        ];
                        $this->scheduleModel->createLongShedule($sheduleData);
                    }

                    redirect('careseeker/viewRequests');
                } else {
                    $data['error'] = 'Failed to send care request. Please try again.';
                    $this->view('careseeker/v_requestCaregiver', $data);
                }
            } else {
                
                $data['careseeker'] = $this->careseekersModel->showCareseekerProfile($careseeker_id);
                $this->view('careseeker/v_requestCaregiver', $data);
            }
        } else {
           
            redirect('careseeker/showCaregiverRequestForm/' . $caregiver_id);
        }
    }



    public function requestConsultant($consultant_id)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        $careseeker_id = $_SESSION['user_id'];
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

       
        $consultant = $this->careseekersModel->showConsultantProfile($consultant_id);
        if (!$consultant) {
            flash('error', 'Consultant not found');
            redirect('careseeker/viewConsultantRequests');
        }
        $consultantEmail = $consultant->email;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'elders' => $elders,
                'consultant' => $consultant,
                'consultant_id' => $consultant_id,
                'careseeker_id' => $careseeker_id,
                'age' => $this->calculateAge($consultant->date_of_birth),
                'elder_profile' => isset($_POST['elder_profile']) ? trim($_POST['elder_profile']) : '',
                'appointment_date' => isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '',
                'from_time' => isset($_POST['from_time']) ? trim($_POST['from_time']) : '',
                'to_time' => isset($_POST['to_time']) ? trim($_POST['to_time']) : '',
                'total_amount' => isset($_POST['total_amount']) ? trim($_POST['total_amount']) : '',
                'expected_services' => isset($_POST['expected_services']) ? trim($_POST['expected_services']) : '',
                'additional_notes' => isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '',
                'error' => ''
            ];

        
        if (empty($data['elder_profile'])) {
            $data['error'] = 'Please select an elder profile';
        } elseif (empty($data['appointment_date'])) {
            $data['error'] = 'Please select an appointment date';
        } else {
            
            $appointment_date = new DateTime($data['appointment_date']);
            $today = new DateTime();
            if ($appointment_date < $today) {
                $data['error'] = 'Appointment must be at least tomorrow. Please select a different time slot';
            }       
            if (empty($data['error'])) { 
              
                $startTime = $data['from_time'] . ':00';
                $endTime = $data['to_time'] . ':00';
                
              
                $isAvailable = $this->scheduleModel->isConsultantAvailable(
                    $consultant_id, 
                    $data['appointment_date'], 
                    $startTime, 
                    $endTime
                );
    
                if (!$isAvailable) {
                    $data['error'] = 'The consultant is not available at this time. Please select a different time slot.';
                } else {
                   
                    $hasBookings = $this->scheduleModel->hasExistingBookings(
                        $consultant_id, 
                        $data['appointment_date'], 
                        $startTime,
                        $endTime
                    );
                    
                    if ($hasBookings) {
                        $data['error'] = 'This time slot is already booked. Please select a different time slot.';
                    }
                }
            }
        }

            if (empty($data['error']) && (empty($data['from_time']) || empty($data['to_time']))) {
                $data['error'] = 'Please select both start and end times';
            } elseif (empty($data['error']) && $data['from_time'] >= $data['to_time']) {
                $data['error'] = 'End time must be after start time';
            }

            if (empty($data['error']) && (empty($data['total_amount']) || !is_numeric($data['total_amount']) || $data['total_amount'] <= 0)) {
                $data['error'] = 'Invalid payment amount';
            }

            if (empty($data['error'])) {
               
                $startTime = $data['from_time'] . ':00';
                $endTime = $data['to_time'] . ':00';
                
                
                $isAvailable = $this->scheduleModel->isConsultantAvailable(
                    $consultant_id, 
                    $data['appointment_date'], 
                    $startTime, 
                    $endTime
                );
    
                if (!$isAvailable) {
                    $data['error'] = 'The consultant is not available at this time. Please select a different time slot.';
                } else {
                   
                    $hasBookings = $this->scheduleModel->hasExistingBookings(
                        $consultant_id, 
                        $data['appointment_date'], 
                        $startTime,
                        $endTime
                    );
                    
                    if ($hasBookings) {
                        $data['error'] = 'This time slot is already booked. Please select a different time slot.';
                    }
                }
            }

           
            if (empty($data['error'])) {
                

                $requestData = [
                    'careseeker_id' => $careseeker_id,
                    'elder_id' => $data['elder_profile'],
                    'consultant_id' => $consultant_id,
                    'appointment_date' => $data['appointment_date'],
                    'from_time' => $data['from_time'],
                    'to_time' => $data['to_time'],
                    'expected_services' => $data['expected_services'],
                    'additional_notes' => $data['additional_notes'],
                    'total_amount' => $data['total_amount'],
                    'status' => 'pending'
                ];

                if ($this->careseekersModel->sendConsultantRequest($requestData)) {
                    
                    $emailBody = '<h1>New Request !</h1>
                        <p>You have a new request.Please visit the website for more details</p>';
                                    
                   $this->sendEmail(
                        $consultantEmail,
                        'New Request - We4u',
                        $emailBody
                    );

                  
                    createNotification($consultant_id, 'A new care request has been sent to you. Please review the details and respond.', false);

                    flash('success', 'Consultant request sent successfully');
                    redirect('careseeker/viewRequests');
                } else {
                    $data['error'] = 'Failed to send request. Please try again.';
                    $this->view('careseeker/v_requestConsultant', $data);
                }
            } else {
                $data['careseeker'] = $this->careseekersModel->showCareseekerProfile($careseeker_id);
                $this->view('careseeker/v_requestConsultant', $data);
            }
        } else {
            
            redirect('careseeker/showConsultantRequestForm/' . $consultant_id);
        }
    }





    public function deleteElderProfile($elderId)
    {   
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        
        if ($this->careseekersModel->deleteElderProfile($elderId)) {
            createNotification($_SESSION['user_id'] , 'Your Elder Profile has been Deleted.', false);
            echo 'Elder profile deleted successfully.';
        } else {
            echo 'Failed to delete elder profile.';
        }

       
        redirect('careseeker/showElderProfiles');
    }



    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }


    public function viewElderProfile()
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isLoggedIn()) {
            $elderId = $_POST['elder_id'];
            $elderData = $this->careseekersModel->getElderProfilesData($elderId);

            $data = ['elderData' => $elderData];

            if ($elderData) {
                $this->view('careseeker/v_viewElderProfile', $data);
            } else {
                echo "Profile not found.";
            }
        } else {
            redirect('careseeker/showElderProfiles');
        }
    }

    public function editElderProfile()
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
      
        if (!$this->isLoggedIn()) {
            redirect('users/login');
        }

        
        $careseeker_id = $_SESSION['user_id'] ?? '';

       
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['elder_id'])) {
           
            $elderID = $_GET['elder_id'];

           
            $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);

            
            if (!$elderProfile) {
                redirect('careseeker/showElderProfiles');
            }

            
            $data = [
                'elder_id' => $elderID,
                'careseeker_id' => $careseeker_id,
                'first_name' => $elderProfile->first_name,
                'middle_name' => $elderProfile->middle_name,
                'last_name' => $elderProfile->last_name,
                'relationship_to_careseeker' => $elderProfile->relationship_to_careseeker,
                'age' => $elderProfile->age,
                'gender' => $elderProfile->gender,
                'weight' => $elderProfile->weight,
                'height' => $elderProfile->height,
                'blood_pressure' => $elderProfile->blood_pressure,
                'emergency_contact' => $elderProfile->emergency_contact,
                'chronic_disease' => $elderProfile->chronic_disease,
                'current_health_issues' => $elderProfile->current_health_issues,
                'allergies' => $elderProfile->allergies,
                'health_barriers' => $elderProfile->health_barriers,
                'surgical_history' => $elderProfile->surgical_history,
                'family_diseases' => $elderProfile->family_diseases,
                'current_medications' => $elderProfile->current_medications,
                'special_needs' => $elderProfile->special_needs,
                'dietary_restrictions' => $elderProfile->dietary_restrictions,
                'profile_picture' => $elderProfile->profile_picture,
                'profile_picture_name' => '', 
                
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];


            
            $this->view('careseeker/v_editElderProfile', $data);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

           
            $elderID = $_POST['elder_id']; 
            $careseeker_id = $_SESSION['user_id'] ?? ''; 

            $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);
            $currentProfilePicture = $elderProfile->profile_picture;
           
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            
            $data = [
                'elder_id' => $elderID,
                'careseeker_id' => $careseeker_id,
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'last_name' => trim($_POST['last_name']),
                'relationship_to_careseeker' => trim($_POST['relationship_to_careseeker']),
                'age' => trim($_POST['age']),
                'gender' => trim($_POST['gender']),
                'weight' => trim($_POST['weight']),
                'height' => trim($_POST['height']),
                'blood_pressure' => trim($_POST['blood_pressure']),
                'emergency_contact' => trim($_POST['emergency_contact']),
                'chronic_disease' => trim($_POST['chronic_disease']),
                'current_health_issues' => trim($_POST['current_health_issues']),
                'allergies' => trim($_POST['allergies']),
                'health_barriers' => trim($_POST['health_barriers']),
                'surgical_history' => trim($_POST['surgical_history']),
                'family_diseases' => trim($_POST['family_diseases']),
                'current_medications' => trim($_POST['current_medications']),
                'special_needs' => trim($_POST['special_needs']),
                'dietary_restrictions' => trim($_POST['dietary_restrictions']),
                'profile_picture' => $_FILES['profile_picture'],
                'profile_picture_name' => time() . '_' . $_FILES['profile_picture']['name'], 
              
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];



           
            if (empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter the first name.';
            }

            if (empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter the last name.';
            }

            if (empty($data['age'])) {
                $data['age_err'] = 'Please enter the age.';
            } elseif (!is_numeric($data['age']) || $data['age'] <= 0) {
                $data['age_err'] = 'Age must be a positive number.';
            }

            if (empty($data['emergency_contact'])) {
                $data['emergency_contact_err'] = 'Please enter an emergency contact.';
            } elseif (!preg_match('/^[0-9]{10}$/', $data['emergency_contact'])) {
                $data['emergency_contact_err'] = 'Emergency contact must be a 10-digit number.';
            }

            if (empty($data['relationship_to_careseeker'])) {
                $data['relationship_to_careseeker_err'] = 'Please specify the relationship to the careseeker.';
            }

            
            if (!empty($data['profile_picture']['name'])) { 
                $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];

                if (!empty($currentProfilePicture)) {
                  
                    $oldImagePath = PUBROOT . '/images/profile_imgs/' . $currentProfilePicture;
                    if (updateImage($oldImagePath, $data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs/')) {
                       
                    } else {
                        $data['profile_picture_err'] = 'Failed to update profile picture.';
                    }
                } else {
                    
                    if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                        
                    } else {
                        $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                    }
                }
            } else {
               
                $data['profile_picture_name'] = $currentProfilePicture;
            }
           
            if (
                empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err'])
                && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err'])
                && empty($data['profile_picture_err'])
            ) {
               
                if ($this->careseekersModel->updateElderProfile($data, $elderID)) {
                   
                    createNotification($_SESSION['user_id'] , 'Your Elder Profile has been Changed.', false);
                   
                    flash('success', 'Your profile has been updated successfully.');
                    redirect('careseeker/showElderProfiles'); 
                } else {
                    die('Something went wrong. Please try again.');
                }
            } else {
               
                $this->view('careseeker/v_editElderProfile', $data);
            }
        }
    }



    public function viewConsultantProfile($consultant_id)
    {
        $profile = $this->careseekersModel->showConsultantProfile($consultant_id);
        $rating = $this->careseekersModel->getAvgRating($consultant_id,'consultant');
        $reviews = $this->careseekersModel->getReviews($consultant_id,'consultant');

        // Calculate age
        $dob = new DateTime($profile->date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        $data = [
            'profile' => $profile,
            'age' => $age,
            'rating' => $rating,
            'reviews' => $reviews
        ];
        $this->view('careseeker/v_consultantProfile', $data);
    }

    public function viewCaregiverProfile($caregiver_id, $request_id = null, $elder_id = null)
    {
        $profile = $this->careseekersModel->showCaregiverProfile($caregiver_id);
        $rating = $this->careseekersModel->getAvgRating($caregiver_id,'caregiver');
        $reviews = $this->careseekersModel->getReviews($caregiver_id,'caregiver');

        // Calculate age
        $dob = new DateTime($profile->date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        $data = [
            'profile' => $profile,
            'age' => $age,
            'rating' => $rating,
            'reviews' => $reviews,
            'request_id' => $request_id,
            'elder_id' => $elder_id,
            'caregiver' => $profile
        ];

        $this->view('careseeker/v_caregiverProfile', $data);
    }





    public function viewRequestInfo($requestId)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        $careRequest = $this->careseekersModel->getFullCareRequestInfo($requestId);

        if (!$careRequest) {
            flash('error', 'Request not found');
            redirect('careseeker/viewRequests');
        }

        $this->view('careseeker/v_viewRequestInfo', $careRequest);
    }

    public function viewConsultRequestInfo($requestId)
    {   
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        $consultRequest = $this->careseekersModel->getFullConsultRequestInfo($requestId);

        if (!$consultRequest) {
            flash('error', 'Request not found');
            redirect('careseeker/viewRequests');
        }

        $this->view('careseeker/v_viewConsultRequestInfo', $consultRequest);
    }



      
      public function viewRequests(){
            if ($_SESSION['user_role'] != 'Careseeker') {
                redirect('pages/permissonerror');
            }
       
            $careRequests = $this->careseekersModel->getAllCareRequestsByUser($_SESSION['user_id']);
            
            // Add service_type manually to each caregiving request
            foreach ($careRequests as &$req) {
                $req->service_category = 'Caregiving';
            }
        
            
            $consultRequests = $this->careseekersModel->getAllConsultRequestsByUser($_SESSION['user_id']);
            foreach ($consultRequests as &$req) {
                $req->service_category = 'Consultation';
            }
    
            $mergedRequests = array_merge($careRequests, $consultRequests);
        
            // Optionally sort by created_at
            usort($mergedRequests, function($a, $b) {
                return strtotime($b->created_at) - strtotime($a->created_at); 
            });
        
            $data = [
                'requests' => $mergedRequests
            ];
        
            $this->view('careseeker/v_viewRequests', $data);
        
        
      }


   

    public function cancelCaregivingRequest($requestId)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        date_default_timezone_set('Asia/Colombo'); 

        $request = $this->careseekersModel->getRequestById($requestId);
        $caregiver = $this->careseekersModel->getCaregiverById($request->caregiver_id);

        if (!$request) {
            flash('error', 'Invalid request or service.');
            redirect('careseeker/viewRequests');
            return;
        }

        $now = new DateTime();
        $startDateTime = $this->getStartDateTime($request); 
        $canCancel = false;
        $fineAmount = 0;
        $refundAmount = 0;

        if ($request->status === 'pending') {
            $canCancel = true;
        } elseif ($request->status === 'accepted') {
            $hoursLeft = ($startDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

            if ($hoursLeft >= 24) {
                $canCancel = true;

                if ($request->is_paid) {
                    $refundAmount = $request->payment_details; 
                }
            } elseif ($hoursLeft > 0) {
                $canCancel = true;

                if ($request->is_paid) {
                    $fineAmount = $request->payment_details * 0.10;
                    $refundAmount = $request->payment_details - $fineAmount;
                } else {
                    $fineAmount = $request->payment_details * 0.10;
                }
            }
        }

        if (!$canCancel) {
            flash('error', 'Request cannot be cancelled at this stage.');
            redirect('careseeker/viewRequests');
            return;
        }

        $this->careseekersModel->cancelRequestWithFineAndRefund($requestId, $fineAmount, $refundAmount);
        $this->scheduleModel->deleteSchedulesByRequestId($requestId);

        if (!$request->is_paid && $fineAmount > 0) {
            flash('warning', 'Request cancelled. Please proceed to pay the fine to complete cancellation.');
            redirect('payment/payFine/' . $requestId);
            return;
        }
        $emailBody = '<h1>Request Cancelled</h1>
        <p>A request you previously received has been cancelled by the care seeker.</p>
        <p>Request ID: ' . $requestId . '</p>
        <p>Please log in to the We4u system for further details.</p>';
  $caregiverEmail = $caregiver->email;
        
$this->sendEmail(
$caregiverEmail,
'Request Cancellation Notification - We4u',
$emailBody
);

       
        createNotification($request->caregiver_id, 'A request you previously received has been cancelled by the care seeker.', false);

        flash('success', 'Request cancelled successfully.');
        redirect('careseeker/viewRequests');
    }


    private function getStartDateTime($request)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        $date = new DateTime($request->start_date);

        if ($request->duration_type === 'long-term') {
            $date->setTime(8, 0);
        } elseif ($request->duration_type === 'short-term') {
            $slots = json_decode($request->time_slots);
            if (in_array('morning', $slots)) {
                $date->setTime(8, 0);
            } elseif (in_array('afternoon', $slots)) {
                $date->setTime(13, 0);
            } elseif (in_array('overnight', $slots)) {
                $date->setTime(20, 0);
            } elseif (in_array('full_day', $slots)) {
                $date->setTime(8, 0);
            }
        }

        return $date;
    }


    
    public function cancelConsultRequest($requestId)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        date_default_timezone_set('Asia/Colombo'); 

        $request = $this->careseekersModel->getConsultantRequestById($requestId);
        $consultant = $this->careseekersModel->getConsultantById($request->consultant_id);

        if (!$request) {
            flash('error', 'Invalid consultation request.');
            redirect('careseeker/viewRequests');
            return;
        }

        $now = new DateTime();
        $appointmentDateTime = $this->getAppointmentDateTime($request);
        $canCancel = false;
        $fineAmount = 0;
        $refundAmount = 0;

        if ($request->status === 'pending') {
            $canCancel = true;
            if ($request->is_paid) {
                $refundAmount = $request->payment_details; 
            }
        } elseif ($request->status === 'accepted') {
            $hoursLeft = ($appointmentDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

            if ($hoursLeft >= 5) {
                $canCancel = true;
                if ($request->is_paid) {
                    $refundAmount = $request->payment_details; 
                }
            } elseif ($hoursLeft > 0) {
                $canCancel = true;
                if ($request->is_paid) {
                    $fineAmount = $request->payment_details * 0.10;
                    $refundAmount = $request->payment_details - $fineAmount;
                } else {
                    $fineAmount = $request->payment_details * 0.10;
                }
            }
        }

        if (!$canCancel) {
            flash('error', 'Consultation cannot be cancelled at this stage.');
            redirect('careseeker/viewRequests');
            return;
        }

        $this->careseekersModel->cancelConsultRequestWithFineAndRefund($requestId, $fineAmount, $refundAmount);

        if (!$request->is_paid && $fineAmount > 0) {
            flash('warning', 'Consultation cancelled. Please proceed to pay the cancellation fee to complete this process.');

            redirect('payment/payConsultFine/' . $requestId);
            return;
        }
        $emailBody = '<h1>Request Cancelled !</h1>
        <p>A request you previously received has been cancelled by the care seeker.</p>
        <p>Request ID: ' . $requestId . '</p>
        <p>Please log in to the We4u system for further details.</p>';
  $consultantEmail = $consultant->email;
       
$this->sendEmail(
$consultantEmail,
'Request Cancellation Notification - We4u',
$emailBody
);  
        createNotification($request->consultant_id, 'A request you previously received has been cancelled by the care seeker.', false);

        flash('success', 'Consultation request cancelled successfully.');
        redirect('careseeker/viewRequests');
    }

    private function getAppointmentDateTime($request)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        $date = new DateTime($request->appointment_date);

        if (!empty($request->start_time)) {
            
            $timeComponents = explode(':', $request->start_time);
            $hours = (int)$timeComponents[0];
            $minutes = (int)$timeComponents[1];
            $date->setTime($hours, $minutes, 0);
        } else {
           
            $date->setTime(8, 0, 0);
        }

        return $date;
    }

   
    public function deleteRequest($requestId = null)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
     
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        
        if ($_SESSION['user_role'] !== 'Careseeker') {
            flash('error', 'You do not have permission to delete requests', 'alert alert-danger');
            redirect('pages/index');
        }

       
        if (!$requestId) {
            flash('error', 'No request specified', 'alert alert-danger');
            redirect('careseeker/viewRequests');
        }

        
        $request = $this->careseekersModel->getRequestById($requestId);
        if (!$request || $request->requester_id != $_SESSION['user_id']) {
            flash('error', 'You do not have permission to delete this request', 'alert alert-danger');
            redirect('careseeker/dashboard');
        }

       
        $deletableStates = ['cancelled', 'rejected', 'completed'];
        if (!in_array(strtolower($request->status), $deletableStates)) {
            flash('warning', 'Only cancelled, rejected, or completed requests can be deleted', 'alert alert-danger');
            redirect('careseeker/viewRequestInfo/' . $requestId);
        }

       
        if ($this->careseekersModel->deleteRequest($requestId)) {
            flash('success', 'Request successfully deleted', 'alert alert-success');
        } else {
            flash('error', 'Failed to delete request. Please try again.', 'alert alert-danger');
        }

      
        redirect('careseeker/viewRequests');
    }






    public function viewPayments()
    {
        // Check if user is logged in as consultant
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Careseeker') {
            redirect('users/login');
        }

        $careseekerId= $_SESSION['user_id'];
        $carepayments = $this->careseekersModel->getPaymentsByCareseekerId($careseekerId);
        $consultpayments = $this->careseekersModel->getConsultPaymentsByCareseekerId($careseekerId);

        foreach($carepayments as $payment) {
            $payment->service="Caregiving";
        }
        foreach($consultpayments as $payment) {
            $payment->service="Consulting";
        }
        //merge arrays
        $allPayments = array_merge($carepayments, $consultpayments);

        // Sort all payments by date descending
        usort($allPayments, function($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        
        
        $data = [
            'payments' => $allPayments
        ];
        $this->view('careseeker/v_viewPayments', $data);
    }

    public function viewConsultants()
    {
        $data = [];
        $this->view('careseeker/v_viewConsultants', $data);
    }



    public function viewMyConsultantSessions()
    {

        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

       
        $careseeker_id = $_SESSION['user_id'];

       
        $consultant_sessions = $this->careseekersModel->getAllConsultantSessions($careseeker_id);
        $elderProfiles = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        $data = [
            'sessions' => $consultant_sessions,
            'elder_profiles' => $elderProfiles
        ];


        $this->view('careseeker/v_viewConsultants', $data);
    }


   
    public function uploadSessionFile()
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Careseeker') {
                redirect('users/login');
            }

            $session_id = $_POST['session_id'];
            $file_type = $_POST['file_type'];
            $uploaded_by = 'careseeker'; 

          
            if ($file_type === 'link') {
                $link = trim($_POST['link']);
                if (!empty($link)) {
                    $this->careseekersModel->uploadSessionFile($session_id, $uploaded_by, $file_type, $link);
                    flash('success', 'Link shared successfully');
                } else {
                    flash('error', 'Link cannot be empty');
                }
            }

           
            elseif (!empty($_FILES['file']['name'])) {
                $file_name = time() . '_' . basename($_FILES['file']['name']);

                
                $target_dir = dirname(APPROOT) . '/public/documents/sessionDocuments/';
                $public_path = 'documents/sessionDocuments/' . $file_name;

                
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $target_path = $target_dir . $file_name;

                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                    $this->careseekersModel->uploadSessionFile($session_id, $uploaded_by, $file_type, $public_path);
                    flash('success', 'File uploaded successfully');
                } else {
                    flash('error', 'File upload failed');
                }
            }

            redirect("careseeker/viewConsultantSession/$session_id");
        }
    }


    public function deleteSessionFile($file_id)
    {
      
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

       
        $file = $this->careseekersModel->getFileById($file_id); 

        if (!$file) {
            flash('error', 'File not found');
            redirect('pages/notfound'); 
        }

        if ($this->careseekersModel->deleteSessionFile($file_id)) {
            flash('success', 'File deleted successfully');
        } else {
            flash('error', 'File deletion failed');
        }

        redirect('careseeker/viewConsultantSession/' . $file->session_id);
    }


    public function viewConsultantSession($session_id)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
       
        $session = $this->careseekersModel->getAllConsultantSessionsById($session_id);

       
        if (!$session || $session->careseeker_id != $_SESSION['user_id']) {
            flash('error', 'Unauthorized access or session not found');
            redirect('careseeker/dashboard');
        }



       
        $your_files = $this->careseekersModel->getSessionFilesByUploader($session_id, 'careseeker');

        
        $consultant_files = $this->careseekersModel->getSessionFilesByUploader($session_id, 'consultant');

        
        $data = [
            'session_id' => $session_id,
            'session' => $session,
            'your_files' => $your_files,
            'consultant_files' => $consultant_files
        ];

        
        $this->view('careseeker/v_viewConsultantSession', $data);
    }






    public function getCaregiverSchedule($caregiverId)
    {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }

        // Prevent any PHP errors or warnings from being output
        ob_start();

        try {
            // Get caregiver schedules
            $shortSchedules = $this->scheduleModel->getAllShortShedulesForCaregiver($caregiverId);
            $longSchedules =  $this->scheduleModel->getAllLongShedulesForCaregiver($caregiverId);

            // Clear any output buffer to prevent PHP errors from being included in the response
            ob_end_clean();

            // Prepare response data
            $data = [
                'shortSchedules' => $shortSchedules ? $shortSchedules : [],
                'longSchedules' => $longSchedules ? $longSchedules : []
            ];

            // Set content type header and output JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            // Clear any output buffer
            ob_end_clean();

            // Return error as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'error' => $e->getMessage(),
                'shortSchedules' => [],
                'longSchedules' => []
            ]);
        }
        exit;
    }


    public function getConsultantAvailability($consultantId) {
        if ($_SESSION['user_role'] != 'Careseeker') {
            redirect('pages/permissonerror');
        }
        // Prevent any PHP errors or warnings from being output
        ob_start();
        
        try {
            // Get consultant availability patterns
            $availabilityPatterns = $this->scheduleModel->getAvailabilityPatterns($consultantId);
            
            // Get consultant availability instances
            $availabilityInstances = $this->scheduleModel->getAvailabilityInstances($consultantId);
            
            // Get existing appointments (pending or accepted)
            $existingAppointments = $this->scheduleModel->getActiveAppointments($consultantId);
            
            // Clear any output buffer to prevent PHP errors from being included in the response
            ob_end_clean();
            
            // Prepare response data
            $data = [
                'availabilityPatterns' => $availabilityPatterns ? $availabilityPatterns : [],
                'availabilityInstances' => $availabilityInstances ? $availabilityInstances : [],
                'existingAppointments' => $existingAppointments ? $existingAppointments : []
            ];
            
            // Set content type header and output JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            // Clear any output buffer
            ob_end_clean();
            
            // Return error as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'error' => $e->getMessage(),
                'availabilityPatterns' => [],
                'availabilityInstances' => [],
                'existingAppointments' => []
            ]);
        }
        exit;
    }


    
    public function careseekerCaregivingHistory(){ 
        $careseekerId = $_SESSION['user_id'];
        $history = $this->careseekersModel->getCareseekerCaregivingHistory($careseekerId);
    
        $data = [
            'history' => $history
        ];
    
        $this->view('careseeker/v_caregivingHistory', $data);
    }

    public function careseekerConsultHistory(){ 
        $careseekerId = $_SESSION['user_id'];
        $history = $this->careseekersModel->getCareseekerConsultHistory($careseekerId);
    
        $data = [
            'history' => $history
        ];
    
        $this->view('careseeker/v_consultHistory', $data);
    }
    
    
    
    
    
    
    
    


private function sendEmail($to, $subject, $body) {
   
    $result = sendEmail($to, $subject, $body);
    
    if ($result['success']) {
        return true;
    } else {
        error_log($result['message']);
        return false;
    }
  }



  public function viewCaregiverReviews($caregiver_id = null) {
    if ($caregiver_id === null) {
        flash('review_message', 'Caregiver ID is required.');
        redirect('careseeker/showElderProfiles'); // Redirect to a fallback page
    }

    // Fetch reviews for the caregiver
    $reviews = $this->careseekersModel->getReviews($caregiver_id);
    error_log("Reviews for caregiver with ID $caregiver_id: " . json_encode($reviews));

    // Prepare the data to pass to the view
    $data = [
        'title' => 'Caregiver Reviews',
        'reviews' => $reviews,
        'caregiver_id' => $caregiver_id // Pass caregiver_id to the view
    ];

    // Load the appropriate view
    $this->view('caregiver/v_rate&review', $data);
}

public function addReview($reviewed_user_id, $role) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'reviewer_id' => $_SESSION['user_id'], // The logged-in careseeker
            'reviewed_user_id' => $reviewed_user_id, // The caregiver or consultant being reviewed
            'review_role' => $role, // Either "Caregiver" or "Consultant"
            'rating' => isset($_POST['rating']) ? trim($_POST['rating']) : '',
            'review_text' => isset($_POST['review_text']) ? trim($_POST['review_text']) : '',
            'rating_err' => '',
            'review_text_err' => '',
            'review_err' => '',
            'caregiver_id' => $reviewed_user_id // Add this to ensure it's available in the view
        ];
        error_log("Data for adding review: " . json_encode($data));

        // Validate rating
        if (empty($data['rating']) || $data['rating'] == '0') {
            $data['rating_err'] = 'Please provide a rating.';
        }

        // Validate review text
        if (empty($data['review_text'])) {
            $data['review_text_err'] = 'Please provide a review.';
        }

        // Check for errors
        if (empty($data['rating_err']) && empty($data['review_text_err'])) {
            if ($this->careseekersModel->addReview($data)) {
                flash('review_message', 'Review added successfully');
                redirect('careseeker/viewCaregiverReviews/' . $reviewed_user_id);
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('careseeker/v_addReview', $data);
        }
    } else {
        $data = [
            'rating' => '',
            'review_text' => '',
            'rating_err' => '',
            'review_text_err' => '',
            'caregiver_id' => $reviewed_user_id // Pass caregiver_id to the view
        ];

        $this->view('careseeker/v_addReview', $data);
    }
}
  public function editReview($review_id) {
    $review = $this->careseekersModel->getReviewById($review_id);

    // Ensure the logged-in user is the reviewer
    if ($review->reviewer_id != $_SESSION['user_id']) {
        flash('review_message', 'You are not authorized to edit this review.');
        redirect('careseeker/viewCaregiverReviews/' . $review->reviewed_user_id);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'review_id' => $review_id,
            'review_text' => trim($_POST['review_text']),
            'rating' => trim($_POST['rating']),
            'updated_at' => date('Y-m-d H:i:s'), // Update the updated_at field
            'review_text_err' => '',
            'rating_err' => '',
            'review_err' => '' // Initialize review_err
        ];

        // Validate input
        if (empty($data['review_text'])) {
            $data['review_text_err'] = 'Please enter a review.';
        }
        if (empty($data['rating'])) {
            $data['rating_err'] = 'Please select a rating.';
        }

        if (empty($data['review_text_err']) && empty($data['rating_err'])) {
            if ($this->careseekersModel->updateReview($data)) {
                flash('review_message', 'Review updated successfully');
                redirect('careseeker/viewCaregiverReviews/' . $review->reviewed_user_id);
            } else {
                $data['review_err'] = 'Something went wrong while updating the review.';
                $this->view('careseeker/v_editreview', $data);
            }
        } else {
            $this->view('careseeker/v_editreview', $data);
        }
    } else {
        $data = [
            'review_id' => $review_id,
            'review_text' => $review->review_text,
            'rating' => $review->rating,
            'review_text_err' => '',
            'rating_err' => '',
            'review_err' => '' 
        ];
        $this->view('careseeker/v_editreview', $data);
    }
}



public function deleteReview($review_id) {
    $review = $this->careseekersModel->getReviewById($review_id);

    
    if ($review->reviewer_id != $_SESSION['user_id']) {
        flash('review_message', 'You are not authorized to delete this review.');
        redirect('careseeker/viewCaregiverReviews/' . $review->reviewed_user_id);
    }

    if ($this->careseekersModel->deleteReview($review_id)) {
        flash('review_message', 'Review deleted successfully');
        redirect('careseeker/viewCaregiverReviews/' . $review->reviewed_user_id);
    } else {
        die('Something went wrong');
    }
}



   
  
}
?>
