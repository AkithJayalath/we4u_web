<?php 
class careseeker extends controller{

    public function viewConsultantProfile(){
        $data=[];
        $this->view('careseeker/v_consultantProfile', $data);
      }

      public function viewCaregiverProfile(){
        $data=[];
        $this->view('careseeker/v_caregiverProfile', $data);
      }


      public function requestCaregiver(){
        $data=[];
        $this->view('careseeker/v_requestCaregiver', $data);
      }


}


?>