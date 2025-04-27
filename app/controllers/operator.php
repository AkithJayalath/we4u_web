<?php 

  class Operator extends Controller {
    private $userModel;
    private $adminModel;
      public function __construct() {
        $this->userModel = $this->model('M_Users');
        $this->adminModel = $this->model('M_Admin');
      }
    

    // public function index() {
    //   $data = [
    //     'title' => 'Operator'
    //   ];
    //   $this->view('operator/v_operator', $data);
    // }

    public function viewcareseeker() {
      $data = [
        'title' => 'View Careseeker'
      ];
      $this->view('users/v_careseekerProfile', $data);
    }

    public function viewmoderator() {
      // check if the user is admin 
      if($_SESSION['user_role'] !== 'Admin') {
        // PERMISSION DENIED
        redirect('');
      }


      $data = [
        'title' => 'View Careseeker'
      ];
      $this->view('users/v_allUserProfiles', $data);
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


    public function viewcaregiver(){
      $data = [
        'title' => 'View Caregiver'
      ];
      $this->view('users/v_caregiverProfile', $data);
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
                  redirect('operator/viewannouncement');
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
        redirect('operator/viewannouncement');
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
                      redirect('operator/viewannouncement');
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

  }

?>