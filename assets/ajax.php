<?php
session_start();
include "../assets/func.php";
$air = new klas_air;
$koneksi = $air->koneksi();

if (isset($_POST['p'])) {
    $p = $_POST['p'];

    if ($p == "sumary") {
        $bln = $_POST['t'];
        $level = $_POST['l'] ?? '';
        $user = $_SESSION['user'] ?? '';

        if (empty($bln)) {
            if ($level == 'warga' && !empty($user)) {
                $q_bln = mysqli_query($koneksi, "SELECT MAX(DATE_FORMAT(tgl, '%Y-%m')) as max_bln FROM pemakaian WHERE username='$user'");
            } else {
                $q_bln = mysqli_query($koneksi, "SELECT MAX(DATE_FORMAT(tgl, '%Y-%m')) as max_bln FROM pemakaian");
            }
            if ($q_bln) {
                $d_bln = mysqli_fetch_assoc($q_bln);
                $bln = $d_bln['max_bln'] ?? date('Y-m');
            } else {
                $bln = date('Y-m');
            }
        }

        $data = array();

        if ($level == 'admin' || $level == 'petugas') {
            $q1 = mysqli_query($koneksi, "SELECT COUNT(username) as jml_pelanggan FROM user WHERE level='warga'");
            $d1 = mysqli_fetch_assoc($q1);
            $data[] = array('jml_pelanggan' => $d1['jml_pelanggan']);

            $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian), 0) as pemakaian FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d2 = mysqli_fetch_assoc($q2);
            $data[] = array('pemakaian' => $d2['pemakaian']);

            $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as sdh_dicatat FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d3 = mysqli_fetch_assoc($q3);
            $data[] = array('tercatat' => $d3['sdh_dicatat']);
        } elseif ($level == 'bendahara') {
            $q1 = mysqli_query($koneksi, "SELECT COUNT(username) as jml_pelanggan FROM user WHERE level='warga'");
            $d1 = mysqli_fetch_assoc($q1);
            $data[] = array('jml_pelanggan' => $d1['jml_pelanggan']);

            $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan), 0) as tagihan FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
            $d2 = mysqli_fetch_assoc($q2);
            $data[] = array('pemasukan' => $d2['tagihan']);

            $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as warga_lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
            $d3 = mysqli_fetch_assoc($q3);
            $data[] = array('lunas' => $d3['warga_lunas']);
        } else {
            $q1 = mysqli_query($koneksi, "SELECT pemakaian,tgl,waktu,tagihan,status FROM pemakaian WHERE username='$user' AND tgl LIKE '$bln%'");
            $d1 = mysqli_fetch_assoc($q1);
            if (!empty($d1)) {
                $tgl_parts = explode('-', $d1['tgl']);
                $hari = $tgl_parts[2];
                $data[] = array("pemakaian" => $d1['pemakaian'], "tgl" => $hari, "waktu" => $d1['waktu'], "tagihan" => $d1['tagihan'], "status" => $d1['status']);
            } else {
                $data[] = array("pemakaian" => "0", "tgl" => "-", "waktu" => "-", "tagihan" => "0", "status" => "-");
            }
        }
        echo json_encode($data);
    } elseif ($p == "chart_bar") {
        $user = $_POST['u'] ?? '';
        $level = $_POST['l'] ?? '';
        if ($level != 'warga' || $user == '') {
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, SUM(pemakaian) as pemakaian FROM pemakaian GROUP BY MONTH(tgl) ORDER BY tgl ASC");
        } else {
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, pemakaian FROM pemakaian WHERE username='$user' ORDER BY tgl ASC");
        }
        $response = [];
        while ($d = mysqli_fetch_assoc($q)) {
            $response[] = $air->bln($d['bln']);
            $response[] = $d['pemakaian'];
        }
        echo json_encode($response);

    } elseif ($p == "chart_line") {
        $user = $_POST['u'] ?? '';
        $level = $_POST['l'] ?? '';
        if ($level != 'warga' || $user == '') {
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, SUM(tagihan) as tagihan FROM pemakaian GROUP BY MONTH(tgl) ORDER BY tgl ASC");
        } else {
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, tagihan FROM pemakaian WHERE username='$user' ORDER BY tgl ASC");
        }
        $response = [];
        while ($d = mysqli_fetch_assoc($q)) {
            $response[] = $air->bln($d['bln']);
            $response[] = $d['tagihan'];
        }
        echo json_encode($response);
    }
}
