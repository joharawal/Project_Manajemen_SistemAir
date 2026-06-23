<?php
session_start();
if (empty($_SESSION['user']) && empty($_SESSION['pass'])) {
    echo "<script>window.location.replace('../index.php')</script>";
}

//koneksi ke database MariaDb
include '../assets/func.php';
$air = new klas_air;
$koneksi = $air->koneksi();
$dt_user = $air->dt_user($_SESSION['user']);
$level = $dt_user[2];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Sistem Manajemen Pemakaian Air</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/Icon.png">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <!-- Modern Blue-White Theme -->
    <link href="../css/modern-theme.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/air.js"></script>
    <style>
        #meter_table thead th {
            white-space: nowrap;
            padding: 12px 8px !important;
        }

        #meter_table tbody td {
            vertical-align: middle;
        }

        #meter_table .btn {
            margin: 0 2px;
        }

        .badge-small {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.65rem !important;
        }

        #meter_table .btn-group-custom {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            align-items: center;
        }
    </style>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Sistem Pemakaian Air</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-yellow"><i class="fa-solid fa-house-chimney"></i></span></div>
                            Dashboard
                        </a>
                        <?php
                        if ($level == "admin") {
                        ?>
                            <a class="nav-link" href="index.php?p=user">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-blue"><i class="fa-solid fa-address-card"></i></span></div>
                                Manajemen User
                            </a>
                            <a class="nav-link" href="index.php?p=tarif">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-green"><i class="fa-solid fa-rupiah-sign"></i></span></div>
                                Manajemen Tarif Air
                            </a>
                            <a class="nav-link" href="index.php?p=catat_meter">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-red"><i class="fa-solid fa-house-flood-water"></i></span></div>
                                Pemakaian Warga
                            </a>
                
                        <?php
                        } elseif ($level == "bendahara") {
                        ?>
                            <!-- <a class="nav-link" href="index.php?p=pembayaran_warga">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-yellow"><i class="fa-solid fa-wallet"></i></span></div>
                                Pembayaran Warga
                            </a> -->
                            <a class="nav-link" href="index.php?p=tarif">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-green"><i class="fa-solid fa-rupiah-sign"></i></span></div>
                                Manajemen Tarif Air
                            </a>
                            <a class="nav-link" href="index.php?p=catat_meter">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-red"><i class="fa-solid fa-house-flood-water"></i></span></div>
                                Pemakaian Warga
                            </a>
                            <!-- <a class="nav-link" href="index.php?p=tagihan_warga_bendahara">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-green"><i class="fas fa-tachometer-alt"></i></span></div>
                                Tagihan Warga
                            </a> -->
                        <?php
                        } elseif ($level == "petugas") {
                        ?>
                            <a class="nav-link" href="index.php?p=catat_meter">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-red"><i class="fa-solid fa-house-flood-water"></i></span></div>
                                Catat Meter Air
                            </a>
                            <!-- <a class="nav-link" href="index.php?p=lihat_pemakaian_warga">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-green"><i class="fas fa-tachometer-alt"></i></span></div>
                                Lihat Pemakaian Air
                            </a> -->

                        <?php
                        } elseif ($level == "warga") {
                        ?>
                            <a class="nav-link" href="index.php?p=lihat_pemakaian_warga">
                                <div class="sb-nav-link-icon"><span class="nav-icon-box nav-icon-blue"><i class="fa-solid fa-gauge-high"></i></span></div>
                                Lihat Pemakaian
                            </a>

                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small"><i class="fa-regular fa-circle-user fa-bounce text-light"></i> Logged in as : <?php echo $dt_user[2] ?></div>
                    <div class="small"><i class="fa-regular fa-id-badge text-warning fa-shake me-1"></i><span class="text-warning"><?php echo $dt_user[0] . ' (' . $dt_user[3] . ')'; ?></span></div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php
                    // echo $_SERVER['REQUEST_URI'];
                    $e = explode("=", $_SERVER['REQUEST_URI']);
                    // echo "<BR> [0]: $e[0] --> [1]: $e[1]";
                    if (!empty($e[1])) {
                        if ($e[1] == "user" || $e[1] == "user_edit&user") {
                            $h1 = "Manajemen User";
                            $li = "Menu Untuk CRUD User";
                        } elseif ($e[1] == "pemakaian_warga") {
                            $h1 = "Lihat Pemakaian Warga";
                            $li = "Lihat Data Pemakaian Air Warga";
                        } elseif ($e[1] == "pembayaran_warga") {
                            $h1 = "Lihat Pembayaran Warga";
                            $li = "Lihat Data Pembayaran Air Warga";
                        } elseif ($e[1] == "catat_meter" || $e[1] == "meter_edit&no") {
                            $h1 = "Pencatatan Meter";
                            $li = "Pencatatan Meter Air Warga";
                        } elseif ($e[1] == "tarif" || $e[1] == "tarif_edit&id_tarif") {
                            $h1 = "Manajemen Tarif Air";
                            $li = "Menu Untuk CRUD Tarif Air";
                        } elseif ($e[1] == "tagihan_warga_bendahara") {
                            $h1 = "Lihat Tagihan Warga";
                            $li = "Lihat Data Tagihan Air Warga";
                        } elseif ($e[1] == "lihat_pemakaian_warga") {
                            $h1 = "Pemakaian & Tagihan Air";
                            $li = "Data Pemakaian & Tagihan Air";
                        } elseif ($e[1] == "lihat_tagihan_warga") {
                            $h1 = "Lihat Tagihan Air Warga";
                            $li = "Lihat Data Tagihan Air Warga";
                        }
                    } else {
                        $h1 = "Dashboard";
                        $li = "Dashboard";
                    }
                    ?>
                    <h1 class="mt-4"><?php echo $h1 ?></h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"><?php echo $li ?></li>
                    </ol>
                    <?php
                    // echo "sesi user: " . $_SESSION['user'] . " sesi pass: " . $_SESSION['pass'];

                    // Ambil bulan terakhir dari database berdasarkan level user
                    $user_sesi = $_SESSION['user'] ?? '';
                    if ($level == 'warga' && !empty($user_sesi)) {
                        $q_max_bln = mysqli_query($koneksi, "SELECT MAX(DATE_FORMAT(tgl, '%Y-%m')) as max_bln FROM pemakaian WHERE username='$user_sesi'");
                    } else {
                        $q_max_bln = mysqli_query($koneksi, "SELECT MAX(DATE_FORMAT(tgl, '%Y-%m')) as max_bln FROM pemakaian");
                    }
                    $d_max_bln = mysqli_fetch_assoc($q_max_bln);
                    $bulan_terakhir = $d_max_bln['max_bln'] ?? date('Y-m');

                    // Untuk warga: ambil data pencatatan terakhir (tanggal + waktu)
                    $warga_tgl_terakhir = '';
                    $warga_waktu_terakhir = '';
                    if ($level == 'warga' && !empty($user_sesi)) {
                        $q_last = mysqli_query($koneksi, "SELECT tgl, waktu FROM pemakaian WHERE username='$user_sesi' ORDER BY tgl DESC, waktu DESC LIMIT 1");
                        $d_last = mysqli_fetch_assoc($q_last);
                        if ($d_last) {
                            // Format tanggal jadi DD-MM-YYYY
                            $tgl_parts = explode('-', $d_last['tgl']);
                            $warga_tgl_terakhir = $tgl_parts[2] . '-' . $tgl_parts[1] . '-' . $tgl_parts[0];
                            $warga_waktu_terakhir = $d_last['waktu'];
                        }
                    }
                    ?>
                    <input type="hidden" id="user_level" value="<?php echo $dt_user[2]; ?>">
                    <input type="hidden" id="yuser" value="<?php echo $_SESSION['user'] ?? ''; ?>">
                    <?php if ($level == 'warga') { ?>
                        <input type="hidden" id="warga_tgl_terakhir" value="<?php echo $warga_tgl_terakhir; ?>">
                        <input type="hidden" id="warga_waktu_terakhir" value="<?php echo $warga_waktu_terakhir; ?>">
                    <?php } ?>
                    <div class="row mb-3" id="pilih_waktu">
                        <div class="col-xl-3 col-md-12">
                            <label for="sel1" class="form-label">Pilih Waktu : </label>
                            <select class="form-select" id="sel1" name="pilih_waktu">
                                <option value="">Bulan</option>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    if ($i < 10) {
                                        $i = "0" . $i;
                                    }
                                    $val = date("Y") . "-" . $i;
                                    // Warga: default selalu "Bulan", role lain: auto-pilih bulan terakhir
                                    $selected = ($level != 'warga' && $val == $bulan_terakhir) ? " selected" : "";
                                    echo "<option value=\"$val\"$selected>" . $air->bln($i) . " " . date("Y") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row" id="sumary">
                        <?php if ($level == "admin" || $level == "petugas") { ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_pelanggan"></h1>
                                        <div class="ms-2">Orang</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pelanggan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_pemakaian"></h1>
                                        <div class="ms-2">m<sup>3</sup></div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Total Pemakaian Air</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_tercatat"></h1>
                                        <div class="ms-2">Warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Sudah Dicatat</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_belum_tercatat"></h1>
                                        <div class="ms-2">Warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Belum Dicatat</div>
                                    </div>
                                </div>
                            </div>
                        <?php } elseif ($level == "bendahara") { ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_pelanggan"></h1>
                                        <div class="ms-2">Orang</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pelanggan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <div class="me-2 mt-2">Rp. </div>
                                        <h1 id="val_pemasukan"></h1>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pemasukan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_lunas"></h1>
                                        <div class="ms-2">Warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Sudah Lunas</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_belum_lunas"></h1>
                                        <div class="ms-2">Warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Belum Lunas</div>
                                    </div>
                                </div>
                            </div>
                        <?php } elseif ($level == "warga") { ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body d-flex justify-content-center align-items-center py-3">
                                        <!-- Mode default: tampil tanggal terakhir penuh (DD-MM-YYYY) -->
                                        <div id="val_waktu_default" class="text-center w-100">
                                            <h1 class="mb-0 text-white">
                                                <?php echo $warga_tgl_terakhir ?: '-'; ?>
                                            </h1>
                                        </div>
                                        <!-- Mode setelah pilih bulan: tampil hari + jam -->
                                        <div id="val_waktu_pencatatan" class="d-flex align-items-center d-none">
                                            <h1 class="display-5 mb-0" id="val_hari_pencatatan">-</h1>
                                            <div class="ms-2 mt-2 small" id="val_jam_pencatatan" style="font-size:1rem;"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white" id="label_waktu_pencatatan">
                                            <?php echo $warga_waktu_terakhir ? 'Pencatatan Terakhir: ' . $warga_waktu_terakhir : 'Waktu Pencatatan'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 id="val_pemakaian_warga">-</h1>
                                        <div class="ms-2 mt-2">m<sup>3</sup></div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pemakaian Air</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <div class="me-2 mt-2">Rp. </div>
                                        <h1 id="val_tagihan_warga">-</h1>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Tagihan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 id="val_status_warga">-</h1>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Status Tagihan</div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row" id="chart">

                        <?php if ($level == "admin" || $level == "bendahara") { ?>
                            <!-- ===== BARIS 1: Total Pemakaian (Line) + Pie RT vs Kos ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Total Pemakaian Air
                                        <span id="tot_pemakaian" class="float-end fw-bold"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myLineChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Jumlah Rumah Kos dan Rumah Tinggal
                                    </div>
                                    <div class="card-body d-flex justify-content-center">
                                        <canvas id="myPieChart" width="100%" height="40"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== BARIS 2: Total Tagihan (Line) + Total Pemasukan (Line) ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Total Tagihan Air
                                        <span id="tot_tagihan" class="float-end fw-bold"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Total Pemasukan
                                        <span id="tot_pemasukan" class="float-end fw-bold"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myPemasukanChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>

                            <!-- ===== BARIS 3: Sudah Dicatat (Bar) + Belum Dicatat (Bar) ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah Warga Tercatat
                                    </div>
                                    <div class="card-body"><canvas id="mySdhDicatatChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah Warga Belum Tercatat
                                    </div>
                                    <div class="card-body"><canvas id="myBlmDicatatChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>

                            <!-- ===== BARIS 4: Tagihan Sudah Lunas (Bar) + Tagihan Belum Lunas (Bar) ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah warga Sudah LUNAS
                                    </div>
                                    <div class="card-body"><canvas id="mySdhLunasChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah warga belum LUNAS
                                    </div>
                                    <div class="card-body"><canvas id="myBlmLunasChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>

                        <?php } elseif ($level == "petugas") { ?>
                            <!-- ===== CHART UNTUK ROLE PETUGAS ===== -->

                            <!-- ===== BARIS 1: Total Pemakaian (Line) + Pie RT vs Kos ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Total Pemakaian Air
                                        <span id="tot_pemakaian" class="float-end fw-bold"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myLineChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Jumlah Rumah Tinggal dan Kos
                                    </div>
                                    <div class="card-body d-flex justify-content-center">
                                        <canvas id="myPieChart" width="100%" height="40"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== BARIS 2: Sudah Dicatat (Bar) + Belum Dicatat (Bar) ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah warga Tercatat
                                    </div>
                                    <div class="card-body"><canvas id="mySdhDicatatChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Jumlah Warga Belum tercatat
                                    </div>
                                    <div class="card-body"><canvas id="myBlmDicatatChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>

                        <?php } else { ?>
                            <!-- ===== CHART UNTUK ROLE WARGA ===== -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Total Pemakaian Air
                                        <span id="tot_pemakaian" class="float-end fw-bold"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Total Pembayaran Air
                                        <span id="tot_tagihan" class="float-end fw-bold"></span>
                                        <span id="tot_blm_lunas" class="float-end fw-bold text-danger me-3"></span>
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                    <?php
                    if (isset($_POST['tombol'])) {
                        $t = $_POST['tombol'];
                        if ($t == "user_add") {
                            $user = $_POST['username'];
                            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            $pass2 = $_POST['password'];
                            $nama = $_POST['nama'];
                            $alamat = $_POST['alamat'];
                            $kota = $_POST['kota'];
                            $tlp = $_POST['tlp'];
                            $level = $_POST['level'];
                            $tipe = $_POST['tipe'];
                            $status = $_POST['status'];

                            //cek username sudah ada atau belum
                            $qc = mysqli_query($koneksi, "SELECT username FROM user WHERE username='$user'");
                            $qj = mysqli_num_rows($qc);
                            // echo "jumlah data: $qj";
                            //username tidak ada
                            if (empty($qj)) {
                                mysqli_query($koneksi, "INSERT INTO user (username, password, level, tipe, status, nama, alamat, kota, tlp) VALUES ('$user','$pass','$level','$tipe','$status',\"$nama\",'$alamat','$kota','$tlp')");
                                if (mysqli_affected_rows($koneksi) > 0) {
                                    echo "<div class='alert alert-success alert-dismissible fade show' id=alert-user>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Berhasil Disimpan
                                            </div>";
                                } else {
                                    echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-user>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Gagal Disimpan
                                            </div>";
                                }
                            } else { //username sudah ada
                                echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-user>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Username $user</strong> Sudah Ada
                                    </div>";
                            }
                        } elseif ($t == "user_edit") {
                            $user = $_POST['username'];
                            $pass = $_POST['password'];
                            // $pass2= $_POST['password'];
                            $nama = $_POST['nama'];
                            $alamat = $_POST['alamat'];
                            $kota = $_POST['kota'];
                            $tlp = $_POST['tlp'];
                            $level = $_POST['level'];
                            $tipe = $_POST['tipe'];
                            $status = $_POST['status'];

                            //cek password yg ada di tabel user
                            $qcp = mysqli_query($koneksi, "SELECT password FROM user WHERE username='$user'");
                            $dcp = mysqli_fetch_row($qcp);
                            $pass_db = $dcp[0];

                            if ($pass == $pass_db) {
                                //tidak ada perubahan password
                                mysqli_query($koneksi, "UPDATE user SET nama= \"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                            } else {
                                //ada perubahan password
                                $pass2 = password_hash($pass, PASSWORD_DEFAULT);
                                mysqli_query($koneksi, "UPDATE user SET password='$pass2', nama= \"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                            }
                            if (mysqli_affected_rows($koneksi) > 0) {
                                echo "<div class='alert alert-success alert-dismissible fade show' id=alert-user>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Berhasil Diupdate
                                            </div>";
                            } else {
                                echo "<div class='alert alert-primary alert-dismissible fade show' id=alert-user>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Tidak Ada Perubahan
                                            </div>";
                                echo "<meta http-equiv='refresh' content='1.5;url=index.php?p=user'>";
                            }
                        } elseif ($t == "user_hapus") {
                            $user = $_POST['user'];
                            mysqli_query($koneksi, "DELETE FROM user WHERE username='$user'");
                            if (mysqli_affected_rows($koneksi) > 0) {
                                echo "<div class='alert alert-success alert-dismissible fade show' id=alert-user>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Berhasil Dihapus
                                    </div>";
                                echo "<meta http-equiv='refresh' content='1.5;url=index.php?p=user'>";
                            } else {
                                echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-user>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Gagal Dihapus
                                    </div>";
                            }
                        } elseif ($t == "tarif_add") {
                            $id_tarif = $_POST['id_tarif'];
                            $tarif = $_POST['tarif'];
                            $tipe_tarif = $_POST['tipe_tarif'];
                            $status = $_POST['status'];

                            $qc = mysqli_query($koneksi, "SELECT id_tarif FROM tarif WHERE id_tarif='$id_tarif'");
                            $qj = mysqli_num_rows($qc);
                            if (empty($qj)) {
                                mysqli_query($koneksi, "INSERT INTO tarif (id_tarif, tarif, tipe, status) VALUES ('$id_tarif','$tarif',\"$tipe_tarif\",'$status')");
                                if (mysqli_affected_rows($koneksi) > 0) {
                                    echo "<div class='alert alert-success alert-dismissible fade show' id=alert-tarif>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Berhasil Disimpan
                                            </div>";
                                } else {
                                    echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-tarif>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Gagal Disimpan
                                            </div>";
                                }
                            } else { //tarif sudah ada
                                echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-tarif>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong> Tarif $id_tarif</strong> Sudah Ada
                                    </div>";
                            }
                        } elseif ($t == "tarif_edit") {
                            $id_tarif = $_POST['id_tarif'];
                            $tarif = $_POST['tarif'];
                            $tipe_tarif = $_POST['tipe_tarif'];
                            $status = $_POST['status'];

                            mysqli_query($koneksi, "UPDATE tarif SET tarif='$tarif', tipe=\"$tipe_tarif\", status='$status' WHERE id_tarif='$id_tarif'");
                            if (mysqli_affected_rows($koneksi) > 0) {
                                echo "<div class='alert alert-success alert-dismissible fade show' id=alert-tarif>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Berhasil Diupdate
                                    </div>";
                            } else {
                                echo "<div class='alert alert-primary alert-dismissible fade show' id=alert-tarif>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Tidak Ada Perubahan
                                    </div>";
                                echo "<meta http-equiv='refresh' content='1.5;url=index.php?p=tarif'>";
                            }
                        } elseif ($t == "tarif_hapus") {
                            $id_tarif = $_POST['id_tarif'];
                            mysqli_query($koneksi, "DELETE FROM tarif WHERE id_tarif='$id_tarif'");
                            if (mysqli_affected_rows($koneksi) > 0) {
                                echo "<div class='alert alert-success alert-dismissible fade show' id=alert-tarif>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Berhasil Dihapus
                                    </div>";
                                echo "<meta http-equiv='refresh' content='1.5;url=index.php?p=tarif'>";
                            } else {
                                echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-tarif>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Gagal Dihapus
                                    </div>";
                            }
                        } elseif ($t == "meter_add") {
                            $username = $_POST['username'];
                            $meter_akhir = $_POST['meter_akhir'];
                            $id_tarif = $air->user_to_idtarif($username);
                            $tarif = $air->idtarif_to_tarif($id_tarif);

                            // Ambil data meter terakhir warga ini
                            $q_last = mysqli_query($koneksi, "SELECT meter_akhir, tgl FROM pemakaian WHERE username='$username' ORDER BY tgl DESC, no DESC LIMIT 1");
                            $d_last = mysqli_fetch_row($q_last);

                            if ($d_last) {
                                // Ada data sebelumnya: meter_awal = meter_akhir terakhir
                                $meter_awal = $d_last[0];
                                $tgl_terakhir = $d_last[1]; // format: YYYY-MM-DD

                                // Cek apakah sudah melewati 1 bulan (30 hari)
                                $tgl_terakhir_obj = date_create($tgl_terakhir);
                                $tgl_sekarang_obj = date_create();
                                $diff_add = date_diff($tgl_terakhir_obj, $tgl_sekarang_obj);
                                $selisih_hari_add = $diff_add->days;

                                // Blok insert jika belum melewati 30 hari (berlaku untuk admin dan petugas)
                                if (($level == "petugas" || $level == "admin") && $selisih_hari_add < 30) {
                                    $nama_bulan_terakhir = date('F Y', strtotime($tgl_terakhir));
                                    echo "<div class='alert alert-warning alert-dismissible fade show' id=alert-meter>
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <strong>Belum 1 Bulan!</strong> Data meter warga ini sudah diinput pada $tgl_terakhir.
                                                </div>";
                                    $meter_awal = null; // reset agar tidak lanjut insert
                                    $meter_akhir = null;
                                }
                            } else {
                                // Belum ada data sebelumnya: meter_awal dari input form
                                $meter_awal = $_POST['meter_awal'];
                            }

                            if (!is_null($meter_awal) && !is_null($meter_akhir)) {
                                //cek meter awal harus lebih kecil dari meter akhir
                                $pemakaian = $meter_akhir - $meter_awal;
                                $tagihan = $tarif * $pemakaian;
                                if ($pemakaian < 0) { //meter akhir lebih kecil dari meter awal atau pemakaian negatif
                                    echo "<div class='alert alert-danger alert-dismissible fade show'>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Meter Akhir</strong> Harus Lebih Besar Dari Meter Awal
                                            </div>";
                                } else { //meter akhir lebih besar dari meter awal atau pemakaian positif
                                    mysqli_query($koneksi, "INSERT INTO pemakaian (username, meter_awal, meter_akhir, pemakaian, tgl, waktu, id_tarif, tagihan, status) VALUES ('$username','$meter_awal','$meter_akhir','$pemakaian',CURRENT_DATE(), CURRENT_TIME(), '$id_tarif', '$tagihan', 'Belum Lunas')");
                                    if (mysqli_affected_rows($koneksi) > 0) {
                                        echo "<div class='alert alert-success alert-dismissible fade show' id=alert-meter>
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <strong>Data</strong> Berhasil Disimpan
                                                </div>
                                                <script>setTimeout(function() { window.location.href = 'index.php?p=catat_meter'; }, 1500);</script>";
                                    } else {
                                        echo "<div class='alert alert-danger alert-dismissible fade show' id=alert-meter>
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <strong>Data</strong> Gagal Disimpan
                                                </div>";
                                    }
                                }
                            }
                        } elseif ($t == "meter_edit") {
                            $no = $_POST['no'];
                            $username = $_POST['username'];
                            $meter_awal = $_POST['meter_awal'];
                            $meter_akhir = $_POST['meter_akhir'];
                            $status_meter = $_POST['status_meter'];
                            $id_tarif = $air->user_to_idtarif($username);
                            $tarif = $air->idtarif_to_tarif($id_tarif);

                            //cek meter awal harus lebih kecil dari meter akhir
                            $pemakaian = $meter_akhir - $meter_awal;
                            $tagihan = $tarif * $pemakaian;
                            if ($pemakaian < 0) { //meter akhir lebih kecil dari meter awal atau pemakaian negatif
                                echo "<div class='alert alert-danger alert-dismissible fade show'>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Meter Akhir</strong> Harus Lebih Besar Dari Meter Awal
                                            </div>";
                            } else { //meter akhir lebih besar dari meter awal atau pemakaian positif
                                mysqli_query($koneksi, "UPDATE pemakaian SET username='$username', meter_awal='$meter_awal', meter_akhir='$meter_akhir', pemakaian='$pemakaian', id_tarif='$id_tarif', tagihan='$tagihan', status='$status_meter' WHERE no='$no'");
                                if (mysqli_affected_rows($koneksi) > 0) {
                                    echo "<div class='alert alert-success alert-dismissible fade show' id=alert-meter>
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <strong>Data</strong> Berhasil Diupdate
                                                </div>
                                                <script>setTimeout(function() { window.location.href = 'index.php?p=catat_meter'; }, 1500);</script>";
                                } else {
                                    echo "<div class='alert alert-primary alert-dismissible fade show' id=alert-meter>
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <strong>Data</strong> Tidak Ada Perubahan
                                                </div>";
                                }
                            }
                        } elseif ($t == "meter_hapus") {
                            $no = $_POST['no'];
                            mysqli_query($koneksi, "DELETE FROM pemakaian WHERE no='$no'");
                            if (mysqli_affected_rows($koneksi) > 0) {
                                echo
                                "<div class='alert alert-success alert-dismissible fade show' id=alert-meter>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Berhasil Dihapus
                                    </div>";
                            } else {
                                echo    "<div class='alert alert-danger alert-dismissible fade show' id=alert-meter>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Gagal Dihapus
                                    </div>";
                            }
                        }
                    }
                    // Handle GET request untuk fetch data edit
                    if (isset($_GET['p'])) {
                        $p = $_GET['p'];
                        if ($p == "user_edit") {
                            $user = $_GET['user'] ?? '';
                            $q = mysqli_query($koneksi, "SELECT username,password,nama,alamat,kota,tlp,level,tipe,status FROM user WHERE username='$user'");
                            $d = mysqli_fetch_row($q);
                            if ($d) {
                                $user = $d[0];
                                $pass = $d[1];
                                $nama = $d[2];
                                $alamat = $d[3];
                                $kota = $d[4];
                                $tlp = $d[5];
                                $level = $d[6];
                                $tipe = $d[7];
                                $status = $d[8];
                            }
                        } else if ($p == "tarif_edit") {
                            $id_tarif = $_GET['id_tarif'];
                            $q = mysqli_query($koneksi, "SELECT * FROM tarif WHERE id_tarif='$id_tarif'");
                            $d = mysqli_fetch_row($q);
                            if ($d) {
                                $tipe_tarif = $d[1];
                                $tarif = $d[2];
                                $status = $d[3];
                            }
                        } else if ($p == "meter_edit") {
                            $no = $_GET['no'];
                            $q = mysqli_query($koneksi, "SELECT * FROM pemakaian WHERE no='$no'");
                            $d = mysqli_fetch_row($q);
                            if ($d) {
                                $username = $d[1];
                                $meter_awal = $d[2];
                                $meter_akhir = $d[3];
                                $id_tarif = $d[7];
                                $tarif = $air->idtarif_to_tarif($id_tarif);
                                $status_meter = $d[9];
                            }
                        } else if ($p == "catat_meter" && $level == "petugas") {
                            // Untuk petugas saat menambah meter baru:
                            // Siapkan data meter_akhir terakhir per warga untuk diisi otomatis via JS
                            $meter_awal_per_warga = array();
                            $q_all_last = mysqli_query($koneksi, "SELECT username, meter_akhir FROM pemakaian WHERE (username, tgl) IN (SELECT username, MAX(tgl) FROM pemakaian GROUP BY username)");
                            while ($d_aw = mysqli_fetch_row($q_all_last)) {
                                $meter_awal_per_warga[$d_aw[0]] = $d_aw[1];
                            }
                        }
                    }
                    ?>

                    <div class="card mb-4" id="form_user">
                        <div class="card-header bg-primary">
                            <i class="fa-solid fa-user-plus me-2 text-light fa-fade"></i>
                            <i class="text-light fw-bold">User</i>
                        </div>
                        <div class="card-body">
                            <form method="post" class="need-validation" id="user_form">
                                <div class="mb-3">
                                    <label for="usernmae" class="form-label">Username :</label>
                                    <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="<?php echo $user ?? ''; ?>" <?php if (($p ?? '') == 'user_edit') echo 'readonly'; ?> required>
                                </div>
                                <div class="mb-3">
                                    <label for="pwd" class="form-label">Password :</label>
                                    <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="password" value="<?php echo $pass ?? ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama :</label>
                                    <input type="text" class="form-control" id="nama" placeholder="Enter Nama" name="nama" value="<?php echo $nama ?? ''; ?>" required>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="alamat">Alamat :</label>
                                    <textarea class="form-control" rows="5" id="alamat" name="alamat"><?php echo $alamat ?? ''; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota :</label>
                                    <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="<?php echo $kota ?? ''; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="tlp" class="form-label">Telepon :</label>
                                    <input type="text" class="form-control" id="tlp" placeholder="Enter Telepon" name="tlp" value="<?php echo $tlp ?? ''; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level :</label>
                                    <select class="form-select" name="level">
                                        <option value="">Level</option>
                                        <?php
                                        $lv = array("admin", "bendahara", "petugas", "warga");
                                        foreach ($lv as $lv2) {
                                            if ($level == $lv2) $sel = "SELECTED";
                                            else $sel = "";
                                            echo "<option value=$lv2 $sel>" . ucwords($lv2) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe :</label>
                                    <select class="form-select" name="tipe">
                                        <option value="">Tipe</option>
                                        <?php
                                        $tp = array("RT", "Kos");
                                        foreach ($tp as $tp2) {
                                            if ($tipe == $tp2) $sel = "SELECTED";
                                            else $sel = "";
                                            echo "<option value=$tp2 $sel>" . ucwords($tp2) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status :</label>
                                    <select class="form-select" name="status">
                                        <option value="">Status</option>
                                        <?php
                                        $st = array("AKTIF", "TIDAK AKTIF");
                                        foreach ($st as $st2) {
                                            if ($status == $st2) $sel = "SELECTED";
                                            else $sel = "";
                                            echo "<option value='$st2' $sel>$st2</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-outline-primary" name="tombol" value="<?php echo (($p ?? '') == 'user_edit') ? 'user_edit' : 'user_add'; ?>"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-4" id="form_tarif">
                        <div class="card-header bg-success">
                            <i class="fa-solid fa-rupiah-sign me-2 text-light fa-fade"></i>
                            <i class="text-light fw-bold"> Tarif</i>
                        </div>
                        <div class="card-body">
                            <form method="post" class="need-validation" id="tarif_form">
                                <div class="mb-3">
                                    <label for="id_tarif" class="form-label">ID Tarif :</label>
                                    <input type="text" class="form-control" id="id_tarif" placeholder="Enter ID Tarif" name="id_tarif" value="<?php echo $id_tarif ?? ''; ?>" <?php if (($p ?? '') == 'tarif_edit') echo 'readonly'; ?> required>
                                </div>
                                <div class="mb-3">
                                    <label for="tipe_tarif" class="form-label">Tipe Tarif :</label>
                                    <select class="form-select" name="tipe_tarif">
                                        <option value="">Tipe Tarif</option>
                                        <?php
                                        $tt = array("RT", "Kos");
                                        foreach ($tt as $tt2) {
                                            if ($tipe_tarif == $tt2) $sel = "SELECTED";
                                            else $sel = "";
                                            echo "<option value=$tt2 $sel>" . ucwords($tt2) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tarif" class="form-label">Tarif :</label>
                                    <input type="number" class="form-control" id="tarif" placeholder="Enter Tarif" name="tarif" value="<?php echo $tarif ?? ''; ?>" required>
                                </div>
                                <?php
                                $status = $status ?? '';
                                $st = array("AKTIF", "TIDAK AKTIF");
                                foreach ($st as $st2) {
                                    if ($status == $st2) $sel = "CHECKED";
                                    else $sel = "";
                                    echo "<div class=\"form-check form-check-inline\">
                                            <input type=radio class=form-check-input id=status name=status value=\"$st2\" $sel>
                                            <label class=form-check-label for=status>$st2</label>
                                         </div>";
                                }
                                ?>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-outline-success" name="tombol" value="<?php echo (($p ?? '') == 'tarif_edit') ? 'tarif_edit' : 'tarif_add'; ?>"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-4" id="form_meter">
                        <div class="card-header bg-danger">
                            <i class="fa-solid fa-water me-2 text-light fa-fade"></i>
                            <i class="text-light fw-bold"> Meter</i>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($e[1] == "meter_edit&no") $dis = 'disabled';
                            else $dis = "";
                            // Siapkan data meter_awal per warga untuk petugas (mode tambah baru)
                            $is_petugas_tambah = ($level == "petugas" && ($p ?? '') == 'catat_meter');
                            if ($is_petugas_tambah && !empty($meter_awal_per_warga)) {
                                $meter_awal_json = json_encode($meter_awal_per_warga);
                            } else {
                                $meter_awal_json = '{}';
                            }
                            ?>

                            <form method="post" class="need-validation" id="meter_form">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nama Warga :</label>
                                    <select class="form-select" name="username" id="select_warga" required <?php echo $dis; ?>>
                                        <option value="">Nama Warga</option>
                                        <?php
                                        $qw = mysqli_query($koneksi, "SELECT username, nama FROM user WHERE level='warga'");
                                        while ($dw = mysqli_fetch_row($qw)) {
                                            if (($username ?? '') == $dw[0]) $sel = "SELECTED";
                                            else $sel = "";
                                            echo "<option value='$dw[0]' $sel>$dw[1]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meter_awal" class="form-label">Meter Awal (m<sup>3</sup>) :</label>
                                    <input type="text" class="form-control" id="meter_awal" placeholder="Enter Meter Awal" name="meter_awal" value="<?php echo $meter_awal ?? ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="meter_akhir" class="form-label">Meter Akhir (m<sup>3</sup>) :</label>
                                    <input type="text" class="form-control" id="meter_akhir" placeholder="Enter Meter Akhir" name="meter_akhir" value="<?php echo $meter_akhir ?? ''; ?>" required>
                                </div>
                                <?php if (($p ?? '') == 'meter_edit' || ($p ?? '') == 'catat_meter') { ?>
                                    <div class="mb-3">
                                        <label for="status_meter" class="form-label">Status :</label>
                                        <select class="form-select" name="status_meter">
                                            <?php
                                            $st_meter = array("BLM LUNAS", "LUNAS");
                                            foreach ($st_meter as $st2) {
                                                if (($status_meter ?? '') == $st2) $sel = "SELECTED";
                                                else $sel = "";
                                                echo "<option value='$st2' $sel>$st2</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-outline-danger" name="tombol" value="<?php echo (($p ?? '') == 'meter_edit') ? 'meter_edit' : 'meter_add'; ?>"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                </div>
                            </form>
                            <?php if ($is_petugas_tambah): ?>
                                <!-- Script: isi meter_awal otomatis berdasarkan warga yang dipilih -->
                                <script>
                                    var meterAwalData = <?php echo $meter_awal_json; ?>;
                                    document.getElementById('select_warga').addEventListener('change', function() {
                                        var selectedUser = this.value;
                                        var inputMeterAwal = document.getElementById('meter_awal');
                                        if (selectedUser && meterAwalData[selectedUser] !== undefined) {
                                            // Warga sudah punya data: isi otomatis & jadikan readonly
                                            inputMeterAwal.value = meterAwalData[selectedUser];
                                            inputMeterAwal.setAttribute('readonly', 'readonly');
                                            inputMeterAwal.placeholder = 'Enter Meter Awal';
                                        } else {
                                            // Warga belum punya data: kosongkan & bisa diisi manual
                                            inputMeterAwal.value = '';
                                            inputMeterAwal.removeAttribute('readonly');
                                            inputMeterAwal.placeholder = 'Enter Meter Awal';
                                        }
                                    });
                                    // Trigger saat load jika warga sudah terpilih
                                    window.addEventListener('load', function() {
                                        var sel = document.getElementById('select_warga');
                                        if (sel && sel.value) sel.dispatchEvent(new Event('change'));
                                    });
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- The Modal -->
                    <div class="modal" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Konfirmasi Hapus Data</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <!-- //Apakah Anda yakin ingin menghapus data ini? -->
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <form method="post">
                                        <button type="submit" name="tombol" id="modal_delete_btn" value="user_hapus" class="btn btn-danger" data-bs-dismiss="modal">Ya</button>
                                    </form>
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card mb-4" id="data_user">
                        <div class="card-header bg-primary">
                            <i class="fa-solid fa-fade fa-address-card me-1 text-light"></i>
                            <i class="text-light fw-bold">Data User</i>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Telepon</th>
                                        <th>Level</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Editing</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $q = mysqli_query($koneksi, "SELECT username,nama,alamat,kota,tlp,level,tipe, status FROm user ORDER BY level ASC ");
                                    while ($d = mysqli_fetch_row($q)) {
                                        $user = $d[0];
                                        $nama = $d[1];
                                        $alamat = $d[2];
                                        $kota = $d[3];
                                        $tlp = $d[4];
                                        $level = $d[5];
                                        $tipe = $d[6];
                                        $status = $d[7];

                                        echo " <tr>
                                                    <td>$user</td>
                                                    <td>$nama</td>
                                                    <td>$alamat</td>
                                                    <td>$kota</td>
                                                    <td>$tlp</td>
                                                    <td>$level</td>
                                                    <td>$tipe</td>
                                                    <td>$status</td>
                                                    <td>
                                                    <a href=index.php?p=user_edit&user=$user><button type=button class='btn btn-outline-success btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
                                                    <button type=button class='btn btn-outline-danger btn-sm' data-bs-toggle=modal data-bs-target=#myModal data_user=$user><i class='fa-solid fa-trash'></i></button> 
                                                    </td>
                                                </tr>";
                                    }
                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-4" id="data_tarif">
                        <div class="card-header bg-success">
                            <i class="fa-solid fa-fade fa-address-book me-1 text-light"></i>
                            <i class="text-light fw-bold">Data Tarif</i>
                        </div>
                        <div class="card-body">
                            <table id="tarif_table">
                                <thead>
                                    <tr>
                                        <th>ID Tarif</th>
                                        <th>Tarif</th>
                                        <th>Tipe Tarif</th>
                                        <th>Status</th>
                                        <th>Editing</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $q = mysqli_query($koneksi, "SELECT id_tarif,tarif,tipe,status FROM tarif ORDER BY id_tarif ASC ");
                                    while ($d = mysqli_fetch_row($q)) {
                                        $id_tarif = $d[0];
                                        $tarif = $d[1];
                                        $tipe_tarif = $d[2];
                                        $status = $d[3];
                                        echo " <tr>
                                                    <td>$id_tarif</td>
                                                    <td>$tarif</td>
                                                    <td>$tipe_tarif</td>
                                                    <td>$status</td>                                                    
                                                    <td>
                                                    <a href=index.php?p=tarif_edit&id_tarif=$id_tarif><button type=button class='btn btn-outline-success btn-sm'><i class='fa-solid fa-pen-to-square'></i> Ubah</button></a>
                                                    <button type=button class='btn btn-outline-danger btn-sm' data-bs-toggle=modal data-bs-target=#myModal data_id_tarif=$id_tarif><i class='fa-solid fa-trash'></i> Hapus</button> 
                                                    </td>
                                            
                                                </tr>";
                                    }
                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-4" id="data_meter">
                        <div class="card-header bg-danger">
                            <i class="fa-solid fa-fade fa-house-flood-water me-1 text-light"></i>
                            <i class="text-light fw-bold">Data Meter Warga</i>
                        </div>
                        <div class="card-body">
                            <table id="meter_table">
                                <thead>
                                    <tr>
                                        <th>Nama Warga</th>
                                        <th>Tipe</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Meter Awal</th>
                                        <th>Meter Akhir</th>
                                        <th>Pemakaian</th>
                                        <?php if ($dt_user[2] != "petugas") { ?>
                                            <th>Tagihan</th>
                                        <?php } ?>
                                        <th>Status</th>
                                        <th>Editing</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $q = mysqli_query($koneksi, "SELECT no,username,meter_awal,meter_akhir,pemakaian,tgl,waktu,tagihan,status FROM pemakaian ORDER BY tgl DESC, username ASC");
                                    while ($d = mysqli_fetch_row($q)) {
                                        $no = $d[0];
                                        $dt_user2 = $air->dt_user($d[1]);
                                        $nama = $dt_user2 ? $dt_user2[0] : $d[1];
                                        $tipe = $dt_user2 ? $dt_user2[1] : $d[1];
                                        $meter_awal = $d[2];
                                        $meter_akhir = $d[3];
                                        $pemakaian = $d[4];
                                        $tgl = $air->tgl_balik_indo($d[5]);
                                        $waktu = $d[6];
                                        $tagihan = $d[7];
                                        $status = $d[8];
                                        $level_login = $dt_user[2];

                                        $tgl_tabel = date_create($d[5]);
                                        $tgl_sekarang = date_create();
                                        $diff = date_diff($tgl_tabel, $tgl_sekarang);
                                        $selisih = $diff->days;

                                        // Tampilkan badge berdasarkan status
                                        if ($status == "BLM LUNAS") {
                                            $badge = "<span class='badge bg-danger badge-small d-block w-100'><i class='fas fa-exclamation-triangle'></i> <strong>BELUM LUNAS</strong></span>";
                                        } else {
                                            $badge = "<span class='badge bg-success badge-small d-block w-100'><i class='fas fa-check-circle'></i> <strong>LUNAS</strong></span>";
                                        }

                                        $tagihan_cell = ($level_login != "petugas") ? "<td>Rp. " . number_format($tagihan, 0, ',', '.') . "</td>" : "";

                                        $tgl_waktu_display = "<div style='line-height: 1.6;'>
                                                    <div><i class='fas fa-calendar-alt' style='color: #007bff; margin-right: 5px;'></i><strong>$tgl</strong></div>
                                                    <div style='margin-top: 5px;'><i class='fas fa-clock' style='color: #28a745; margin-right: 5px;'></i>$waktu</div>
                                                    <div style='margin-top: 8px; padding-top: 5px; border-top: 1px solid #ddd;'><small style='color: #6c757d;'><i class='fas fa-hourglass-half' style='margin-right: 4px;'></i>$selisih hari lalu</small></div>
                                                </div>";

                                        echo " <tr> 
                                                <td>$nama</td>
                                                <td>$tipe</td>
                                                <td>$tgl_waktu_display</td>
                                                <td>$meter_awal (m<sup>3</sup>)</td>
                                                <td>$meter_akhir (m<sup>3</sup>)</td>
                                                <td>$pemakaian (m<sup>3</sup>)</td>
                                                $tagihan_cell
                                                <td>$badge</td>";

                                        if ($level_login == "admin" || $level_login == "bendahara") {
                                            //berlaku untuk admin & bendahara
                                            echo "<td>
                                                <div class='btn-group-custom'>
                                                <a href=index.php?p=meter_edit&no=$no><button type=button class='btn btn-outline-success btn-sm'><i class='fa-solid fa-pen-to-square'></i>Ubah</button></a>
                                                <button type=button class='btn btn-outline-danger btn-sm' data-bs-toggle=modal data-bs-target=#myModal data_no=$no><i class='fa-solid fa-trash'></i>Hapus</button>
                                                </div>
                                                </td>";
                                        } else {
                                            //berlaku untuk petugas
                                            if ($selisih <= 30) {
                                                echo "<td>
                                                <div class='btn-group-custom'>
                                                <a href=index.php?p=meter_edit&no=$no><button type=button class='btn btn-outline-success btn-sm'><i class='fa-solid fa-pen-to-square'></i>Ubah</button></a>
                                                <button type=button class='btn btn-outline-danger btn-sm' data-bs-toggle=modal data-bs-target=#myModal data_no=$no><i class='fa-solid fa-trash'></i>Hapus</button>
                                                </div>
                                                </td>";
                                            } else {
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-4" id="data_pemakaian">
                        <div class="card-header bg-primary">
                            <i class="fa-solid fa-users me-2 text-light fa-fade"></i>
                            <i class="text-light fw-bold">Data Pemakaian & Tagihan Air</i>
                        </div>
                        <div class="card-body">
                            <table id="pemakaian_table">
                                <thead>
                                    <tr>
                                        <th>Waktu Pencatatan Meter</th>
                                        <th>Kode Tarif</th>
                                        <th>Meter Awal</th>
                                        <th>Meter Akhir</th>
                                        <th>Pemakaian</th>
                                        <th>Tagihan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $q_pemakaian = mysqli_query($koneksi, "SELECT no,username,meter_awal,meter_akhir,pemakaian,tgl,waktu,tagihan,status FROM pemakaian WHERE username='" . $_SESSION['user'] . "' ORDER BY tgl DESC");
                                    while ($dp = mysqli_fetch_row($q_pemakaian)) {
                                        $dp_dt_user = $air->dt_user($dp[1]);
                                        $dp_nama = $dp_dt_user ? $dp_dt_user[0] : $dp[1];
                                        $dp_tipe = $dp_dt_user ? $dp_dt_user[1] : $dp[1];
                                        $dp_id_tarif = $air->user_to_idtarif($dp[1]);
                                        $dp_nominal_tarif = $air->idtarif_to_tarif($dp_id_tarif);
                                        $dp_kode_tarif = "Rp. " . number_format((float)$dp_nominal_tarif, 0, ',', '.');
                                        $dp_meter_awal = $dp[2];
                                        $dp_meter_akhir = $dp[3];
                                        $dp_pemakaian = $dp[4];
                                        $dp_tgl = $air->tgl_balik_indo($dp[5]);
                                        $dp_waktu = $dp[6];
                                        $dp_tagihan = $dp[7];
                                        $dp_status = $dp[8];

                                        // Tampilkan badge berdasarkan status
                                        if ($dp_status == "BLM LUNAS") {
                                            $badge = "<span class='badge bg-danger badge-small d-block w-100'><i class='fas fa-exclamation-triangle'></i> <strong>BELUM LUNAS</strong></span>";
                                        } else {
                                            $badge = "<span class='badge bg-success badge-small d-block w-100'><i class='fas fa-check-circle'></i> <strong>LUNAS</strong></span>";
                                        }


                                        echo " <tr>
                                                    <td>$dp_tgl | $dp_waktu</td>
                                                    <td>$dp_kode_tarif</td>
                                                    <td>$dp_meter_awal (m<sup>3</sup>)</td>
                                                    <td>$dp_meter_akhir (m<sup>3</sup>)</td>
                                                    <td>$dp_pemakaian (m<sup>3</sup>)</td>
                                                    <td>Rp. " . number_format($dp_tagihan, 0, ',', '.') . "</td>
                                                    <td>$badge</td>
                                                </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website <?php echo date("Y") ?></div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>

    <!-- ===== TOMBOL FLOATING CHATBOT (POPUP INLINE) ===== -->
    <style>
        /* --- FAB Button --- */
        #chatbot-fab {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1565c0, #0288d1);
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 18px rgba(21,101,192,0.45);
            z-index: 1050;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }
        #chatbot-fab:hover {
            transform: scale(1.1) rotate(8deg);
            box-shadow: 0 8px 24px rgba(21,101,192,0.55);
        }
        #chatbot-fab .fab-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: #e53935;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(229,57,53,0.5); }
            50%       { box-shadow: 0 0 0 6px rgba(229,57,53,0); }
        }
        #chatbot-tooltip {
            position: fixed;
            bottom: 96px;
            right: 28px;
            background: #1565c0;
            color: #fff;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            z-index: 1049;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.25s ease, transform 0.25s ease;
            pointer-events: none;
        }
        #chatbot-tooltip.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Chat Popup Panel --- */
        #chatbot-panel {
            position: fixed;
            bottom: 100px;
            right: 28px;
            width: 360px;
            max-height: 520px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 50px rgba(21,101,192,0.25);
            z-index: 1051;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: chatSlideIn 0.3s cubic-bezier(0.4,0,0.2,1);
            font-family: 'Inter', Arial, sans-serif;
        }
        #chatbot-panel.open {
            display: flex;
        }
        @keyframes chatSlideIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        /* Header */
        .cb-header {
            background: linear-gradient(135deg, #0d47a1, #1565c0, #0288d1);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .cb-header-avatar {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        .cb-header-info { flex: 1; }
        .cb-header-title { color:#fff; font-size:14px; font-weight:700; }
        .cb-header-sub   { color:rgba(255,255,255,0.75); font-size:11px; margin-top:1px; }
        .cb-header-status { display:flex; align-items:center; gap:5px; color:rgba(255,255,255,0.9); font-size:11px; }
        .cb-status-dot { width:8px; height:8px; background:#69f0ae; border-radius:50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(105,240,174,0.5)} 50%{box-shadow:0 0 0 5px rgba(105,240,174,0)} }
        .cb-close {
            background: rgba(255,255,255,0.18); border:none; border-radius:50%;
            width:28px; height:28px; color:#fff; cursor:pointer; font-size:14px;
            display:flex; align-items:center; justify-content:center;
            transition: background 0.2s;
        }
        .cb-close:hover { background: rgba(255,255,255,0.32); }
        /* Chat Box */
        #cb-chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 14px 12px;
            background: #f0f4f8;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-behavior: smooth;
            min-height: 200px;
            max-height: 340px;
        }
        #cb-chat-box::-webkit-scrollbar { width: 4px; }
        #cb-chat-box::-webkit-scrollbar-thumb { background: rgba(21,101,192,0.25); border-radius: 10px; }
        /* Messages */
        .cb-msg-row { display:flex; align-items:flex-end; gap:7px; animation: cbFadeUp 0.3s ease; }
        .cb-msg-row.cb-user-row { flex-direction: row-reverse; }
        @keyframes cbFadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .cb-avatar {
            width:28px; height:28px; border-radius:50%; display:flex;
            align-items:center; justify-content:center; font-size:13px; flex-shrink:0;
        }
        .cb-avatar.bot { background: linear-gradient(135deg,#0d47a1,#1565c0); color:#fff; }
        .cb-avatar.usr { background: linear-gradient(135deg,#1565c0,#0288d1); color:#fff; }
        .cb-msg-content { max-width:78%; display:flex; flex-direction:column; gap:2px; }
        .cb-bubble {
            padding:9px 13px; border-radius:16px; font-size:13px;
            line-height:1.5; box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        }
        .cb-bot-bubble  { background:#fff; color:#1a2332; border-radius:4px 16px 16px 16px; border-left:3px solid #1565c0; }
        .cb-user-bubble { background:linear-gradient(135deg,#1565c0,#0288d1); color:#fff; border-radius:16px 4px 16px 16px; }
        .cb-time { font-size:9px; color:#90a4ae; padding:0 3px; }
        .cb-msg-row.cb-user-row .cb-time { text-align:right; }
        /* Typing */
        .cb-typing { display:flex; align-items:center; gap:7px; animation:cbFadeUp 0.3s ease; }
        .cb-typing-bubble { background:#fff; border-radius:16px; padding:10px 14px; display:flex; gap:4px; border-left:3px solid #1565c0; box-shadow:0 2px 6px rgba(0,0,0,0.07); }
        .cb-dot { width:6px; height:6px; background:#90a4ae; border-radius:50%; animation:cbTyping 1.2s infinite; }
        .cb-dot:nth-child(2){animation-delay:0.2s} .cb-dot:nth-child(3){animation-delay:0.4s}
        @keyframes cbTyping { 0%,60%,100%{transform:translateY(0);background:#90a4ae} 30%{transform:translateY(-5px);background:#1565c0} }
        /* Quick replies */
        .cb-quick {
            background:#f7fafd; padding:8px 12px; border-top:1px solid #dde3ea;
            display:flex; flex-wrap:wrap; gap:6px; flex-shrink:0;
        }
        .cb-qbtn {
            background:#fff; border:1.5px solid #1565c0; color:#1565c0;
            padding:4px 12px; border-radius:20px; font-size:11px; font-weight:500;
            cursor:pointer; transition:all 0.2s; font-family:'Inter',sans-serif;
        }
        .cb-qbtn:hover { background:linear-gradient(135deg,#1565c0,#0288d1); color:#fff; border-color:transparent; }
        /* Input */
        .cb-input-area {
            background:#fff; border-top:1px solid #dde3ea;
            display:flex; align-items:center; padding:10px 12px; gap:8px; flex-shrink:0;
        }
        .cb-input-area input {
            flex:1; border:1.5px solid #dde3ea; border-radius:20px;
            padding:9px 14px; font-size:13px; font-family:'Inter',sans-serif;
            outline:none; color:#1a2332; background:#f7fafd;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .cb-input-area input:focus { border-color:#1565c0; box-shadow:0 0 0 3px rgba(21,101,192,0.12); background:#fff; }
        .cb-input-area input::placeholder { color:#aab4be; }
        .cb-send {
            width:38px; height:38px; border-radius:50%; border:none;
            background:linear-gradient(135deg,#1565c0,#0288d1); color:#fff;
            font-size:15px; cursor:pointer; display:flex; align-items:center; justify-content:center;
            transition:all 0.2s; box-shadow:0 3px 10px rgba(21,101,192,0.4); flex-shrink:0;
        }
        .cb-send:hover { transform:scale(1.1); box-shadow:0 5px 16px rgba(21,101,192,0.55); }

        /* Responsive: lebih kecil di mobile */
        @media (max-width: 480px) {
            #chatbot-panel { width: calc(100vw - 24px); right: 12px; bottom: 88px; }
        }

        /* ====== SPLIT-SCREEN MODE ====== */
        body.cb-split #layoutSidenav_content {
            margin-right: 380px;
            transition: margin-right 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        body.cb-split #chatbot-panel {
            position: fixed;
            top: 62px;           /* tinggi topnav */
            right: 0;
            bottom: 0;
            width: 380px;
            max-height: none;
            height: calc(100vh - 62px);
            border-radius: 0;
            box-shadow: -4px 0 24px rgba(21,101,192,0.15);
            animation: none;
            border-left: 1px solid rgba(21,101,192,0.15);
        }
        body.cb-split #cb-chat-box {
            max-height: none;
            flex: 1;
        }
        body.cb-split #chatbot-fab {
            display: none;
        }
        body.cb-split #chatbot-tooltip {
            display: none;
        }
        /* Split-screen toggle buttons in header */
        .cb-hdr-btns {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .cb-hdr-btn {
            background: rgba(255,255,255,0.18);
            border: none;
            border-radius: 6px;
            width: 28px;
            height: 28px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            font-size: 13px;
        }
        .cb-hdr-btn:hover { background: rgba(255,255,255,0.32); }
        .cb-hdr-btn.active { background: rgba(255,255,255,0.35); box-shadow: 0 0 0 2px rgba(255,255,255,0.5); }
        /* Transition for main content */
        #layoutSidenav_content {
            transition: margin-right 0.35s cubic-bezier(0.4,0,0.2,1);
        }
    </style>

    <div id="chatbot-tooltip">💬 Tanya Asisten Air</div>
    <button id="chatbot-fab" title="Asisten Chatbot" onclick="cbToggle()">
        🤖
        <span class="fab-badge">!</span>
    </button>

    <!-- Panel Chat Popup -->
    <div id="chatbot-panel">
        <!-- Header -->
        <div class="cb-header">
            <div class="cb-header-avatar">🤖</div>
            <div class="cb-header-info">
                <div class="cb-header-title">Asisten Sistem Air</div>
                <div class="cb-header-sub">Kelompok 8 · TE24E</div>
            </div>
            <div class="cb-header-status">
                <div class="cb-status-dot"></div> Online
            </div>
            <div class="cb-hdr-btns">
                <!-- Tombol popup mode -->
                <button class="cb-hdr-btn active" id="cb-btn-popup" onclick="cbSetMode('popup')" title="Mode Popup">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor">
                        <rect x="1" y="3" width="12" height="9" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M4 1h6v2H4z" fill="currentColor" opacity=".5"/>
                    </svg>
                </button>
                <!-- Tombol split-screen mode -->
                <button class="cb-hdr-btn" id="cb-btn-split" onclick="cbSetMode('split')" title="Mode Split Layar">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="1" y="1" width="5.5" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                        <rect x="7.5" y="1" width="5.5" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                    </svg>
                </button>
                <button class="cb-hdr-btn cb-close" onclick="cbClose()" title="Tutup">✕</button>
            </div>
        </div>

        <!-- Chat messages -->
        <div id="cb-chat-box">
            <div class="cb-msg-row">
                <div class="cb-avatar bot">🤖</div>
                <div class="cb-msg-content">
                    <div class="cb-bubble cb-bot-bubble">Halo! Saya asisten Sistem Air. Ada yang bisa saya bantu? 👋</div>
                    <div class="cb-time" id="cb-init-time"></div>
                </div>
            </div>
        </div>

        <!-- Quick Replies -->
        <div class="cb-quick">
            <button class="cb-qbtn" onclick="cbQuickReply('Cara membayar tagihan')">💳 Cara bayar tagihan</button>
            <button class="cb-qbtn" onclick="cbQuickReply('Bagaimana cek pemakaian air?')">💧 Cek pemakaian</button>
            <button class="cb-qbtn" onclick="cbQuickReply('Apa itu meter air?')">📊 Meter air</button>
        </div>

        <!-- Input -->
        <div class="cb-input-area">
            <input type="text" id="cb-pesan" placeholder="Ketik pertanyaan..." />
            <button class="cb-send" onclick="cbKirim()" title="Kirim">➤</button>
        </div>
    </div>

    <script>
        // ====== FAB & PANEL TOGGLE ======
        const cbFab   = document.getElementById('chatbot-fab');
        const cbTip   = document.getElementById('chatbot-tooltip');
        const cbPanel = document.getElementById('chatbot-panel');
        let cbOpen    = false;
        let cbMode    = 'popup'; // 'popup' | 'split'

        // Set waktu pesan awal
        document.getElementById('cb-init-time').textContent = cbNow();

        cbFab.addEventListener('mouseenter', () => { if (!cbOpen) cbTip.classList.add('show'); });
        cbFab.addEventListener('mouseleave', () => cbTip.classList.remove('show'));

        // Buka/tutup via FAB
        function cbToggle() {
            if (!cbOpen) {
                cbOpen = true;
                cbTip.classList.remove('show');
                cbPanel.style.display = 'flex';
                cbPanel.classList.add('open');
                if (cbMode === 'popup') {
                    cbPanel.style.animation = 'none';
                    cbPanel.offsetHeight;
                    cbPanel.style.animation = 'chatSlideIn 0.3s cubic-bezier(0.4,0,0.2,1)';
                }
                document.getElementById('cb-pesan').focus();
            } else {
                cbClose();
            }
        }

        // Tutup chat (kembali ke FAB)
        function cbClose() {
            cbOpen = false;
            cbPanel.classList.remove('open');
            cbPanel.style.display = 'none';
            document.body.classList.remove('cb-split');
            cbMode = 'popup';
            document.getElementById('cb-btn-popup').classList.add('active');
            document.getElementById('cb-btn-split').classList.remove('active');
        }

        // Ganti mode: 'popup' atau 'split'
        function cbSetMode(mode) {
            cbMode = mode;
            if (mode === 'split') {
                document.body.classList.add('cb-split');
                document.getElementById('cb-btn-split').classList.add('active');
                document.getElementById('cb-btn-popup').classList.remove('active');
                // Pastikan panel terbuka
                cbOpen = true;
                cbPanel.style.display = 'flex';
                cbPanel.classList.add('open');
            } else {
                document.body.classList.remove('cb-split');
                document.getElementById('cb-btn-popup').classList.add('active');
                document.getElementById('cb-btn-split').classList.remove('active');
                // Kembali ke popup mode
                cbOpen = true;
                cbPanel.style.display = 'flex';
                cbPanel.classList.add('open');
                cbPanel.style.animation = 'none';
                cbPanel.offsetHeight;
                cbPanel.style.animation = 'chatSlideIn 0.3s cubic-bezier(0.4,0,0.2,1)';
            }
            document.getElementById('cb-pesan').focus();
        }

        // ====== UTILITIES ======
        function cbNow() {
            return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        function cbAppend(type, text, time) {
            const box  = document.getElementById('cb-chat-box');
            const isBot = (type === 'bot');

            const row = document.createElement('div');
            row.className = 'cb-msg-row' + (isBot ? '' : ' cb-user-row');

            const av = document.createElement('div');
            av.className = 'cb-avatar ' + (isBot ? 'bot' : 'usr');
            av.textContent = isBot ? '🤖' : '👤';

            const content = document.createElement('div');
            content.className = 'cb-msg-content';

            const bubble = document.createElement('div');
            bubble.className = 'cb-bubble ' + (isBot ? 'cb-bot-bubble' : 'cb-user-bubble');
            if (isBot) bubble.innerHTML = text; else bubble.textContent = text;

            const timeEl = document.createElement('div');
            timeEl.className = 'cb-time';
            timeEl.textContent = time || cbNow();

            content.appendChild(bubble);
            content.appendChild(timeEl);
            row.appendChild(av);
            row.appendChild(content);
            box.appendChild(row);
            box.scrollTop = box.scrollHeight;
        }

        function cbShowTyping() {
            const box = document.getElementById('cb-chat-box');
            const el  = document.createElement('div');
            el.className = 'cb-typing'; el.id = 'cb-typing';
            const av = document.createElement('div');
            av.className = 'cb-avatar bot'; av.textContent = '🤖';
            const bub = document.createElement('div');
            bub.className = 'cb-typing-bubble';
            bub.innerHTML = '<div class="cb-dot"></div><div class="cb-dot"></div><div class="cb-dot"></div>';
            el.appendChild(av); el.appendChild(bub);
            box.appendChild(el);
            box.scrollTop = box.scrollHeight;
        }

        function cbHideTyping() {
            const el = document.getElementById('cb-typing');
            if (el) el.remove();
        }

        // ====== KIRIM PESAN ======
        function cbKirim() {
            const input = document.getElementById('cb-pesan');
            const pesan = input.value.trim();
            if (!pesan) return;

            cbAppend('user', pesan, cbNow());
            input.value = '';
            input.disabled = true;
            cbShowTyping();

            const fd = new FormData();
            fd.append('pesan', pesan);

            setTimeout(() => {
                fetch('../chatbot/chatbot.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    cbHideTyping();
                    cbAppend('bot', data.jawaban, cbNow());
                    input.disabled = false;
                    input.focus();
                })
                .catch(() => {
                    cbHideTyping();
                    cbAppend('bot', '⚠️ Terjadi kesalahan. Silakan coba lagi.', cbNow());
                    input.disabled = false;
                });
            }, 600);
        }

        function cbQuickReply(teks) {
            document.getElementById('cb-pesan').value = teks;
            cbKirim();
        }

        // Enter to send
        document.getElementById('cb-pesan').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); cbKirim(); }
        });
    </script>
    <!-- ===== END CHATBOT FAB ===== -->
    </head>
</body>

</html>