<?php 
class admin extends controller{

  private $adminModel;

  public function __construct(){
    // if(!$_SESSION['user_id']) {
    //   redirect('users/login');
    // }else{
    //   if($_SESSION['user_role'] != 'Admin'){
    //     redirect('pages/permissonerror');
    //   }
    $this->adminModel = $this->model('M_Admin');

//   }


  }

  public function index(){
    $data = [
    ];
    $this->view('admin/v_admin_dashboard', $data);
  }

  public function jobsCompleted() {
    $data = [
        'title' => 'Jobs Completed Dashboard',
        'jobs_stats' => [
            'weekly' => 150,
            'monthly' => 620,
            'yearly' => 7500,
            'total' => 21459
        ]
    ];
    
    $this->view('admin/v_jobs_completed', $data);
}

public function viewCompletedJob($job_id) {
    // Get job details from model
    $data = [
        'title' => 'View Completed Job',
        'job_id' => $job_id,
        'service_type' => 'Nursing Care',
        'provider_name' => 'John Smith',
        'provider_id' => '1234',
        'careseeker_name' => 'Mary Johnson',
        'careseeker_id' => '5678',
        'start_date' => '2023-11-01',
        'end_date' => '2023-11-30',
        'duration' => '30 days',
        'location' => 'Colombo, Sri Lanka',
        'payment_status' => 'Paid',
        'provider_comment' => 'Excellent cooperation from the careseeker. All requirements were clear and the environment was very supportive.',
        'careseeker_comment' => 'Very professional service. The caregiver was punctual and provided excellent care.'
    ];

    $this->view('admin/v_view_completed_job', $data);
}

  public function adduser(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'verify_password' => trim($_POST['verify_password']),
            'role' => trim($_POST['role']),
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'verify_password_err' => '',
            'role_err' => ''
        ];

        // Validation
        if(empty($data['name'])){
            $data['name_err'] = 'Please enter name';
        }

        if(empty($data['email'])){
            $data['email_err'] = 'Please enter email';
        } elseif($this->adminModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
        }

        if(empty($data['password'])){
            $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6){
            $data['password_err'] = 'Password must be at least 6 characters';
        } elseif(!preg_match('/[A-Z]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Uppercase Letter';
        } elseif(!preg_match('/[a-z]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Lowercase Letter';
        } elseif(!preg_match('/[0-9]/', $data['password'])){
            $data['password_err'] = 'Password must contain at least one Number';
        }

        if(empty($data['verify_password'])){
            $data['verify_password_err'] = 'Please confirm password';
        } elseif($data['password'] != $data['verify_password']){
            $data['verify_password_err'] = 'Passwords do not match';
        }

        if(empty($data['role'])){
            $data['role_err'] = 'Please select a role';
        }

        if(empty($data['name_err']) && empty($data['email_err']) && 
           empty($data['password_err']) && empty($data['verify_password_err']) && 
           empty($data['role_err'])){
            
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if($this->adminModel->addUser($data)){
                redirect('admin/user_detailes');
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('admin/v_add_user', $data);
        }
    } else {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'verify_password' => '',
            'role' => '',
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'verify_password_err' => '',
            'role_err' => ''
        ];
        
        $this->view('admin/v_add_user', $data);
    }

  }


public function user_detailes(){
    // Check if flagged filter is applied
    $showFlagged = isset($_GET['flagged']) && $_GET['flagged'] == 'true';
    
    // Get users based on filter
    if ($showFlagged) {
        $users = $this->adminModel->getFlaggedUsers();
    } else {
        $users = $this->adminModel->getAllUsers();
    }
    
    // Get counts for stats cards
    $totalUsers = $this->adminModel->getTotalUsersCount();
    $caregivers = $this->adminModel->getCaregiversCount();
    $consultants = $this->adminModel->getConsultantsCount();
    $careseekers = $this->adminModel->getCareseekerCount();
    $pendingUsers = $this->adminModel->getPendingUsersCount();
    $rejectedUsers = $this->adminModel->getRejectedUsersCount();
    $flaggedUsers = $this->adminModel->getFlaggedUsersCount();
    
    $data = [
        'users' => $users,
        'totalUsers' => $totalUsers,
        'caregivers' => $caregivers,
        'consultants' => $consultants,
        'careseekers' => $careseekers,
        'pendingUsers' => $pendingUsers,
        'rejectedUsers' => $rejectedUsers,
        'flaggedUsers' => $flaggedUsers,
        'showFlagged' => $showFlagged
    ];
    
    $this->view('admin/v_users', $data);
}



  

  public function blog(){
    $data = [
      'title' => 'Blog'
    ];
    $this->view('admin/v_blog', $data);
  }

  public function viewblog(){
    
    $data = [
      'title' => 'View Blog'
    ];
    $this->view('admin/v_view_blog', $data); 
  }

  public function editblog(){
    $data = [
      'title' => 'Add Blog'
    ];
    $this->view('admin/v_edit_blog', $data);
  }

  public function addblog(){
    $data = [
      'title' => 'Add Blog'
    ];
    $this->view('admin/v_add_blog', $data);
  }

