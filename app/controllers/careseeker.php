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
            if ($_SESSION['user_role'] != 'Careseeker') {
                redirect('pages/permissonerror');
            }
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
        $careseeker_id = $_SESSION['user_id'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Prepare input data and error messages
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
                'profile_picture_name' => time() . '_' . $_FILES['profile_picture']['name'], // For storing the profile picture path

                // Error fields
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];

            // Validation
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

            // Profile picture validation (only if a file is uploaded)
            if (!empty($data['profile_picture']['name'])) {
                $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];

                // Upload the new profile picture
                if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                    $data['profile_pic'] = $profileImagePath; // Assign the correct path
                } else {
                    $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                }
            } else {
                $data['profile_picture_name'] = null;
            }


            // Check for errors
            if (
                empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err'])
                && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err']) &&
                empty($data['profile_picture_err'])
            ) {

                // If no errors, proceed to create the profile
                if ($this->careseekersModel->createElderProfile($data)) {
                    redirect('careseeker/showElderProfiles'); // Redirect to the elder profiles page
                } else {
                    echo "Error creating profile!";
                }
            } else {
                // Load the view with error messages
                $this->view('careseeker/v_create', $data);
            }
        } else {
            // If the request is not POST, load an empty form
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


        $careseeker_id = $_SESSION['user_id'] ?? '';

        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        // Pass the data to the view
        $data = [
            'elders' => $elders
        ];

        $this->view('careseeker/v_createProfile', $data);
    }

    public function showCaregiverRequestForm($caregiver_id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get the careseeker_id from the session
        $careseeker_id = $_SESSION['user_id'];

        // Get the elder profiles for the careseeker
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
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get the careseeker_id from the session
        $careseeker_id = $_SESSION['user_id'];

        // Get the elder profiles for the careseeker
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
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get the careseeker_id from the session
        $careseeker_id = $_SESSION['user_id'];

        // Get the elder profiles for the careseeker
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        // First, check if caregiver exists
        $caregiver = $this->careseekersModel->showCaregiverProfile($caregiver_id);
        if (!$caregiver) {
            flash('error', 'Caregiver not found');
            redirect('careseeker/ViewRequests');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Extract data from the form
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

            // Validate elder profile selection
            if (empty($data['elder_profile'])) {
                $data['error'] = 'Please select an elder profile';
            }
            // Validate duration type selection
            elseif (empty($data['duration_type'])) {
                $data['error'] = 'Please select a duration type';
            }
            // Validate dates and time slots based on duration type
            elseif ($data['duration_type'] === 'long-term') {
                // Validate start and end dates for long-term care
                if (empty($data['from_date'])) {
                    $data['error'] = 'Please select a start date for long-term care';
                } elseif (empty($data['to_date'])) {
                    $data['error'] = 'Please select an end date for long-term care';
                } else {
                    // Check if caregiver type matches the selected duration
                    if ($caregiver->caregiver_type !== 'long' && $caregiver->caregiver_type !== 'both') {
                        $data['error'] = 'This caregiver does not offer long-term care services';
                    } else {
                        // Validate date range
                        $from_date = new DateTime($data['from_date']);
                        $to_date = new DateTime($data['to_date']);
                        $today = new DateTime();

                        // Check if from_date is in the future (at least tomorrow)
                        $tomorrow = new DateTime();
                        $tomorrow->setTime(0, 0, 0);
                        $tomorrow->modify('+1 day');

                        $from_date_normalized = clone $from_date;
                        $from_date_normalized->setTime(0, 0, 0);

                        if ($from_date_normalized < $tomorrow) {
                            $data['error'] = 'Start date must be at least tomorrow';
                        }
                        // Check if to_date is after from_date
                        elseif ($to_date < $from_date) {
                            $data['error'] = 'End date must be after or equal to start date';
                        } else {
                            // Calculate days between from_date and to_date
                            $interval = $from_date->diff($to_date);
                            $days = $interval->days + 1; // Include both start and end day

                            // Check if the duration exceeds 5 days
                            if ($days > 5) {
                                $data['error'] = 'Maximum care duration is 5 days';
                            }

                            // Check date range availability - FIXED: Moved this check here
                            if (empty($data['error'])) {
                                // Make sure dates are in the correct format
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
                // Validate date for short-term care
                if (empty($data['from_date_short'])) {
                    $data['error'] = 'Please select a date for short-term care';
                }
                // Validate time slots for short-term care
                elseif (empty($data['time_slots'])) {
                    $data['error'] = 'Please select at least one time slot';
                } else {
                    // Check if caregiver type matches the selected duration
                    if ($caregiver->caregiver_type !== 'short' && $caregiver->caregiver_type !== 'both') {
                        $data['error'] = 'This caregiver does not offer short-term care services';
                    } else {
                        // Validate that the short-term date is in the future
                        $from_date_short = new DateTime($data['from_date_short']);
                        $from_date_short->setTime(0, 0, 0);

                        $tomorrow = new DateTime();
                        $tomorrow->setTime(0, 0, 0);
                        $tomorrow->modify('+1 day');


                        if ($from_date_short < $tomorrow) {
                            $data['error'] = 'Date for short-term care must be at least tomorrow';
                        }

                        // Validate time slots selection
                        $fullDaySelected = in_array('full-day', $data['time_slots']);
                        $otherSlotsSelected = array_diff($data['time_slots'], ['full-day']);

                        if ($fullDaySelected && !empty($otherSlotsSelected)) {
                            $data['error'] = 'Cannot select Full Day and other time slots together';
                        }

                        // Check if time slots are available
                        if (empty($data['error'])) {
                            $allSlotsAvailable = true;

                            // Convert time slot names from frontend to database format
                            $slotMapping = [
                                'morning' => 'morning',
                                'evening' => 'evening',
                                'overnight' => 'overnight',
                                'full-day' => 'fullday'
                            ];

                            foreach ($data['time_slots'] as $timeSlot) {
                                // Convert frontend slot name to database format
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

            // Validate payment amount
            if (empty($data['error']) && $data['total_payment'] <= 0) {
                $data['error'] = 'Invalid payment amount';
            }

            //validate service address
            if (empty($data['error']) && empty($data['service_address'])) {
                $data['error'] = 'Please enter a service address';
            }

            // If no errors, proceed with creating the request
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

                // execute the query and get the resulting data 
                $result = $this->careseekersModel->sendCareRequest($requestData);
                if ($result['success']) {
                    flash('success', 'Care request sent successfully');
                    // update sheduling table for caregiver
                    // first check duration type if if that is a short term then
                    if ($data['duration_type'] === 'short-term') {
                        // if there are several time slots then go through each of them and pass that data in to the model
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
                // If there are errors, pass the data back to the view
                $data['careseeker'] = $this->careseekersModel->showCareseekerProfile($careseeker_id);
                $this->view('careseeker/v_requestCaregiver', $data);
            }
        } else {
            // If someone tries to access this directly without POST, redirect
            redirect('careseeker/showCaregiverRequestForm/' . $caregiver_id);
        }
    }



    public function requestConsultant($consultant_id)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        $careseeker_id = $_SESSION['user_id'];
        $elders = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        // Check if consultant exists
        $consultant = $this->careseekersModel->showConsultantProfile($consultant_id);
        if (!$consultant) {
            flash('error', 'Consultant not found');
            redirect('careseeker/viewConsultantRequests');
        }

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

        // Validations
        if (empty($data['elder_profile'])) {
            $data['error'] = 'Please select an elder profile';
        } elseif (empty($data['appointment_date'])) {
            $data['error'] = 'Please select an appointment date';
        } else {
            // Check if appointment date is at least tomorrow
            $appointment_date = new DateTime($data['appointment_date']);
            $today = new DateTime();
            if ($appointment_date < $today) {
                $data['error'] = 'Appointment must be at least tomorrow. Please select a different time slot';
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

            // If all validations pass
            if (empty($data['error'])) {
                // $formattedTimeSlot = $data['from_time'] . ':00-' . $data['to_time'] . ':00';

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
            // Not a POST request, redirect to form
            redirect('careseeker/showConsultantRequestForm/' . $consultant_id);
        }
    }





    public function deleteElderProfile($elderId)
    {
        // Check if the elder profile exists
        if ($this->careseekersModel->deleteElderProfile($elderId)) {
            echo 'Elder profile deleted successfully.';
        } else {
            echo 'Failed to delete elder profile.';
        }

        // Redirect to the profile page
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
        // Ensure the user is logged in
        if (!$this->isLoggedIn()) {
            redirect('users/login');
        }

        // Get the careseeker_id from the session
        $careseeker_id = $_SESSION['user_id'] ?? '';

        // Check if elder_id is passed in the GET request
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['elder_id'])) {
            // Get elder_id from the GET request
            $elderID = $_GET['elder_id'];

            // Fetch the elder profile by elder_id and careseeker_id
            $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);

            // If no profile found, redirect to the elder profiles page
            if (!$elderProfile) {
                redirect('careseeker/showElderProfiles');
            }

            // Prefill the form with elder profile data
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
                'profile_picture_name' => '', // Initial value for profile picture
                // Error fields
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];


            // Load the view with pre-filled data
            $this->view('careseeker/v_editElderProfile', $data);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Handle POST request to update elder profile
            $elderID = $_POST['elder_id']; // Get elder_id from the form submission
            $careseeker_id = $_SESSION['user_id'] ?? ''; // Get careseeker_id from the session

            $elderProfile = $this->careseekersModel->getElderProfileById($elderID, $careseeker_id);
            $currentProfilePicture = $elderProfile->profile_picture;
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Prepare input data and error messages
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
                'profile_picture_name' => time() . '_' . $_FILES['profile_picture']['name'], // Default to current picture if not updated
                // Error fields
                'first_name_err' => '',
                'last_name_err' => '',
                'age_err' => '',
                'emergency_contact_err' => '',
                'relationship_to_careseeker_err' => '',
                'profile_picture_err' => '',
            ];



            // Validation
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

            // Handle profile picture upload/update
            if (!empty($data['profile_picture']['name'])) { // Only process if a new image is uploaded
                $profileImagePath = '/images/profile_imgs/' . $data['profile_picture_name'];

                if (!empty($currentProfilePicture)) {
                    // Update existing profile picture by deleting the old one
                    $oldImagePath = PUBROOT . '/images/profile_imgs/' . $currentProfilePicture;
                    if (updateImage($oldImagePath, $data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs/')) {
                        // Successfully updated
                    } else {
                        $data['profile_picture_err'] = 'Failed to update profile picture.';
                    }
                } else {
                    // Upload a new profile picture
                    if (uploadImage($data['profile_picture']['tmp_name'], $data['profile_picture_name'], '/images/profile_imgs')) {
                        // Successfully uploaded
                    } else {
                        $data['profile_picture_err'] = 'Profile picture uploading unsuccessful';
                    }
                }
            } else {
                // No new profile picture uploaded, retain the old one
                $data['profile_picture_name'] = $currentProfilePicture;
            }
            // Check for errors
            if (
                empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['age_err'])
                && empty($data['emergency_contact_err']) && empty($data['relationship_to_careseeker_err'])
                && empty($data['profile_picture_err'])
            ) {
                // No errors, update the profile
                if ($this->careseekersModel->updateElderProfile($data, $elderID)) {
                    redirect('careseeker/showElderProfiles'); // Redirect to the elder profiles page
                } else {
                    die('Something went wrong. Please try again.');
                }
            } else {
                // Load the view with error messages
                $this->view('careseeker/v_editElderProfile', $data);
            }
        }
    }



    public function viewConsultantProfile($consultant_id)
    {
        $profile = $this->careseekersModel->showConsultantProfile($consultant_id);
        $rating = $this->careseekersModel->getAvgRating($consultant_id);
        $reviews = $this->careseekersModel->getReviews($consultant_id);

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
        $rating = $this->careseekersModel->getAvgRating($caregiver_id);
        $reviews = $this->careseekersModel->getReviews($caregiver_id);

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

        $careRequest = $this->careseekersModel->getFullCareRequestInfo($requestId);

        if (!$careRequest) {
            flash('error', 'Request not found');
            redirect('careseeker/viewRequests');
        }

        $this->view('careseeker/v_viewRequestInfo', $careRequest);
    }

    public function viewConsultRequestInfo($requestId)
    {

        $consultRequest = $this->careseekersModel->getFullConsultRequestInfo($requestId);

        if (!$consultRequest) {
            flash('error', 'Request not found');
            redirect('careseeker/viewRequests');
        }

        $this->view('careseeker/v_viewConsultRequestInfo', $consultRequest);
    }



      
      public function viewRequests(){
       
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


    // Cancel Caregiving Request

    public function cancelCaregivingRequest($requestId)
    {
        date_default_timezone_set('Asia/Colombo'); // or your relevant timezone

        $request = $this->careseekersModel->getRequestById($requestId);

        if (!$request) {
            flash('error', 'Invalid request or service.');
            redirect('careseeker/viewRequests');
            return;
        }

        $now = new DateTime();
        $startDateTime = $this->getStartDateTime($request); // using slot-aware logic
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
                    $refundAmount = $request->payment_details; // full refund
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

        if (!$request->is_paid && $fineAmount > 0) {
            flash('warning', 'Request cancelled. Please proceed to pay the fine to complete cancellation.');
            redirect('payment/payFine/' . $requestId);
            return;
        }

        flash('success', 'Request cancelled successfully.');
        redirect('careseeker/viewRequests');
    }


    private function getStartDateTime($request)
    {
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


    // Cancel Consultation Request
    public function cancelConsultRequest($requestId)
    {
        date_default_timezone_set('Asia/Colombo'); // or your relevant timezone

        $request = $this->careseekersModel->getConsultantRequestById($requestId);

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
                $refundAmount = $request->payment_details; // full refund for pending requests
            }
        } elseif ($request->status === 'accepted') {
            $hoursLeft = ($appointmentDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

            if ($hoursLeft >= 5) {
                $canCancel = true;
                if ($request->is_paid) {
                    $refundAmount = $request->payment_details; // full refund if > 5 hours
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

        flash('success', 'Consultation request cancelled successfully.');
        redirect('careseeker/viewRequests');
    }

    private function getAppointmentDateTime($request)
    {
        $date = new DateTime($request->appointment_date);

        if (!empty($request->start_time)) {
            // Use the start_time directly since it's now a TIME datatype
            $timeComponents = explode(':', $request->start_time);
            $hours = (int)$timeComponents[0];
            $minutes = (int)$timeComponents[1];
            $date->setTime($hours, $minutes, 0);
        } else {
            // Default to 8:00 AM if no start time is specified
            $date->setTime(8, 0, 0);
        }

        return $date;
    }

    // Delete Caregiving Request
    public function deleteRequest($requestId = null)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Check if user is a careseeker
        if ($_SESSION['user_role'] !== 'Careseeker') {
            flash('error', 'You do not have permission to delete requests', 'alert alert-danger');
            redirect('pages/index');
        }

        // If no request ID provided, redirect back
        if (!$requestId) {
            flash('error', 'No request specified', 'alert alert-danger');
            redirect('careseeker/viewRequests');
        }

        // Verify the request belongs to this careseeker
        $request = $this->careseekersModel->getRequestById($requestId);
        if (!$request || $request->requester_id != $_SESSION['user_id']) {
            flash('error', 'You do not have permission to delete this request', 'alert alert-danger');
            redirect('careseeker/dashboard');
        }

        // Check if the request is in a deletable state (cancelled, rejected, or completed)
        $deletableStates = ['cancelled', 'rejected', 'completed'];
        if (!in_array(strtolower($request->status), $deletableStates)) {
            flash('warning', 'Only cancelled, rejected, or completed requests can be deleted', 'alert alert-danger');
            redirect('careseeker/viewRequestInfo/' . $requestId);
        }

        // Delete the request
        if ($this->careseekersModel->deleteRequest($requestId)) {
            flash('success', 'Request successfully deleted', 'alert alert-success');
        } else {
            flash('error', 'Failed to delete request. Please try again.', 'alert alert-danger');
        }

        // Redirect to dashboard
        redirect('careseeker/viewRequests');
    }






    public function viewPayments()
    {
        $data = [];
        $this->view('careseeker/v_viewPayments', $data); 
    }

    public function viewConsultants()
    {
        $data = [];
        $this->view('careseeker/v_viewConsultants', $data);
    }



    public function viewMyConsultantSessions()
    {

        // Check if user is logged in as consultant
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Careseeker') {
            redirect('users/login');
        }


        // Get caregiver ID from session
        $careseeker_id = $_SESSION['user_id'];

        // Get all requests for this consultant
        $consultant_sessions = $this->careseekersModel->getAllConsultantSessions($careseeker_id);
        $elderProfiles = $this->careseekersModel->getElderProfilesByCareseeker($careseeker_id);

        $data = [
            'sessions' => $consultant_sessions,
            'elder_profiles' => $elderProfiles
        ];


        $this->view('careseeker/v_viewConsultants', $data);
    }


    //upload files for session
    public function uploadSessionFile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check login and role
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Careseeker') {
                redirect('users/login');
            }

            $session_id = $_POST['session_id'];
            $file_type = $_POST['file_type'];
            $uploaded_by = 'careseeker'; // Or 'careseeker' if you're in that controller

            // Handle link upload
            if ($file_type === 'link') {
                $link = trim($_POST['link']);
                if (!empty($link)) {
                    $this->careseekersModel->uploadSessionFile($session_id, $uploaded_by, $file_type, $link);
                    flash('success', 'Link shared successfully');
                } else {
                    flash('error', 'Link cannot be empty');
                }
            }

            // Handle file upload
            elseif (!empty($_FILES['file']['name'])) {
                $file_name = time() . '_' . basename($_FILES['file']['name']);

                // Define file system and public path
                $target_dir = dirname(APPROOT) . '/public/documents/sessionDocuments/';
                $public_path = 'documents/sessionDocuments/' . $file_name;

                // Create directory if not exists
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
        // You can add role-based security here if needed
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Load the file first to get session_id for redirection after delete
        $file = $this->careseekersModel->getFileById($file_id); // See helper below

        if (!$file) {
            flash('error', 'File not found');
            redirect('pages/notfound'); // or wherever you prefer
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
        // Get session details
        $session = $this->careseekersModel->getAllConsultantSessionsById($session_id);

        // Check if session exists and belongs to the current user
        if (!$session || $session->careseeker_id != $_SESSION['user_id']) {
            flash('error', 'Unauthorized access or session not found');
            redirect('careseeker/dashboard');
        }



        // Get files uploaded by careseeker
        $your_files = $this->careseekersModel->getSessionFilesByUploader($session_id, 'careseeker');

        // Get files uploaded by consultant
        $consultant_files = $this->careseekersModel->getSessionFilesByUploader($session_id, 'consultant');

        // Prepare data for view
        $data = [
            'session_id' => $session_id,
            'session' => $session,
            'your_files' => $your_files,
            'consultant_files' => $consultant_files
        ];

        // Load view
        $this->view('careseeker/v_viewConsultantSession', $data);
    }






    public function getCaregiverSchedule($caregiverId)
    {
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
    
    
    
    
    
    
    
    




   
  
}
?>
