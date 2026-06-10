<?php
class klas_air
{

    private $host;
    private $user;
    private $pass;
    private $db;

    public function __construct()
    {
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

    public function koneksi()
    {
        $koneksi = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        if (!$koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
        return $koneksi;
    }

    public function dt_user($username)
    {
        $q = mysqli_query($this->koneksi(), "SELECT nama, tipe, level, kota FROM user WHERE username = '$username'");
        $d = mysqli_fetch_row($q);
        return $d;
    }

    public function user_to_idtarif($username)
    {
        $q = mysqli_query($this->koneksi(), "SELECT tipe FROM user WHERE username = '$username'");
        $d = mysqli_fetch_row($q);
        $tipe = $d[0];

        $id_tarif = $this->tipe_to_idtarif($tipe);
        return $id_tarif ?? 0;
    }

    public function tipe_to_idtarif($tipe)
    {
        $q = mysqli_query($this->koneksi(), "SELECT id_tarif FROM tarif WHERE tipe = '$tipe' AND status = 'AKTIF'");
        $d = mysqli_fetch_row($q);
        return $d[0] ?? 0;
    }

    public function idtarif_to_tarif($id_tarif)
    {
        $q = mysqli_query($this->koneksi(), "SELECT tarif FROM tarif WHERE id_tarif = '$id_tarif' AND status = 'AKTIF'");
        $d = mysqli_fetch_row($q);
        return $d[0] ?? 0;
    }

    // public function tgl_balik($tgl) {
    //     $e = explode("-", $tgl);
    //     $tgl_baru = "$e[2]-$e[1]-$e[0]";
    //     return $tgl_baru;
    // }

    public function tgl_balik_indo($tgl)
    {
        $e = explode("-", $tgl);
        $bulan = ["01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"];
        $tgl_baru = "$e[2] {$bulan[$e[1]]} $e[0]";
        return $tgl_baru;
    }

    public function bln($no)
    {
        if ($no == 1) $bln = "Januari";
        elseif ($no == 2) $bln = "Februari";
        elseif ($no == 3) $bln = "Maret";
        elseif ($no == 4) $bln = "April";
        elseif ($no == 5) $bln = "Mei";
        elseif ($no == 6) $bln = "Juni";
        elseif ($no == 7) $bln = "Juli";
        elseif ($no == 8) $bln = "Agustus";
        elseif ($no == 9) $bln = "September";
        elseif ($no == 10) $bln = "Oktober";
        elseif ($no == 11) $bln = "November";
        else $bln = "Desember";
        return $bln;
    }
}
