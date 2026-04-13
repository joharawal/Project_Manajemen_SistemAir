
<?php
class klas_air {

    private $host;
    private $user;
    private $pass;
    private $db;

    public function __construct() {
        // cek apakah di localhost atau hosting
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            // LOCAL (XAMPP)
            $this->host = "localhost";
            $this->user = "root";
            $this->pass = "";
            $this->db   = "air";
        } else {
            // HOSTING (cPanel)
            $this->host = "localhost";
            $this->user = "teemyid_sembarangsek";
            $this->pass = "bebasaja123";
            $this->db   = "teemyid_kelompok8";
        }
    }

    public function koneksi() {
        $koneksi = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        if (!$koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
        return $koneksi;
    }

    public function dt_user($username) {
        $koneksi = $this->koneksi();
        $username = mysqli_real_escape_string($koneksi, $username);
        $query = mysqli_query($koneksi, "SELECT nama, username, level FROM user WHERE username = '$username'");
        return mysqli_fetch_row($query); 
    }
}
?>