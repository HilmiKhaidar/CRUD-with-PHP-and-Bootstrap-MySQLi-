<?php

class Auth
{
    private $host = 'localhost';
    private $db_name = 'itenas';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function register($nim, $nama, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO mahasiswa (nim, nama, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $nim, $nama, $hashed_password); // Menggunakan prepared statements

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            return false;
        }
    }

    public function login($nim, $password)
    {
        $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nim); // Menggunakan prepared statements
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nim'] = $user['nim'];
                $_SESSION['nama'] = $user['nama'];

                header("Location: index.php");
                exit();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: login.php"); // Memperbaiki kesalahan penempatan spasi
        exit(); // Menambahkan exit() setelah header untuk menghentikan script
    }
}

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Memperbaiki REQUEST_METHOD
    if (isset($_POST['register'])) {
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $password = $_POST['password'];
        $auth->register($nim, $nama, $password);
    } else if (isset($_POST['login'])) {
        $nim = $_POST['nim'];
        $password = $_POST['password'];
        $auth->login($nim, $password);
    } else if (isset($_POST['logout'])) {
        $auth->logout();
    }
}
