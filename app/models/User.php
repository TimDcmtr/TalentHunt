<?php
class User
{
    private $conn;
    private $table_name = "users";

    // --- PROPERTIES (Mapped to Database Columns + Computed fields) ---
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $password; // Only for writing, not reading back in API
    public $school;
    public $location;
    public $field_of_study;

    // Extended Profile
    public $bio;
    public $categories;      // Array
    public $search_type;
    public $work_mode;       // Array
    public $min_salary;
    public $cv_uploaded;     // Boolean
    public $cv_filename;
    public $member_since;    // String formatted (e.g. "November 2024")
    public $application_count;
    public $skills;          // Array

    // Computed property (not in DB)
    public $avatar_initials;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- CREATE (Registration) ---
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    phone = :phone, 
                    password = :password, 
                    school = :school, 
                    location = :location, 
                    field_of_study = :field_of_study,
                    created_at = NOW(),
                    application_count = 0,
                    cv_uploaded = 0";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->school = htmlspecialchars(strip_tags($this->school));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->field_of_study = htmlspecialchars(strip_tags($this->field_of_study));

        // Hash Password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":school", $this->school);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":field_of_study", $this->field_of_study);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- READ (Get Full Profile) ---
    public function getProfileById($id)
    {
        // We select everything except the password for security
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // 1. Basic Fields Mapping
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->school = $row['school'];
            $this->location = $row['location'];
            $this->field_of_study = $row['field_of_study'];
            $this->bio = $row['bio'];
            $this->search_type = $row['search_type'];
            $this->cv_filename = $row['cv_filename'];

            // 2. Typse Casting (SQL returns strings, we want real type)
            $this->min_salary = (int) $row['min_salary'];
            $this->application_count = (int) $row['application_count'];
            $this->cv_uploaded = (bool) $row['cv_uploaded'];

            // 3. Date Formatting
            // Convert MySQL Date "2024-11-01" to "November 2024"
            $date = new DateTime($row['created_at']);
            $this->member_since = $date->format('F Y');

            // 4. JSON Decoding (Convert DB string to PHP Array)
            // The 'true' parameter in json_decode forces associative arrays
            $this->skills = json_decode($row['skills'] ?? '[]', true);
            $this->categories = json_decode($row['categories'] ?? '[]', true);
            $this->work_mode = json_decode($row['work_mode'] ?? '[]', true);

            // 5. Computed Field (Initials)
            // JD = First letter of firstname + First letter of lastname
            $this->avatar_initials = strtoupper(substr($this->firstname, 0, 1) . substr($this->lastname, 0, 1));

            return true;
        }
        return false;
    }

    public function findByEmail()
    {
        // On sélectionne les infos vitales + le mot de passe pour la vérification
        $query = "SELECT id, firstname, lastname, email, password, field_of_study 
              FROM " . $this->table_name . " 
              WHERE email = :email 
              LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        // Nettoyage
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // On hydrate l'objet avec les infos trouvées
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password']; // Le hash crypté
            $this->field_of_study = $row['field_of_study'];

            return true;
        }
        return false;
    }
}
?>