<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'itenas';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}

class Crud
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create($nim, $nama, $alamat)
    {
        $query = "INSERT INTO mahasiswa (nim, nama, alamat) VALUES ('$nim', '$nama', '$alamat')";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil menambahkan data mahasiswa';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function read()
    {
        $query = "SELECT * FROM mahasiswa";
        return $this->conn->query($query);
    }

    public function update($id, $nim, $nama, $alamat)
    {
        $query = "UPDATE mahasiswa SET nim = '$nim', nama = '$nama', alamat = '$alamat' WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil update data mahasiswa';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function delete($id)
    {
        $query = "DELETE FROM mahasiswa WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil hapus data mahasiswa';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }
}

$database = new Database();
$db = $database->getConnection();
$crud = new Crud($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $create = $crud->create($_POST['nim'], $_POST['nama'], $_POST['alamat']);
        header('Location: index.php?status=' . $create . '');
    } elseif (isset($_POST['update'])) {
        $update = $crud->update($_POST['id'], $_POST['nim'], $_POST['nama'], $_POST['alamat']);
        header('Location: index.php?status=' . $update . '');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $delete = $crud->delete($_GET['id']);
    header('Location: index.php?status=' . $delete . '');
}