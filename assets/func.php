

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
            $this->db   = "kelompok8.te24e.my.id";
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
        $q = mysqli_query($this->koneksi(), "SELECT nama, username, level, kota FROM user WHERE username = '$username'");
        $d = mysqli_fetch_row($q);
        return $d;
    }

    public function user_to_idtarif($username) {
        $q = mysqli_query($this->koneksi(), "SELECT tipe FROM user WHERE username = '$username'");
        $d = mysqli_fetch_row($q);
        $tipe = $d[0];

        $id_tarif = $this->tipe_to_idtarif($tipe);
        return $id_tarif;
    }

    public function tipe_to_idtarif($tipe) {
        $q = mysqli_query($this->koneksi(), "SELECT id_tarif FROM tarif WHERE tipe = '$tipe' AND status = 'AKTIF'");
        $d = mysqli_fetch_row($q);
        return $d[0];
    }

    public function idtarif_to_tarif($id_tarif) {
        $q = mysqli_query($this->koneksi(), "SELECT tarif FROM tarif WHERE id_tarif = '$id_tarif' AND status = 'AKTIF'");
        $d = mysqli_fetch_row($q);
        return $d[0];
    }

    public function tgl_balik($tgl) {
        $e = explode("-", $tgl);
        $tgl_baru = "$e[2]-$e[1]-$e[0]";
        return $tgl_baru;
    }

    // public function koneksi() {
    //     $koneksi = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
    //     if (!$koneksi) {
    //         die("Koneksi database gagal: " . mysqli_connect_error());
    //     }
    //     return $koneksi;
    // }

    // public function dt_user($username) {
    //     $koneksi = $this->koneksi();
    //     $username = mysqli_real_escape_string($koneksi, $username);
    //     $query = mysqli_query($koneksi, "SELECT nama, username, level, kota FROM user WHERE username = '$username'");
    //     return mysqli_fetch_row($query); 
    // }

    // public function user_to_idtarif($username) {
    //     $koneksi = $this->koneksi();
    //     $username = mysqli_real_escape_string($koneksi, $username);
    //     $query = mysqli_query($koneksi, "SELECT tipe FROM user WHERE username = '$username'");
    //     $tipe = mysqli_fetch_row($query)[0];
    //     return mysqli_fetch_row($query); 
    // }

    // public function tipe_to_idtarif($tipe) {
    //     $koneksi = $this->koneksi();
    //     $tipe = mysqli_real_escape_string($koneksi, $tipe);
    //     $query = mysqli_query($koneksi, "SELECT id_tarif FROM tarif WHERE tipe = '$tipe'");
    //     return mysqli_fetch_row($query); 
    // }

    //  public function dt_meter($id_meter) {
    //     $koneksi = $this->koneksi();
    //     $id_meter = mysqli_real_escape_string($koneksi, $id_meter);
    //     $query = mysqli_query($koneksi, "SELECT id_meter, id_tarif, meter_awal, meter_akhir, tanggal_catat FROM meter WHERE id_meter = '$id_meter'");
    //     return mysqli_fetch_row($query); 
    // }
}
?>