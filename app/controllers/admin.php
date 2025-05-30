<?php 
class admin extends controller{

  private $adminModel;

  public function __construct(){
    if(!$_SESSION['user_id']) {
      redirect('users/login');
    }else{
      if($_SESSION['user_role'] != 'Admin'){
        redirect('pages/permissonerror');
      }
    }
    $this->adminModel = $this->model('M_Admin');
  }

  public function index(){
    // Get counts for stats cards
    $completedJobs = $this->adminModel->getCompletedJobsCount();
    $rejectedJobs = $this->adminModel->getRejectedJobsCount();
    $pendingJobs = $this->adminModel->getPendingJobsCount();
    $cancelledJobs = $this->adminModel->getCancelledJobsCount();
    $lastWeekCompleted = $this->adminModel->getLastWeekCompletedCount();
    $lastMonthCompleted = $this->adminModel->getLastMonthCompletedCount();
    
    // Get caregiver requests from model
    $careRequests = $this->adminModel->getAllCareRequests();
        
    // Add service_type manually to each caregiving request
    foreach ($careRequests as &$req) {
        $req->service_category = 'Caregiving';
    }

    // Get consultant requests from model
    $consultRequests = $this->adminModel->getAllConsultRequests();
    foreach ($consultRequests as &$req) {
        $req->service_category = 'Consultation';
    }

    // Merge both types of requests
    $mergedRequests = array_merge($careRequests, $consultRequests);

    // Sort by created_at/request_date (newest first)
    usort($mergedRequests, function($a, $b) {
        $dateA = isset($a->created_at) ? $a->created_at : $a->request_date;
        $dateB = isset($b->created_at) ? $b->created_at : $b->request_date;
        return strtotime($dateB) - strtotime($dateA); 
    });

    $data = [
        'completedJobs' => $completedJobs,
        'rejectedJobs' => $rejectedJobs,
        'pendingJobs' => $pendingJobs,
        'cancelledJobs' => $cancelledJobs,
        'lastWeekCompleted' => $lastWeekCompleted,
        'lastMonthCompleted' => $lastMonthCompleted,
        'Requests' => $mergedRequests
    ];
    
    $this->view('admin/v_admin_dashboard', $data);
}

public function payments() {
    // Get payment statistics
    $totalUserEarnings = $this->adminModel->getTotalUserEarnings();
    $totalWE4UEarnings = $this->adminModel->getTotalWE4UEarnings();
    $lastMonthUserEarnings = $this->adminModel->getLastMonthUserEarnings();
    $lastMonthWE4UEarnings = $this->adminModel->getLastMonthWE4UEarnings();
    $totalFineAmount = $this->adminModel->getTotalFineAmount();
    
    // Get all payment records
    $payments = $this->adminModel->getAllPayments();
    
    $data = [
        'totalUserEarnings' => $totalUserEarnings,
        'totalWE4UEarnings' => $totalWE4UEarnings,
        'lastMonthUserEarnings' => $lastMonthUserEarnings,
        'lastMonthWE4UEarnings' => $lastMonthWE4UEarnings,
        'totalFineAmount' => $totalFineAmount,
        'payments' => $payments
    ];
    
    $this->view('admin/v_payments', $data);
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
            
            $passwordToEmail = $data['password'];
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if($this->adminModel->addUser($data)){
                // send a Welcome email to the moderator
                $this->sendModeratorWelcomeEmail($data['email'], $data['name'], $passwordToEmail);
                flash('success', 'User added successfully');
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

  private function sendModeratorWelcomeEmail($email, $name, $password) {
    $result = sendEmail(
        $email,
        'Congratulations! You have been added as a moderator',
        '<h1>Welcome to We4u, ' . $name . '!</h1>
        <p>Here are your login credentials:</p>
        <p><strong>Email:</strong> ' . $email . '<br>
        <strong>Password:</strong> ' . $password . '</p>
        <p>Please log into your account to explore more features and start your journey with us. We recommend changing your password after your first login for security purposes.</p>'
        );
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

public function blog() {
    $blogs = $this->adminModel->getAllBlogs(); // Fetch all blogs (admin/moderator can see all)
    $data = [
        'title' => 'All Blogs',
        'blogs' => $blogs,
        'nextPage' => isset($_GET['page']) ? $_GET['page'] + 1 : 2
    ];
    
    $this->view('admin/v_blog', $data); 
}

public function viewblog($blog_id = null) {
    if ($blog_id === null) {
        redirect('admin/blog'); // Redirect to the blog list if no blog_id is provided
    }

    // Fetch the blog details
    $blog = $this->adminModel->getBlogById($blog_id);
    if (!$blog) {
        redirect('admin/blog'); // Redirect if the blog does not exist
    }

    $data = [
        'blog' => $blog
    ];

    $this->view('admin/v_view_blog', $data);
}

public function editBlog($blog_id = null) {
    if ($blog_id === null) {
        redirect('admin/blog'); // Redirect to the blog list if no blog_id is provided
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Initialize variables
        $imagePath = '';
        $imagePathErr = '';

        // Handle file upload
        if (!empty($_FILES['image_path']['name'])) {
            $imageName = time() . '_' . $_FILES['image_path']['name']; // Generate a unique name for the image
            $imageTmpName = $_FILES['image_path']['tmp_name'];
            $uploadLocation = 'images/blogs'; // Directory for uploaded images

            // Ensure the upload directory exists
            if (!is_dir($uploadLocation)) {
                mkdir($uploadLocation, 0777, true); // Create the directory if it doesn't exist
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($imageTmpName, $uploadLocation . '/' . $imageName)) {
                $imagePath = $uploadLocation . '/' . $imageName;
            } else {
                $imagePathErr = 'Failed to upload the image.';
            }
        } else {
            // Use the existing image if no new image is uploaded
            $imagePath = trim($_POST['existing_image_path']);
        }

        $data = [
            'blog_id' => $blog_id,
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content']),
            'image_path' => $imagePath,
            'title_err' => '',
            'content_err' => '',
            'image_path_err' => $imagePathErr
        ];

        // Validation
        if (empty($data['title'])) {
            $data['title_err'] = 'Please enter blog title';
        }
        if (empty($data['content'])) {
            $data['content_err'] = 'Please enter blog content';
        }

        // Check for errors
        if (empty($data['title_err']) && empty($data['content_err']) && empty($data['image_path_err'])) {
            // Update the blog in the database
            if ($this->adminModel->updateBlog($data)) {
                flash('blog_message', 'Blog updated successfully');
                redirect('admin/viewblog/' . $blog_id);
            } else {
                die('Something went wrong updating the blog.');
            }
        } else {
            // Load the view with errors
            $this->view('admin/v_edit_blog', $data);
        }
    } else {
        // Fetch the blog details
        $blog = $this->adminModel->getBlogById($blog_id);
        if (!$blog) {
            redirect('admin/blog'); // Redirect if the blog does not exist
        }

        $data = [
            'blog_id' => $blog_id,
            'title' => $blog->title,
            'content' => $blog->content,
            'image_path' => $blog->image_path,
            'title_err' => '',
            'content_err' => '',
            'image_path_err' => ''
        ];
        $this->view('admin/v_edit_blog', $data);
    }
}

public function deleteBlog($blog_id) {
    if ($this->adminModel->deleteBlog($blog_id)) {
        redirect('admin/viewblog');
    } else {
        die('Something went wrong deleting blog');
    }
}

public function addblog() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Initialize variables
        $imagePath = '';
        $imagePathErr = '';

        // Handle file upload
        if (!empty($_FILES['image_path']['name'])) {
            $imageName = time() . '_' . $_FILES['image_path']['name']; // Generate a unique name for the image
            $imageTmpName = $_FILES['image_path']['tmp_name'];
            $uploadLocation = 'images/blogs'; // Removed leading slash

            // Use the uploadImage helper function
            if (uploadImage($imageTmpName, $imageName, $uploadLocation)) {
                $imagePath = $uploadLocation . '/' . $imageName;
            } else {
                $imagePathErr = 'Failed to upload the image.';
            }
        } else {
            $imagePathErr = 'Please select an image';
        }

        $data = [
            'user_id' => $_SESSION['user_id'], // Add the user ID (make sure you have sessions enabled)
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content']),
            'image_path' => $imagePath,
            'title_err' => '',
            'content_err' => '',
            'image_path_err' => $imagePathErr
        ];
        error_log(json_encode($data));

        // Validation
        if (empty($data['title'])) {
            $data['title_err'] = 'Please enter a blog title';
        }
        if (empty($data['content'])) {
            $data['content_err'] = 'Please enter blog content';
        }

        // Check for errors
        if (empty($data['title_err']) && empty($data['content_err']) && empty($data['image_path_err'])) {
            // Add blog to the database
            if ($this->adminModel->addBlog($data)) {
                flash('blog_message', 'Blog added successfully');
                redirect('admin/blog'); // Changed from blogs to blog to match your cancel button URL
            } else {
                die('Something went wrong while adding the blog.');
            }
        } else {
            // Load the view with errors
            $this->view('admin/v_add_blog', $data);
        }
    } else {
        // Load the form
        $data = [
            'title' => '',
            'content' => '',
            'image_path' => '',
            'title_err' => '',
            'content_err' => '',
            'image_path_err' => ''
        ];
        $this->view('admin/v_add_blog', $data);
    }
}

    public function viewUserProfile($user_id) {
        // check if the user is admin 
        if($_SESSION['user_role'] !== 'Admin') {
        // PERMISSION DENIED
        redirect('pages/permissiondenied');
        }
        $user_details = $this->adminModel->getUserDetails($user_id);
        $data = [
        'user_details' => $user_details,
        'title' => 'View Careseeker'
        ];
        $this->view('users/v_allUserProfiles', $data);
    }

    public function activateUser($user_id) {
        // Check if the user is admin 
        if($_SESSION['user_role'] !== 'Admin') {
            // PERMISSION DENIED
            redirect('pages/permissiondenied');
        }

        $user_email = $_POST['email'];
        
        if($this->adminModel->activateUser($user_id)) {
            // Send activation email
            $result = sendEmail(
                $user_email,
                'Account Activation',
                '<h1>Account Activation</h1><p>Your account has been activated. You can now log in.</p>'
            );
            flash('success', 'User activated successfully. Activation email sent.');
        } else {
            flash('error', 'Failed to activate user');
        }
        
        redirect('admin/viewUserProfile/' . $user_id);
    }

    public function deactivateUser($user_id) {
        // Check if the user is admin 
        if($_SESSION['user_role'] !== 'Admin') {
            // PERMISSION DENIED
            redirect('pages/permissiondenied');
        }

        $user_email = $_POST['email'];
        
        if($this->adminModel->deactivateUser($user_id)) {
            // send deactivation email
            $result = sendEmail(
                $user_email,
                'Account Deactivation',
                '<h1>Account Deactivation</h1><p>Your account has been deactivated. Please contact support for more information.</p>'
            );

            flash('success', 'User deactivated successfully. Deactivation email.');
        } else {
            flash('error', 'Failed to deactivate user');
        }
        
        redirect('admin/viewUserProfile/' . $user_id);
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
                flash('success', 'Announcement updated successfully');
                redirect('admin/viewannouncement');
            } else {
                flash('error', 'Something went wrong');
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
            flash('success', 'Announcement deleted successfully');
            redirect('admin/viewannouncement');
        } else { 
            flash('error', 'Something went wrong');
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
                    flash('success', 'Announcement added successfully');
                    redirect('admin/viewannouncement');
                } else {
                    flash('error', 'Something went wrong');
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
        'Congratulations! Your Caregiver Application is Approved',
        '<h1>Welcome to We4u!</h1><p>Your request to become a Caregiver has been successfully accepted by our moderators. Congratulations!</p><p>Log into your account to explore more features and start your journey with us.</p><p>Thank you for choosing We4u.</p>'
    );

    // $result = sendEmail(
    //     $email,
    //     'Update on Your Consultant Application',
    //     '<h1>We4u Application Status</h1><p>We regret to inform you that your request to become a Consultant has been rejected.</p><p><strong>Reason for rejection:</strong> ' . htmlspecialchars($reason) . '</p><p>If you believe this decision was made in error or if you would like to reapply with additional information, please contact our support team.</p><p>Thank you for your interest in We4u.</p>'
    // );
    
    if ($result['success']) {
        flash('success', 'Email sent successfully');
        return true;
    } else {
        return false;
    }
}
  
}// Only one closing brace needed here for the class

?>
