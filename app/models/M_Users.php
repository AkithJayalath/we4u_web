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
      $this->db->query('SELECT * FROM User WHERE email = :email');
      $this->db->bind(':email' , $email);

      $row = $this->db->single();

      if($this->db->rowCount() > 0){
        return true;
      }
      else{
        return false;
      }
    }

    // Register the User
    public function register($data){
      $this->db->query('INSERT INTO User(name, email, password) VALUES(:name, :email, :password)');

      $this->db->bind(':name' , $data['name']);
      $this->db->bind(':email' , $data['email']);
      $this->db->bind(':password' , $data['password']);


      if($this->db->execute()){
        return true;
      }
      else {
        return false;
      }
    }

    // Login the User
    public function login($email, $password){
      $this->db->query('SELECT * FROM User WHERE email = :email');
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
  }
?>