<?php
session_start();
include "func.php";
$air = new klas_air;
$koneksi = $air->koneksi();

if (isset($_POST['p'])) {
    $p = $_POST['p'];

    if ($p == "sumary") {
        $bln = $_POST['t'];
        $level = $_POST['level'] ?? '';

        $data = array();

        if ($level == 'admin' || $level == 'petugas') {
            $q1 = mysqli_query($koneksi, "SELECT COUNT(username) as jml_pelanggan FROM user WHERE level='warga'");
            $d1 = mysqli_fetch_assoc($q1);
            $data['jml_pelanggan'] = $d1['jml_pelanggan'];

            $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian), 0) as jml_pemakaian FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d2 = mysqli_fetch_assoc($q2);
            $data['jml_pemakaian'] = $d2['jml_pemakaian'];

            $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as sdh_dicatat FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d3 = mysqli_fetch_assoc($q3);
            $data['tercatat'] = $d3['sdh_dicatat'];
            $data['belum_tercatat'] = max(0, $d1['jml_pelanggan'] - $d3['sdh_dicatat']);
        } elseif ($level == 'bendahara') {
            $q1 = mysqli_query($koneksi, "SELECT COUNT(username) as jml_pelanggan FROM user WHERE level='warga'");
            $d1 = mysqli_fetch_assoc($q1);
            $data['jml_pelanggan'] = $d1['jml_pelanggan'];

            $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan), 0) as total_pemasukan FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
            $d2 = mysqli_fetch_assoc($q2);
            $data['total_pemasukan'] = number_format($d2['total_pemasukan'], 0, ',', '.');

            $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as warga_lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
            $d3 = mysqli_fetch_assoc($q3);
            $data['warga_lunas'] = $d3['warga_lunas'];

            $q4 = mysqli_query($koneksi, "SELECT COUNT(username) as warga_belum_lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Belum Lunas'");
            $d4 = mysqli_fetch_assoc($q4);
            $data['warga_belum_lunas'] = $d4['warga_belum_lunas'];
        } elseif ($level == 'warga') {
            $user_ses = $_SESSION['user'] ?? '';
            $q1 = mysqli_query($koneksi, "SELECT tgl, waktu, pemakaian, tagihan, status FROM pemakaian WHERE username='$user_ses' AND tgl LIKE '$bln%'");
            if ($d1 = mysqli_fetch_assoc($q1)) {
                $tgl_parts = explode('-', $d1['tgl']);
                $tgl_hari = $tgl_parts[2];
                $data['waktu_pencatatan'] = '<h1 class="display-5  mb-0">' . $tgl_hari . '</h1><span class="ms-2 mt-2">' . $d1['waktu'] . '</span>';
                $data['pemakaian'] = $d1['pemakaian'];
                $data['tagihan'] = number_format($d1['tagihan'], 0, ',', '.');
                $data['status'] = $d1['status'];
            } else {
                $data['waktu_pencatatan'] = '<h1 class="display-5 mb-0">-</h1>';
                $data['pemakaian'] = "0";
                $data['tagihan'] = "0";
                $data['status'] = "-";
            }
        }

        echo json_encode($data);
    }
}