  public function viewannouncement() {
    $announcements = $this->adminModel->getAnnouncements();
    $data = [
        'announcements' => $announcements
    ];
    $this->view('admin/v_viewannouncements', $data);
  }

public function editannouncement($announcement_id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'announcement_id' => $announcement_id,
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content']),
            'status' => trim($_POST['status']),
            'title_err' => '',
            'content_err' => '',
            'status_err' => ''
        ];

        // Validation
        if (empty($data['title'])) {
            $data['title_err'] = 'Please enter announcement title';
        }
        if (empty($data['content'])) {
            $data['content_err'] = 'Please enter announcement content';
        }
        if (empty($data['status'])) {
            $data['status_err'] = 'Please select a status';
        }

        if (empty($data['title_err']) && empty($data['content_err']) && empty($data['status_err'])) {
            if ($this->adminModel->updateAnnouncement($data)) {
                redirect('admin/viewannouncement');
            } else {
                die('Something went wrong');
            }
        } else {
            $data['announcement'] = $this->adminModel->getAnnouncementById($announcement_id);
            $this->view('admin/v_editannouncement', $data);
        }
    } else {
        $announcement = $this->adminModel->getAnnouncementById($announcement_id);
        $data = [
            'announcement' => $announcement,
            'title_err' => '',
            'content_err' => '',
            'status_err' => ''
        ];
        $this->view('admin/v_editannouncement', $data);
    }
}

public function deleteannouncement($announcement_id) {
  if ($this->adminModel->deleteAnnouncement($announcement_id)) {
      redirect('admin/viewannouncement');
  } else {
      die('Something went wrong');
  }
}

      public function addannouncement() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'status' => trim($_POST['status']),
                'title_err' => '',
                'content_err' => '',
                'status_err' => ''
            ];

            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter announcement title';
            }
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter announcement content';
            }
            if (empty($data['status'])) {
                $data['status_err'] = 'Please select a status';
            }

            if (empty($data['title_err']) && empty($data['content_err']) && empty($data['status_err'])) {
                if ($this->adminModel->addAnnouncement($data)) {
                    redirect('admin/viewannouncement');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/v_addannouncement', $data);
            }
        } else {
            $data = [
                'title' => '',
                'content' => '',
                'status' => '',
                'title_err' => '',
                'content_err' => '',
                'status_err' => ''
            ];
            $this->view('admin/v_addannouncement', $data);
        }

    }

    // just a function to send a email
public function sendWelcomeEmail() {
    // $email = $_SESSION['user_email'];
    $email = 'nadun202327@gmail.com';
    
    $result = sendEmail(
        $email,
        'Welcome to We4u',
        '<h1>Welcome to We4u!</h1><p>ammo hutto kohomada ithin</p>'
    );
    
    if ($result['success']) {
        // Email sent successfully
        return true;
    } else {
        // Log the error
        error_log($result['message']);
        return false;
    }
}

// In your controller
public function downloadPdf() {
    $content = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1.3;
            color: #000000;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 10px 20px;
            box-sizing: border-box;
        }
        
        .header {
            width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 10px;
        }
        
        .brand {
            float: left;
            font-size: 18pt;
            font-weight: bold;
        }
        
        .date {
            float: right;
        }
        
        .supplier-section {
            text-align: left;
            margin-bottom: 30px;
        }
        
        .supplier-title {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .supplier-info {
            margin: 0;
            padding: 0;
        }
        
        .supplier-info p {
            margin: 3px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        thead tr {
            background-color: #f0f0f0;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border: none;
        }
        
        td {
            border-bottom: 1px solid #eeeeee;
        }
        
        .total-section {
            text-align: right;
            margin: 20px 0;
        }
        
        .total {
            display: inline-block;
            background-color: #6c5ce7;
            color: white;
            padding: 8px 20px;
            min-width: 150px;
            text-align: center;
        }
        
        .footer-separator {
            border-top: 1px solid #dddddd;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">Brand</div>
            <div class="date">Date</div>
        </div>
        
        <div class="supplier-section">
            <div class="supplier-title">Supplier Company INC</div>
            <div class="supplier-info">
                <p>Number: 23456789</p>
                <p>VAT: 23456789</p>
                <p>6522 Abshire Mills</p>
                <p>Port Orfolurt, 05820</p>
                <p>United States</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bank</th>
                    <th>Bank Holder Name</th>
                    <th>Phone Number</th>
                    <th>Account Number</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>Bank of America</td>
                    <td>John Smith</td>
                    <td>+1-555-123-4567</td>
                    <td>123456789</td>
                    <td>$180.00</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Chase Bank</td>
                    <td>Sarah Johnson</td>
                    <td>+1-555-234-5678</td>
                    <td>987654321</td>
                    <td>$144.00</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Wells Fargo</td>
                    <td>Michael Brown</td>
                    <td>+1-555-345-6789</td>
                    <td>456789123</td>
                    <td>$60.00</td>
                </tr>
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total">Total: $384.00</div>
        </div>
        
        <div class="footer-separator"></div>
    </div>
</body>
</html>';
    
    generate_pdf('We4U Invoice', $content, 'we4u_invoice.pdf', 'download');
}




   
  
}// Only one closing brace needed here for the class

?>
