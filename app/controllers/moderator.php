<?php 
  class moderator extends controller{
    private $moderatorModel;

    public function __construct(){
      $this->moderatorModel = $this->model('M_Moderator');
    }

    public function careseekerrequests(){
      $data = [];

      $this->view('moderator/v_careseekerrequests', $data);
    }

    public function acceptedcareseekers(){
      $data = [];
      
      $this->view('moderator/v_acceptedcareseekers', $data);
    }

    public function rejectedcareseekers(){
      $data = [];

      $this->view('moderator/v_rejectedcareseekers', $data);
    }

    public function interviewdetails(){
      $data = [];

      $this->view('moderator/v_interviews', $data);
    }

    public function interview(){
      $data = [];

      $this->view('moderator/v_interview', $data);
    }



  }
?>