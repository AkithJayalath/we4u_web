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
                'title' => 'Completed Jobs',
                'url' => URLROOT . '/admin/jobsCompleted',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                'active' => false
            ],            
            [
                'title' => 'Announcements',
                'url' => URLROOT . '/admin/viewannouncement',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'My Profile',
                'url' => URLROOT . '/users/viewProfile',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ], 
            
        ];
    }
    
    // private function getCaregiverLinks() {
    //     return [
    //         [
    //             'title' => 'Requests',
    //             'url' => URLROOT . '/caregivers/requests',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'History',
    //             'url' => URLROOT . '/caregivers/caregivingHistory',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Reviews',
    //             'url' => URLROOT . '/caregivers/rateandreview',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Payment',
    //             'url' => URLROOT . '/caregivers/paymentMethod',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'My Profile',
    //             'url' => URLROOT . '/caregivers/viewmyProfile',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
    //             'active' => false
    //         ],          
    //         [
    //             'title' => 'Calendar',
    //             'url' => URLROOT . '/caregivers',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
    //             'active' => false
    //         ],
    //     ];
    // }

    private function getCaregiverLinks() {
        return [
            [
                'title' => 'Requests',
                'url' => URLROOT . '/caregivers/requests',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-280h280v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm-120 400q-33 0-56.5-23.5T80-280v-400q0-33 23.5-56.5T160-760h640q33 0 56.5 23.5T880-680v400q0 33-23.5 56.5T800-200H160Zm0-80h640v-400H160v400Zm0 0v-400 400Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'History',
                'url' => URLROOT . '/caregivers/caregivingHistory',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77t76.5 114T840-480q0 75-28.5 140.5t-77 114t-114 76.5T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Reviews',
                'url' => URLROOT . '/caregivers/rateandreview',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m354-247 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-80l65-281L80-550l288-25 112-265 112 265 288 25-218 189 65 281-247-149L233-80Zm247-350Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Payments',
                'url' => URLROOT . '/caregivers/viewpayments',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160h640v-80H160v80Zm0 160h640v-80H160v80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'PaymentMethod',
                'url' => URLROOT . '/caregivers/paymentMethod',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160h640v-80H160v80Zm0 160h640v-80H160v80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'My Profile',
                'url' => URLROOT . '/caregivers/viewmyProfile',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ],          
            [
                'title' => 'Calendar',
                'url' => URLROOT . '/caregivers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Z"/></svg>',
                'active' => false
            ],
        ];
    }
    

    // private function getCareseekerLinks(){
    //     return [
    //         [
    //             'title' => 'Requests',
    //             'url' => URLROOT . '/caregivers/request',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M680-280q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM200-160v-112q0-34 17.5-62.5T264-378q62-31 126-46.5T520-440q14 0 27 .5t27 1.5q-16 20-27 43.5T533-347l-13-1q-63 0-120 14.5T292-296q-11 5-16.5 15.5T270-260v20h297q5 22 13.5 42T597-160H200Zm280-400q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T560-720q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720q0 33 23.5 56.5T480-640Zm200 280q17 0 28.5-11.5T720-400q0-17-11.5-28.5T680-440q-17 0-28.5 11.5T640-400q0 17 11.5 28.5T680-360Zm0-40Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Rate&Review',
    //             'url' => URLROOT . '/caregivers/rateandreview',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m354-247 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-80l65-281L80-550l288-25 112-265 112 265 288 25-218 189 65 281-247-149L233-80Zm247-350Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'History',
    //             'url' => URLROOT . '/caregivers/caregivingHistory',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Payments',
    //             'url' => URLROOT . '/caregivers/paymentMethod',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'P_History',
    //             'url' => URLROOT . '/caregivers/paymentHistory',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'My Profile',
    //             'url' => URLROOT . '/caregivers/viewmyProfile',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
    //             'active' => false
    //         ]
    //     ];
    // }

    private function getCareseekerLinks(){
        return [
            [
                'title' => 'Elder Profiles',
                'url' => URLROOT . '/careseeker/showElderProfiles',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Sessions',
                'url' => URLROOT . '/careseeker/viewConsultants',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M680-280q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM200-160v-112q0-34 17.5-62.5T264-378q62-31 126-46.5T520-440q14 0 27 .5t27 1.5q-16 20-27 43.5T533-347l-13-1q-63 0-120 14.5T292-296q-11 5-16.5 15.5T270-260v20h297q5 22 13.5 42T597-160H200Z"/></svg>',
                'active' => false
            ],

            [
                'title' => 'Requests',
                'url' => URLROOT . '/careseeker/viewRequests',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-280h280v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm-120 400q-33 0-56.5-23.5T80-280v-400q0-33 23.5-56.5T160-760h640q33 0 56.5 23.5T880-680v400q0 33-23.5 56.5T800-200H160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Payments',
                'url' => URLROOT . '/careseeker/viewPayments',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'My Profile',
                'url' => URLROOT . '/users/viewCareseekerProfile',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ]
        ];
    }
    

    
    // public function getConsultantLinks(){
    //     return [
    //         [
    //             'title' => 'Requests',
    //             'url' => URLROOT . '/consultant/requests',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M680-280q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM200-160v-112q0-34 17.5-62.5T264-378q62-31 126-46.5T520-440q14 0 27 .5t27 1.5q-16 20-27 43.5T533-347l-13-1q-63 0-120 14.5T292-296q-11 5-16.5 15.5T270-260v20h297q5 22 13.5 42T597-160H200Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Schedule',
    //             'url' => URLROOT . '/consultant/schedule',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 280q-17 0-28.5-11.5T440-400q0-17 11.5-28.5T480-440q17 0 28.5 11.5T520-400q0 17-11.5 28.5T480-360Zm-160 0q-17 0-28.5-11.5T280-400q0-17 11.5-28.5T320-440q17 0 28.5 11.5T360-400q0 17-11.5 28.5T320-360Zm320 0q-17 0-28.5-11.5T600-400q0-17 11.5-28.5T640-440q17 0 28.5 11.5T680-400q0 17-11.5 28.5T640-360ZM480-200q-17 0-28.5-11.5T440-240q0-17 11.5-28.5T480-280q17 0 28.5 11.5T520-240q0 17-11.5 28.5T480-200Zm-160 0q-17 0-28.5-11.5T280-240q0-17 11.5-28.5T320-280q17 0 28.5 11.5T360-240q0 17-11.5 28.5T320-200Zm320 0q-17 0-28.5-11.5T600-240q0-17 11.5-28.5T640-280q17 0 28.5 11.5T680-240q0 17-11.5 28.5T640-200Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'Payment History',
    //             'url' => URLROOT . '/consultant/paymentHistory',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Z"/></svg>',
    //             'active' => false
    //         ],
    //         [
    //             'title' => 'My Profile',
    //             'url' => URLROOT . '/consultant/profile',
    //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
    //             'active' => false
    //         ]
    //     ];
    // }

    public function getConsultantLinks(){
        return [
            [
                'title' => 'Requests',
                'url' => URLROOT . '/consultant/viewrequests',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-280h280v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm-120 400q-33 0-56.5-23.5T80-280v-400q0-33 23.5-56.5T160-760h640q33 0 56.5 23.5T880-680v400q0 33-23.5 56.5T800-200H160Zm0-80h640v-400H160v400Zm0 0v-400 400Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Patient List',
                'url' => URLROOT . '/consultant/patientlist',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Reviews',
                'url' => URLROOT . '/consultant/rateandreview',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m354-247 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-80l65-281L80-550l288-25 112-265 112 265 288 25-218 189 65 281-247-149L233-80Zm247-350Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'Payments',
                'url' => URLROOT . '/consultant/viewpayments',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160h640v-80H160v80Zm0 160h640v-80H160v80Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'PaymentMethod',
                'url' => URLROOT . '/consultant/paymentMethod',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160h640v-80H160v80Zm0 160h640v-80H160v80Z"/></svg>',
                'active' => false
            ],

            [
                'title' => 'Schedule',
                'url' => URLROOT . '/consultant',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Z"/></svg>',
                'active' => false
            ],
            [
                'title' => 'My Profile',
                'url' => URLROOT . '/consultant/consultantprofile',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ]
        ];
    }
    

    

      private function getModeratorLinks() {
          return [

              [
                  'title' => 'Requests',
                  'url' => URLROOT . '/moderator/careseekerrequests',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                  'active' => false
              ],
              [
                'title' => 'Pending',
                'url' => URLROOT . '/moderator/pendingrequests',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ],
              [
                  'title' => 'Accepted',
                  'url' => URLROOT . '/moderator/acceptedcareseekers',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54 54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Rejected',
                  'url' => URLROOT . '/moderator/rejectedcareseekers',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Interviews',
                  'url' => URLROOT . '/moderator/interviewdetails',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
                  'active' => false
              ],
              [
                  'title' => 'Announcements',
                  'url' => URLROOT . '/operator/viewannouncement',
                  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-200v-80h640v80H160Zm0-240v-80h640v80H160Zm0-240v-80h640v80H160Z"/></svg>',
                  'active' => false
              ],
              [
                'title' => 'My Profile',
                'url' => URLROOT . '/users/viewProfile',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>',
                'active' => false
            ], 
          ];
      }

        public function getSidebarLogo() {
            $userRole = $_SESSION['user_role'];
            
            $logos = [
                'Admin' => '/images/admin-logo.png',
                'Caregiver' => '/images/caregiver-logo.png',
                'Careseeker' => '/images/careseeker-logo.png',
                'Moderator' => '/images/moderator-logo.png',
                'default'  => '/images/careseeker-logo.png',
                'Consultant' => '/images/consultant-logo.png',
            ];
        
            return isset($logos[$userRole]) ? $logos[$userRole] : $logos['default'];
        }
    
        public function getSidebarLinks() {
            $userRole = $_SESSION['user_role'];
        
            switch($userRole) {
                case 'Admin':
                    return $this->getAdminLinks();
                case 'Caregiver':
                    return $this->getCaregiverLinks();
                case 'Careseeker':
                    return $this->getCareseekerLinks();
                case 'Moderator':
                    return $this->getModeratorLinks();
                case 'Consultant':
                    return $this->getConsultantLinks();
                default:
                    return [];
            }
        }
}

?>


