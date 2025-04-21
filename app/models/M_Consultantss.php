
<?php 
class M_Consultantss {
    private $db;

    public function __construct() {
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

    public function register($data, $uploadedFiles) {
        // Insert into the User table
        $this->db->query('INSERT INTO user (username, email, gender, date_of_birth, password, role) 
                          VALUES (:username, :email, :gender, :dob, :password, :role)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dob', $data['dob']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'Consultant'); 

        // Execute the query and check if the insertion was successful
        if ($this->db->execute()) {
            // Get the ID of the newly created user
            $newUserId = $this->db->lastInsertId();

            // Insert into the Caregiver table
            $this->db->query('INSERT INTO consultant (consultant_id,contact_info,slmc_reg_no, expertise,nic_no) 
                              VALUES (:caregiver_id,  :contact_info,:slmc_reg_no, :expertise,:nic_no)');
            $this->db->bind(':caregiver_id', $newUserId);
           
            $this->db->bind(':contact_info', $data['contact_info']);
            $this->db->bind(':slmc_reg_no', $data['slmc_no']);
            $this->db->bind(':expertise', $data['specialization']);
            $this->db->bind(':nic_no', $data['nic_no']);

            // Execute the caregiver-specific insertion
            if ($this->db->execute()) {
                // Insert into the Approval Request table
                $this->db->query('INSERT INTO approvalrequest (user_id, request_type, request_date, status, comments) 
                                  VALUES (:user_id, :request_type, :request_date, :status, :comments)');
                $this->db->bind(':user_id', $newUserId);
                $this->db->bind(':request_type', 'Consultant'); // or any appropriate type
                $this->db->bind(':request_date', date('Y-m-d H:i:s'));
                $this->db->bind(':status', 'Pending'); // Initial status
                $this->db->bind(':comments', '');

                if ($this->db->execute()) {
                    $request_id = $this->db->lastInsertId();

                    // Insert documents into the 'document' table
                    foreach ($uploadedFiles as $file) {
                        $this->db->query('INSERT INTO document (request_id, user_id, document_type, file_path, upload_date) 
                                          VALUES (:request_id, :user_id, :document_type, :file_path, :upload_date)');
                        $this->db->bind(':request_id', $request_id);
                        $this->db->bind(':user_id', $newUserId);
                        $this->db->bind(':document_type', 'Consultant Document'); // or any specific document type
                        $this->db->bind(':file_path', '/documents/approvalDocuments/' . $file);
                        $this->db->bind(':upload_date', date('Y-m-d H:i:s'));

                        if (!$this->db->execute()) {
                            return false; // Fail if any document insertion fails
                        }
                    }

                    return true; // Success
                }
            }
        }

        return false; // Return false if any step fails
    }


    public function getFilteredConsultants($filters, $page, $perPage) {
        $sql = 'SELECT c.*, u.username, u.gender, u.profile_picture 
                FROM consultant c 
                JOIN user u ON c.consultant_id = u.user_id 
                WHERE u.role = "Consultant"';

        $params = $this->buildFilterParams($filters);
        $sql .= $this->buildFilterConditions($filters);
        $sql .= $this->buildSortCondition($filters['sort']);
        
        // Add pagination
        $offset = ($page - 1) * $perPage;
        $sql .= ' LIMIT :offset, :limit';
        $params[':offset'] = $offset;
        $params[':limit'] = $perPage;

        $this->db->query($sql);
        foreach($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    public function getTotalFilteredConsultants($filters) {
        $sql = 'SELECT COUNT(*) as total 
                FROM consultant c 
                JOIN user u ON c.consultant_id = u.user_id 
                WHERE u.role = "Consultant"';

        $params = $this->buildFilterParams($filters);
        $sql .= $this->buildFilterConditions($filters);

        $this->db->query($sql);
        foreach($params as $param => $value) {
            $this->db->bind($param, $value);
        }


        $result = $this->db->single();
        return $result->total;
    }

    public function getAllRegions() {
        $this->db->query('SELECT DISTINCT available_regions FROM consultant WHERE available_regions IS NOT NULL');
        $results = $this->db->resultSet();
        
        $regions = [];
        foreach($results as $result) {
            if(!empty($result->available_regions)) {
                $regionArray = explode(',', $result->available_regions);
                foreach($regionArray as $region) {
                    $trimmedRegion = trim($region);
                    if(!empty($trimmedRegion) && !in_array($trimmedRegion, $regions)) {
                        $regions[] = $trimmedRegion;
                    }
                }
            }
        }
        
        sort($regions);
        return $regions;
    }

    public function getAllSpecialities() {
        $this->db->query('SELECT specializations, expertise FROM consultant');
        $results = $this->db->resultSet();
    
        $specialities = [];
        foreach ($results as $result) {
            if (!empty($result->specializations)) {
                // Split the specializations by comma
                $specialitiesArray = explode(',', $result->specializations);
                foreach ($specialitiesArray as $speciality) { // Use a different variable name here
                    $trimmedSpeciality = trim($speciality);
                    // Add trimmed speciality to the array if it's not already there
                    if (!empty($trimmedSpeciality) && !in_array($trimmedSpeciality, $specialities)) {
                        $specialities[] = $trimmedSpeciality;
                    }
                }.
            }
        }
    
        // Sort the specialities alphabetically
        sort($specialities);
        return $specialities;
    }
    

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function register($data, $uploadedFiles) {
        try {
            $this->db->beginTransaction();

            // Insert user
            $userId = $this->insertUser($data);
            if (!$userId) throw new Exception("User insertion failed");

            // Insert consultant
            if (!$this->insertConsultant($userId, $data)) {
                throw new Exception("Consultant insertion failed");
            }

            // Insert approval request
            $requestId = $this->insertApprovalRequest($userId);
            if (!$requestId) throw new Exception("Approval request insertion failed");

            // Insert documents
            if (!$this->insertDocuments($requestId, $userId, $uploadedFiles)) {
                throw new Exception("Document insertion failed");
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    private function buildFilterParams($filters) {
        $params = [];
        

        if(!empty($filters['username'])) {

            $params[':username'] = '%' . $filters['username'] . '%';
        }

        if(!empty($filters['region'])) {

            $params[':region'] = '%' . $filters['region'] . '%';
        }

        if(!empty($filters['speciality'])) {

            $params[':speciality'] = '%' . $filters['speciality'] . '%';
            $params[':expertise'] = '%' . $filters['speciality'] . '%';
        }

        return $params;
    }

    private function buildFilterConditions($filters) {
        $sql = '';
        
        if(!empty($filters['username'])) {
            $sql .= ' AND u.username LIKE :username';

        }

        if(!empty($filters['region'])) {
            $sql .= ' AND c.available_regions LIKE :region';

        }


        if(!empty($filters['speciality'])) {
            $sql .= ' AND (c.specializations LIKE :speciality OR c.expertise LIKE :expertise)';

        }

        return $sql;
    }

    private function buildSortCondition($sort) {
        switch($sort) {
            case 'rating':
                return ' ORDER BY c.rating DESC';
            case 'price-asc':
                return ' ORDER BY CAST(c.payment_details AS DECIMAL) ASC';
            case 'price-desc':
                return ' ORDER BY CAST(c.payment_details AS DECIMAL) DESC';
            default:
                return ' ORDER BY u.username ASC';
        }
    }
    

    private function insertUser($data) {
        $this->db->query('INSERT INTO user (username, email, gender, date_of_birth, password, role) 
                         VALUES (:username, :email, :gender, :dob, :password, :role)');
        

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dob', $data['dob']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'Consultant');

        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    private function insertConsultant($userId, $data) {
        $this->db->query('INSERT INTO consultant (consultant_id, contact_info, slmc_reg_no, expertise, nic_no) 
                         VALUES (:consultant_id, :contact_info, :slmc_reg_no, :expertise, :nic_no)');
        $this->db->bind(':consultant_id', $userId);
        $this->db->bind(':contact_info', $data['contact_info']);
        $this->db->bind(':slmc_reg_no', $data['slmc_no']);
        $this->db->bind(':expertise', $data['specialization']);
        $this->db->bind(':nic_no', $data['nic_no']);

        return $this->db->execute();
    }

    private function insertApprovalRequest($userId) {
        $this->db->query('INSERT INTO approvalrequest (user_id, request_type, request_date, status, comments) 
                         VALUES (:user_id, :request_type, :request_date, :status, :comments)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':request_type', 'Consultant');
        $this->db->bind(':request_date', date('Y-m-d H:i:s'));
        $this->db->bind(':status', 'Pending');
        $this->db->bind(':comments', '');

        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    private function insertDocuments($requestId, $userId, $uploadedFiles) {
        foreach ($uploadedFiles as $file) {
            $this->db->query('INSERT INTO document (request_id, user_id, document_type, file_path, upload_date) 
                             VALUES (:request_id, :user_id, :document_type, :file_path, :upload_date)');
            
            $this->db->bind(':request_id', $requestId);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':document_type', 'Consultant Document');
            $this->db->bind(':file_path', '/documents/approvalDocuments/' . $file);
            $this->db->bind(':upload_date', date('Y-m-d H:i:s'));

            if (!$this->db->execute()) return false;
        }
        return true;
    }
    
}
?>

