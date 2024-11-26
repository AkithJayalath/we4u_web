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

      public function requestConsultant(){
        $data=[];
        $this->view('careseeker/v_requestConsultant', $data);
      }

      public function viewRequestInfo(){
        $data=[];
        $this->view('careseeker/v_viewRequestInfo', $data);
      }

      public function viewRequests(){
        $data=[];
        $this->view('careseeker/v_viewRequests', $data);
      }

      public function viewPayments(){
        $data=[];
        $this->view('careseeker/v_viewPayments', $data);
      }

      public function viewConsultants(){
        $data=[];
        $this->view('careseeker/v_viewConsultants', $data);
      }

      public function viewConsultantSession(){
        $data=[];
        $this->view('careseeker/v_viewConsultantSession', $data);
      }



}


?>