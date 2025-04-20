<?php
class Consultants extends Controller {
    private $consultantsModel;

    public function __construct() {
        if(!isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            redirect('users/login');
        }

        $this->consultantsModel = $this->model('M_Consultantss');
    }

    public function viewConsultants() {
        // Get filter parameters with proper sanitization
        $filters = [
            'username' => trim(filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING) ?? ''),
            'region' => trim(filter_input(INPUT_GET, 'region', FILTER_SANITIZE_STRING) ?? ''),
            'speciality' => trim(filter_input(INPUT_GET, 'speciality', FILTER_SANITIZE_STRING) ?? ''),
            'sort' => trim(filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING) ?? '')
        ];

        // Pagination setup
        $page = max(1, (int)(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1));
        $perPage = 9;

        // Get filtered consultants and total count
        $consultants = $this->consultantsModel->getFilteredConsultants($filters, $page, $perPage);
        $totalConsultants = $this->consultantsModel->getTotalFilteredConsultants($filters);
        $totalPages = ceil($totalConsultants / $perPage);

        // Get regions and specialities for filter dropdowns
        $regions = $this->consultantsModel->getAllRegions();
        $specialities = $this->consultantsModel->getAllSpecialities();


        $data = [
            'consultants' => $consultants,
            'regions' => $regions,
            'specialities' => $specialities,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalConsultants' => $totalConsultants,
            'filters' => $filters
        ];

        $this->view('consultant/viewConsultants', $data);
    }

    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'nic_no' => trim($_POST['nic_no']),
                'gender' => trim($_POST['gender']),
                'dob' => trim($_POST['dob']),
                'contact_info' => trim($_POST['contact_info']),
                'slmc_no' => trim($_POST['slmc_no']),
                'specialization' => trim($_POST['specialization']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'documents' => $_FILES['documents'],
                'username_err' => '',
                'email_err' => '',
                'nic_no_err' => '',
                'gender_err' => '',
                'dob_err' => '',
                'contact_info_err' => '',
                'slmc_no_err' => '',
                'specialization_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'documents_err' => ''
            ];

            // Validation
            $this->validateRegistrationData($data);

            // Handle document upload
            $uploadDir = '/documents/approvalDocuments/';
            $uploadResult = uploadMultipleFiles($data['documents'], $uploadDir);
            $uploadedFiles = $uploadResult['uploadedFiles'];

            if (empty($uploadedFiles)) {
                $data['documents_err'] = 'Please add the documents';
            } elseif (!empty($uploadResult['errors'])) {
                $data['documents_err'] = implode(', ', $uploadResult['errors']);
            }

            // Check for validation errors
            if ($this->hasNoValidationErrors($data)) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->consultantsModel->register($data, $uploadedFiles)) {
                    redirect('users/login');
                } else {
                    die('Registration failed');
                }
            }

            $this->view('users/v_consultant_register', $data);
        } else {
            // Initial form load
            $this->view('users/v_consultant_register', $this->getEmptyRegistrationData());
        }
    }

    private function validateRegistrationData(&$data) {
        if(empty($data['username'])) {
            $data['username_err'] = 'Please enter username';
        }

        if(empty($data['email'])) {
            $data['email_err'] = 'Please enter email';
        } elseif($this->consultantsModel->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Email is already taken';
        }

        $this->validateNIC($data);
        $this->validateBasicInfo($data);
        $this->validatePassword($data);
    }

    private function validateNIC(&$data) {
        if (empty($data['nic_no'])) {
            $data['nic_no_err'] = 'Please enter your NIC number';
            return;
        }

        $patternPre2016 = '/^\d{9}[VXvx]$/';
        $patternPost2016 = '/^\d{12}$/';

        if (!preg_match($patternPre2016, $data['nic_no']) && !preg_match($patternPost2016, $data['nic_no'])) {
            $data['nic_no_err'] = 'Invalid NIC number format';
        }
    }

    private function validateBasicInfo(&$data) {
        if(empty($data['gender'])) {
            $data['gender_err'] = 'Please add gender';
        }

        $this->validateDOB($data);

        if (empty($data['contact_info'])) {
            $data['contact_info_err'] = 'Please enter your contact number';
        } elseif (!preg_match('/^[0-9]{10}$/', $data['contact_info'])) {
            $data['contact_info_err'] = 'Contact number should be a 10-digit number';
        }

        if(empty($data['slmc_no'])) {
            $data['slmc_no_err'] = 'Please enter SLMC registration number';
        }

        if(empty($data['specialization'])) {
            $data['specialization_err'] = 'Please enter specialization';
        }
    }

    private function validateDOB(&$data) {
        if(empty($data['dob'])) {
            $data['dob_err'] = 'Please add a date of birth';
            return;
        }

        if (!$this->consultantsModel->validateDate($data['dob'])) {
            $data['dob_err'] = 'Invalid date format. Please use YYYY-MM-DD';
            return;
        }

        $dob = new DateTime($data['dob']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        if($age < 18) {
            $data['dob_err'] = 'You must be at least 18 years old to register';
        }
    }

    private function validatePassword(&$data) {
        if(empty($data['password'])) {
            $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6) {
            $data['password_err'] = 'Password must be at least 6 characters';
        } elseif(!preg_match('/[A-Z]/', $data['password'])) {
            $data['password_err'] = 'Password must contain at least one Uppercase Letter';
        } elseif(!preg_match('/[a-z]/', $data['password'])) {
            $data['password_err'] = 'Password must contain at least one Lowercase Letter';
        } elseif(!preg_match('/[0-9]/', $data['password'])) {
            $data['password_err'] = 'Password must contain at least one Number';
        }

        if(empty($data['confirm_password'])) {
            $data['confirm_password_err'] = 'Please confirm password';
        } elseif($data['password'] != $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords do not match';
        }
    }

    private function hasNoValidationErrors($data) {
        return empty($data['username_err']) &&
               empty($data['email_err']) &&
               empty($data['nic_no_err']) &&
               empty($data['password_err']) &&
               empty($data['confirm_password_err']) &&
               empty($data['gender_err']) &&
               empty($data['dob_err']) &&
               empty($data['contact_info_err']) &&
               empty($data['slmc_no_err']) &&
               empty($data['specialization_err']) &&
               empty($data['documents_err']);
    }

    private function getEmptyRegistrationData() {
        return [
            'username' => '',
            'email' => '',
            'nic_no' => '',
            'gender' => '',
            'dob' => '',
            'contact_info' => '',
            'slmc_no' => '',
            'specialization' => '',
            'password' => '',
            'confirm_password' => '',
            'username_err' => '',
            'email_err' => '',
            'nic_no_err' => '',
            'gender_err' => '',
            'dob_err' => '',
            'contact_info_err' => '',
            'slmc_no_err' => '',
            'specialization_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'documents_err' => ''
        ];
    }
}
?>
