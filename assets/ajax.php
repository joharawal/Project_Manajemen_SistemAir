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

            $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan), 0) as tagihan FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)='lunas'");
            $d2 = mysqli_fetch_assoc($q2);
            $data[] = array('pemasukan' => $d2['tagihan']);

            $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as warga_lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)='lunas'");
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

        // Ambil rentang bulan min-max dari tabel pemakaian
        if ($level != 'warga' || $user == '') {
            $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, SUM(pemakaian) as pemakaian FROM pemakaian GROUP BY MONTH(tgl)");
        } else {
            $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian WHERE username='$user'");
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, pemakaian FROM pemakaian WHERE username='$user'");
        }
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = $d['pemakaian']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    } elseif ($p == "chart_line") {
        $user = $_POST['u'] ?? '';
        $level = $_POST['l'] ?? '';

        // Ambil rentang bulan min-max dari tabel pemakaian
        if ($level != 'warga' || $user == '') {
            $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, SUM(tagihan) as tagihan FROM pemakaian GROUP BY MONTH(tgl)");
        } else {
            $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian WHERE username='$user'");
            $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, tagihan FROM pemakaian WHERE username='$user'");
        }
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = $d['tagihan']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    // =====================================================================
    // CHART UNTUK ROLE ADMIN
    // =====================================================================

    } elseif ($p == "chart_pie_tipe") {
        // Pie chart: Perbandingan jumlah pelanggan RT vs Kos
        $response = [];
        $tipe_list = ['RT', 'Kos'];
        foreach ($tipe_list as $tipe) {
            $q = mysqli_query($koneksi, "SELECT COUNT(username) as jml FROM user WHERE level='warga' AND tipe='$tipe'");
            $d = mysqli_fetch_assoc($q);
            $response[$tipe] = (int)$d['jml'];
        }
        echo json_encode($response);

    } elseif ($p == "chart_line_pemasukan") {
        // Line chart: Total pemasukan (tagihan lunas) per bulan untuk Admin
        // Rentang bulan mengikuti min-max data di tabel pemakaian (semua status)
        $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, SUM(tagihan) as pemasukan FROM pemakaian WHERE LOWER(status)='lunas' GROUP BY MONTH(tgl)");
        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = $d['pemasukan']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    } elseif ($p == "chart_bar_sdh_dicatat") {
        // Bar chart: Jumlah pelanggan yang sudah dicatat per bulan
        // Rentang bulan mengikuti min-max data di tabel pemakaian
        $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, COUNT(username) as jml FROM pemakaian GROUP BY MONTH(tgl)");
        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = (int)$d['jml']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    } elseif ($p == "chart_bar_blm_dicatat") {
        // Bar chart: Jumlah pelanggan yang belum dicatat per bulan
        // Rentang bulan mengikuti min-max data di tabel pemakaian
        $q_total = mysqli_query($koneksi, "SELECT COUNT(username) as total FROM user WHERE level='warga'");
        $d_total = mysqli_fetch_assoc($q_total);
        $total_warga = (int)$d_total['total'];

        $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, COUNT(username) as sdh FROM pemakaian GROUP BY MONTH(tgl)");
        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = (int)$d['sdh']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = max(0, $total_warga - (isset($data_db[$i]) ? $data_db[$i] : 0));
        }
        echo json_encode($response);

    } elseif ($p == "chart_bar_sdh_lunas") {
        // Bar chart: Jumlah warga yang sudah lunas per bulan
        // Pakai LOWER() agar case-insensitive (hosting bisa beda case dengan lokal)
        $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, COUNT(username) as jml FROM pemakaian WHERE LOWER(status)='lunas' GROUP BY MONTH(tgl)");
        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = (int)$d['jml']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    } elseif ($p == "chart_bar_blm_lunas") {
        // Bar chart: Jumlah warga yang belum lunas per bulan
        // Kebalikan dari lunas: semua status yang LOWER-nya bukan 'lunas'
        $q_range = mysqli_query($koneksi, "SELECT MIN(MONTH(tgl)) as bln_min, MAX(MONTH(tgl)) as bln_max FROM pemakaian");
        $range = mysqli_fetch_assoc($q_range);
        $bln_min = (int)($range['bln_min'] ?? 1);
        $bln_max = (int)($range['bln_max'] ?? 1);

        $q = mysqli_query($koneksi, "SELECT MONTH(tgl) as bln, COUNT(username) as jml FROM pemakaian WHERE LOWER(status) != 'lunas' GROUP BY MONTH(tgl)");
        $data_db = [];
        while ($d = mysqli_fetch_assoc($q)) { $data_db[(int)$d['bln']] = (int)$d['jml']; }

        $response = [];
        for ($i = $bln_min; $i <= $bln_max; $i++) {
            $response[] = $air->bln($i);
            $response[] = isset($data_db[$i]) ? $data_db[$i] : 0;
        }
        echo json_encode($response);

    } elseif ($p == "chart_warga_blm_lunas") {
        // Nominal tagihan belum lunas milik warga yang sedang login
        $user = $_POST['u'] ?? '';
        $q = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan), 0) as blm_lunas FROM pemakaian WHERE username='$user' AND LOWER(status) != 'lunas'");
        $d = mysqli_fetch_assoc($q);
        echo json_encode(['blm_lunas' => (float)$d['blm_lunas']]);
    } elseif ($p == "fetch_tarif") {
        // Fetch data tarif untuk diisi ke form edit
        $id_tarif = $_POST['id_tarif'] ?? '';
        $q = mysqli_query($koneksi, "SELECT id_tarif, tipe, tarif, status FROM tarif WHERE id_tarif='$id_tarif'");
        $d = mysqli_fetch_assoc($q);
        if ($d) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_tarif' => $d['id_tarif'],
                    'tipe_tarif' => $d['tipe'],
                    'tarif' => $d['tarif'],
                    'status' => $d['status']
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tarif tidak ditemukan'
            ]);
        }
    }
}
