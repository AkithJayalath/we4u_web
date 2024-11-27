<?php class SidebarController extends Controller {

    public function __construct(){

    }

    private function getAdminLinks() {
        return [
            [
                'title' => 'Dashboard',
                'url' => URLROOT . '/admin/index',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240Zm-240 160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Add User',
                'url' => URLROOT . '/admin/adduser',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'User Details',
                'url' => URLROOT . '/admin/user_detailes',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18q77 0 141 18t115 44q30 15 47 44t17 62v112H80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Blog',
                'url' => URLROOT . '/admin/blog',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Announcements',
                'url' => URLROOT . '/admin/viewannouncement',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
                'active' => false
            ]
        ];
    }
    
    private function getCaregiverLinks() {
        return [
            [
                'title' => 'Careseeker Requests',
                'url' => URLROOT . '/moderator/careseekerrequests',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Accepted Careseekers',
                'url' => URLROOT . '/moderator/acceptedcareseekers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Rejected Careseekers',
                'url' => URLROOT . '/moderator/rejectedcareseekers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Interview Details',
                'url' => URLROOT . '/moderator/interviewdetails',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Announcements',
                'url' => URLROOT . '/moderator/announcementdetails',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
                'active' => false
            ]
        ];
    }

    private function getCareseekerLinks(){
        return [
            [
                'title' => 'My Schedule',
                'url' => URLROOT . '/caregiver/schedule',
                'icon' => '<svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#e8eaed"
              >
                <path
                  d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
                />
              </svg>',
                'active' => false
            ],
            [
                'title' => 'Patients',
                'url' => URLROOT . '/caregiver/patients',
                'icon' => 'patients-icon-svg',
                'active' => false
            ]
            // Add more caregiver specific links
        ];
    }

      private function getModeratorLinks() {
          return [
              [
                  'title' => 'Careseeker Requests',
                  'url' => URLROOT . '/moderator/careseekerrequests',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Accepted Careseekers',
                  'url' => URLROOT . '/moderator/acceptedcareseekers',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54 54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Rejected Careseekers',
                  'url' => URLROOT . '/moderator/rejectedcareseekers',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Interview Details',
                  'url' => URLROOT . '/moderator/interviewdetails',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Announcements',
                  'url' => URLROOT . '/moderator/announcementdetails',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
                  'active' => false
              ]
          ];
      }

        public function getSidebarLogo() {
            $userRole = $_SESSION['user_role'];
            
            $logos = [
                'Admin' => '/images/admin-logo.png',
                'caregiver' => '/images/caregiver-logo.png',
                'Careseeker' => '/images/careseeker-logo.png',
                'Moderator' => '/images/moderator-logo.png',
            ];
        
            return isset($logos[$userRole]) ? $logos[$userRole] : $logos['default'];
        }
    
        public function getSidebarLinks() {
            $userRole = $_SESSION['user_role'];
        
            switch($userRole) {
                case 'Admin':
                    return $this->getAdminLinks();
                case 'caregiver':
                    return $this->getCaregiverLinks();
                case 'Careseeker':
                    return $this->getCareseekerLinks();
                case 'Moderator':
                    return $this->getModeratorLinks();
                default:
                    return [];
            }
        }
}

?>


