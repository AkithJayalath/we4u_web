<?php
  class M_Users{
    private $db; 
    
    public function __construct()
    {
      $this->db = new Database();
    }

    // find the user by user email
    public function findUserByEmail($email){ 
      //:indicate a bind value
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);

      $row = $this->db->single();

      if($this->db->rowCount() > 0){
        return true;
      }
      else{
        return false;
      }
    }

    public function validateDate($date) {
      $d = DateTime::createFromFormat('Y-m-d', $date);
      return $d && $d->format('Y-m-d') === $date;
  }

    // Register the User
    public function register($data){
      $this->db->query('INSERT INTO user(username,email,gender,date_of_birth,password, role) VALUES(:username, :email,:gender,:dob ,:password, :role )');

      $this->db->bind(':username' , $data['username']);
      $this->db->bind(':email' , $data['email']);
      $this->db->bind(':gender' , $data['gender']);
      $this->db->bind(':dob' , $data['dob']);
      $this->db->bind(':password' , $data['password']);
      $this->db->bind(':role' , 'Careseeker');


      if($this->db->execute()){
        return true;
      }
      else {
        return false;
      }
    }

    // Login the User
    public function login($email, $password){
      $this->db->query('SELECT * FROM user WHERE email = :email');
      $this->db->bind(':email' , $email);
      // this will return the entire row that means the password also comes with this so there is a problem 
      $row = $this->db->single();

      $hashed_password =  $row->password;

      if(password_verify($password, $hashed_password)){
        return $row;
      }
      else {
        return false;
      }
    }


    public function getCareseekerProfile($userId){
      $this->db->query('SELECT * FROM careseeker_profile WHERE user_id=:user_id');
      $this->db->bind(':user_id',$userId);
      $results = $this->db->resultSet();

      return $results;
    }
  }
?>