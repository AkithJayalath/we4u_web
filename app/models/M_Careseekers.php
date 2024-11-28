<?php 
class M_Careseekers{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createElderProfile($data) {
        $query = "INSERT INTO elderprofile (
            careseeker_id,
            first_name,
            middle_name,
            last_name,
            relationship_to_careseeker,
            age,
            gender,
            weight,
            height,
            blood_pressure,
            emergency_contact,
            chronic_disease,
            current_health_issues,
            allergies,
            surgical_history,
            family_diseases,
            current_medications,
            special_needs,
            dietary_restrictions,
            profile_picture
        ) VALUES (
            :careseeker_id,
            :first_name,
            :middle_name,
            :last_name,
            :relationship_to_careseeker,
            :age,
            :gender,
            :weight,
            :height,
            :blood_pressure,
            :emergency_contact,
            :chronic_disease,
            :current_health_issues,
            :allergies,
            :surgical_history,
            :family_diseases,
            :current_medications,
            :special_needs,
            :dietary_restrictions,
            :profile_pic
        )";

        // Bind values
        $this->db->query($query);
        $this->db->bind(':careseeker_id', $data['careseeker_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':middle_name', $data['middle_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':relationship_to_careseeker', $data['relationship_to_careseeker']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':height', $data['height']);
        $this->db->bind(':blood_pressure', $data['blood_pressure']);
        $this->db->bind(':emergency_contact', $data['emergency_contact']);
        $this->db->bind(':chronic_disease', $data['chronic_disease']);
        $this->db->bind(':current_health_issues', $data['current_health_issues']);
        $this->db->bind(':allergies', $data['allergies']);
        $this->db->bind(':surgical_history', $data['surgical_history']);
        $this->db->bind(':family_diseases', $data['family_diseases']);
        $this->db->bind(':current_medications', $data['current_medications']);
        $this->db->bind(':special_needs', $data['special_needs']);
        $this->db->bind(':dietary_restrictions', $data['dietary_restrictions']);
        $this->db->bind(':profile_pic', $data['profile_picture_name']);

        // Execute the query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Method to get all elder profiles
    public function getAllElders() {
        $query = "SELECT * FROM elders";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    // Method to get all elder profiles of a specific careseeker
    public function getElderProfilesByCareseeker($careseeker_id) {
        $this->db->query('SELECT * FROM elderprofile WHERE careseeker_id = :careseeker_id');
        $this->db->bind(':careseeker_id', $careseeker_id);
    
        return $this->db->resultSet();
    }
    
    public function getElderProfilesData($elderID) {
        $this->db->query('SELECT * FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id=:careseeker_id');
        $this->db->bind(':careseeker_id', $_SESSION['user_id']);
        $this->db->bind(':elder_id', $elderID);
    
        return $this->db->single();
    }

    // Method to get a single elder profile by ID
    public function getElderProfileById($elderID, $careseeker_id)
{
    $this->db->query('SELECT * FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id = :careseeker_id');
    $this->db->bind(':elder_id', $elderID);
    $this->db->bind(':careseeker_id', $careseeker_id);

    $row = $this->db->single();

    // Check if a profile was found
    if ($this->db->rowCount() > 0) {
        return $row; // Return the elder profile as an object
    } else {
        return false; // Return false if no matching profile
    }
}


    public function deleteElderProfile($elderId) {
        // Prepare query to delete profile
        $query = 'DELETE FROM elderprofile WHERE elder_id = :elder_id AND careseeker_id = :careseeker_id';

        // Bind parameters
        $this->db->query($query);
        $this->db->bind(':elder_id', $elderId);
        $this->db->bind(':careseeker_id', $_SESSION['user_id']);  // Ensure the careseeker can only delete their own profiles

        // Execute query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function updateElderProfile($data,$elderID){
        $sql = "UPDATE elderprofile 
            SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                relationship_to_careseeker = :relationship_to_careseeker,
                age = :age,
                gender = :gender,
                weight = :weight,
                height = :height,
                blood_pressure = :blood_pressure,
                emergency_contact = :emergency_contact,
                chronic_disease = :chronic_disease,
                current_health_issues = :current_health_issues,
                allergies = :allergies,
                health_barriers = :health_barriers,
                surgical_history = :surgical_history,
                family_diseases = :family_diseases,
                current_medications = :current_medications,
                special_needs = :special_needs,
                dietary_restrictions = :dietary_restrictions,
                profile_picture = :profile_picture
            WHERE elder_id = :elder_id AND careseeker_id=:careseeker_id";

    // Prepare the query
    $this->db->query($sql);

    // Bind parameters to the query
    $this->db->bind(':first_name', $data['first_name']);
    $this->db->bind(':middle_name', $data['middle_name']);
    $this->db->bind(':last_name', $data['last_name']);
    $this->db->bind(':relationship_to_careseeker', $data['relationship_to_careseeker']);
    $this->db->bind(':age', $data['age']);
    $this->db->bind(':gender', $data['gender']);
    $this->db->bind(':weight', $data['weight']);
    $this->db->bind(':height', $data['height']);
    $this->db->bind(':blood_pressure', $data['blood_pressure']);
    $this->db->bind(':emergency_contact', $data['emergency_contact']);
    $this->db->bind(':chronic_disease', $data['chronic_disease']);
    $this->db->bind(':current_health_issues', $data['current_health_issues']);
    $this->db->bind(':allergies', $data['allergies']);
    $this->db->bind(':health_barriers', $data['health_barriers']);
    $this->db->bind(':surgical_history', $data['surgical_history']);
    $this->db->bind(':family_diseases', $data['family_diseases']);
    $this->db->bind(':current_medications', $data['current_medications']);
    $this->db->bind(':special_needs', $data['special_needs']);
    $this->db->bind(':dietary_restrictions', $data['dietary_restrictions']);
    $this->db->bind(':profile_picture', $data['profile_picture_name']);
    $this->db->bind(':elder_id', $elderID);
    $this->db->bind(':careseeker_id', $_SESSION['user_id']);

    // Execute the query
    if ($this->db->execute()) {
       return true;
    } else {
        return false;
    }
    
}
}

?>