<?php
class Company
{
    private $conn;
    private $table_name = "companies";

    // --- PROPERTIES (Mapped to Database Columns) ---
    public $id;
    public $name;
    public $logo;
    public $size_range;      // Ex: "50-200 employés"
    public $founded_year;    // Int
    public $headquarters;    // Ex: "Paris, France"
    public $website;
    public $short_description;
    public $description;     // Text long
    public $core_values;     // Array (JSON in DB)
    public $sector;
    public $specialties;     // Array (JSON in DB)
    public $email;
    public $password;        // Only for writing
    public $phone;
    public $linkedin;
    public $twitter;
    public $active_offers;   // Int
    public $employee_count;  // Int

    // --- COMPUTED PROPERTIES (Not in DB) ---
    public $member_since;    // String formatted

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- CREATE (Registration) ---
    public function create()
    {
        // On insère les champs de base lors de l'inscription
        $query = "INSERT INTO " . $this->table_name . " 
                  SET 
                    name = :name, 
                    email = :email, 
                    password = :password, 
                    phone = :phone, 
                    headquarters = :headquarters, 
                    website = :website,
                    sector = :sector,
                    created_at = NOW(),
                    active_offers = 0,
                    logo = 'E'"; // Logo par défaut

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->headquarters = htmlspecialchars(strip_tags($this->headquarters));
        $this->website = htmlspecialchars(strip_tags($this->website));
        $this->sector = htmlspecialchars(strip_tags($this->sector));

        // Hash Password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":headquarters", $this->headquarters);
        $stmt->bindParam(":website", $this->website);
        $stmt->bindParam(":sector", $this->sector);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- READ (Get Full Profile) ---
    public function getProfileById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // 1. Basic Fields Mapping
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->logo = $row['logo'];
            $this->size_range = $row['size_range'];
            $this->headquarters = $row['headquarters'];
            $this->website = $row['website'];
            $this->short_description = $row['short_description'];
            $this->description = $row['description'];
            $this->sector = $row['sector'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->linkedin = $row['linkedin'];
            $this->twitter = $row['twitter'];

            // 2. Type Casting (Int)
            $this->founded_year = (int) $row['founded_year'];
            $this->active_offers = (int) $row['active_offers'];
            $this->employee_count = (int) $row['employee_count'];

            // 3. Date Formatting
            $date = new DateTime($row['created_at']);
            $this->member_since = $date->format('F Y'); // "January 2024"

            // 4. JSON Decoding
            $this->core_values = json_decode($row['core_values'] ?? '[]', true);
            $this->specialties = json_decode($row['specialties'] ?? '[]', true);

            return true;
        }
        return false;
    }

    // --- FIND BY EMAIL (Login) ---
    public function findByEmail()
    {
        $query = "SELECT id, name, email, password, sector 
                  FROM " . $this->table_name . " 
                  WHERE email = :email 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->sector = $row['sector'];

            return true;
        }
        return false;
    }
}
?>