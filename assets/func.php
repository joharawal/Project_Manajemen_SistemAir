<?php
class klas_air {
    // private $host = "localhost";
    // private $user = "teemyid_sembarangsek"; 
    // private $pass = "bebasaja123";
    // private $db   = "teemyid_kelompok8";

    private $host = "localhost";
    private $user = "root"; 
    private $pass = "";
    private $db   = "kelompok8.te24e.my.id";

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