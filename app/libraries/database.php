<?php
    class Database{  // PDO (php data object) object relational mapping where we can exucute our own sql queries inside php so it will do the corresponding manipulation in the database
        private $host = DB_HOST;
        private $user = DB_USER;
        private $password = DB_PASSWORD; 
        private $dbname = DB_NAME;

        private $dbh;
        private $statement;
        private $error;

        public function __construct(){
            $dsn ='mysql:host='.$this->host.';dbname='.$this->dbname;

            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            // Instantiate PDO
            try{
                $this->dbh = new PDO($dsn,$this->user,$this->password,$options);

            }catch(PDOException $e){
                $this->error =$e ->getMessage();
                echo $this->error;
            }
            
        }

        // Prepared statement
        public function query($sql){
            $this->statement = $this->dbh->prepare($sql);
        }

        // Bind paramenters
        public function bind($param,$value,$type = NULL){
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type= PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type= PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type=PDO::PARAM_NULL;
                        break;
                    default:
                        $type=PDO::PARAM_STR;

                }
            }
            $this->statement->bindValue($param,$value,$type);
        }

        //Execute the prepared statement
        public function execute(){
            return $this->statement->execute();
        }

        // Get multiple records as the result
        public function resultSet(){
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        }

        // Get signle record as the single result
        public function single(){
            $this->execute();
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }

        // Get row count
        public function rowCount(){
            return $this->statement->rowCount();
        }

        public function lastInsertId() {
            return $this->dbh->lastInsertId();
        }

        
    }
?>