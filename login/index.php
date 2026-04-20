<?php
session_start ();
if(empty($_SESSION['user']) && empty($_SESSION['pass'])) {
    echo "<script>window.location.replace('../index.php')</script>";
}

//koneksi ke database MariaDb
include '../assets/func.php';
$air = new klas_air;
$koneksi = $air->koneksi();
$dt_user = $air->dt_user($_SESSION['user']);
$level = strtolower($dt_user[2] ?? '');


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Sistem Manajemen Air</title>
        <link rel="icon" type="icon.png" href="../assets/img/Icon.png">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
     
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
                        <li><hr class="dropdown-divider" /></li>
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
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Dashboard
                            </a>
                            <?php 
                            if ($level=="admin") {
                                ?>
                                <a class="nav-link" href="index.php?p=user">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Manajemen User
                            </a>
                                <a class="nav-link" href="index.php?p=pemakaian_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Pemakaian Air Warga
                            </a>
                                <a class="nav-link" href="index.php?p=manajemen_tarif">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Manajemen Tarif Air
                            </a>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                            <?php 
                            } 
                            elseif($level=="bendahara") {
                            ?>
                                <a class="nav-link" href="index.php?p=manajemen_tarif">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Manajemen Tarif Air
                            </a>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                            <a class="nav-link" href="index.php?p=tagihan_warga_bendahara">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                 Melihat Tagihan Warga
                            </a>
                           
                            <?php
                            }
                             elseif($level=="petugas") {
                            ?>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                            <a class="nav-link" href="index.php?p=pemakaian_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Pemakaian Air Warga
                            </a>
                            <?php
                             }
                             elseif($level=="warga") {
                            ?>
                                <a class="nav-link" href="index.php?p=pemakaian_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                 Melihat Pemakaian
                            </a>
                              <a class="nav-link" href="index.php?p=tagihan_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                 Melihat Tagihan
                            </a>
                               
                            <?php
                             }
                            ?>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small"><i class="fa-regular fa-user fa-flip text-warning"></i> Logged in as : <?php echo $dt_user[2]?></div>
                        <?php echo $dt_user[0].' ('.$dt_user [1]. ')'; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <?php
                        // echo $_SERVER['REQUEST_URI'];
                        $e=explode("=",$_SERVER['REQUEST_URI']);
                        // echo "<BR> [0]: $e[0] --> [1]: $e[1]";
                        if(!empty($e[1])) {
                            if($e[1]=="user" || (isset($e[1]) && strpos($e[1], 'user_edit') === 0)) {
                                $h1="Manajemen User";
                                $li="Menu Untuk CRUD User";
                            }
                            elseif($e[1]=="pemakaian_warga") {
                                $h1="Melihat Pemakaian Air";
                                $li="Melihat Data Pemakaian Air";
                            }
                            elseif($e[1]=="pembayaran_warga") {
                                $h1="Lihat Pembayaran Warga";
                                $li="Lihat Data Pembayaran Air Warga";
                            }
                            elseif($e[1]=="ubah_datameter_warga") {
                                $h1="Ubah Datameter Warga";
                                $li="Ubah Air Warga";
                            }
                            elseif($e[1]=="manajemen_tarif") {
                                $h1="Manajemen Tarif";
                                $li="Manajemen Tarif Air";  
                            }
                            elseif($e[1]=="tagihan_warga") {
                                $h1="Lihat Tagihan";
                                $li="Lihat Data Tagihan Air";
                            }
                             elseif($e[1]=="tagihan_warga_bendahara") {
                                $h1="Lihat Tagihan Warga";
                                $li="Lihat Data Tagihan Air Warga";
                            }
                        }
                        else {
                            $h1="Dashboard";
                            $li="Dashboard";
                        }
                        ?>
                        <h1 class="mt-4"><?php echo $h1 ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?php echo $li ?></li>
                        </ol>
                        <?php
                        // echo "sesi user: " . $_SESSION['user'] . " sesi pass: " . $_SESSION['pass']; 
                            ?>
                        <div class="row" id="sumary">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="chart">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <?php 
                        // Deteksi mode edit: dari URL ?p=user_edit&user=xxx
                        $edit_mode = false;
                        $edit_user = '';
                        if (isset($_GET['p']) && $_GET['p'] == 'user_edit' && isset($_GET['user'])) {
                            $edit_mode = true;
                            $edit_user = $_GET['user'];
                        }

                        if (isset($_POST['tombol'])) {
                            $t = $_POST['tombol'];
                            if ($t == "user_add") {
                                
                                $user=$_POST['username'];
                                $pass= password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $pass2= $_POST['password'];
                                $nama=$_POST['nama'];
                                $alamat=$_POST['alamat'];
                                $kota=$_POST['kota'];
                                $tlp=$_POST['tlp'];
                                $leve=$_POST['level'];
                                $tipe=$_POST['tipe'];
                                $stats=$_POST['status'];
                                // Query untuk menambahkan data ke tabel user dengan urutan nama field secara eksplisit
                                $query_insert = "INSERT INTO user (username, password, nama, alamat, kota, tlp, level, tipe, status) 
                                                 VALUES ('$user', '$pass', '$nama', '$alamat', '$kota', '$tlp', '$leve', '$tipe', '$stats')";
                                
                                $eksekusi = mysqli_query($koneksi, $query_insert);

                                if($eksekusi) {
                                    echo "<script>alert('User berhasil ditambahkan!'); window.location.replace('index.php?p=user');</script>";
                                } else {
                                    echo "<script>alert('Gagal menambahkan user: " . mysqli_error($koneksi) . "');</script>";
                                }
                            } elseif ($t == "user_edit") {
                                $user   = mysqli_real_escape_string($koneksi, $_POST['username']);
                                $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
                                $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
                                $kota   = mysqli_real_escape_string($koneksi, $_POST['kota']);
                                $tlp    = mysqli_real_escape_string($koneksi, $_POST['tlp']);
                                $leve   = mysqli_real_escape_string($koneksi, $_POST['level']);
                                $tipe   = mysqli_real_escape_string($koneksi, $_POST['tipe']);
                                $stats  = mysqli_real_escape_string($koneksi, $_POST['status']);

                                // Jika password diubah (bukan sentinel), update password juga
                                if (!empty($_POST['password']) && $_POST['password'] !== '__NOCHANGE__') {
                                    $pass_baru = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $query_update = "UPDATE user SET nama='$nama', alamat='$alamat', kota='$kota', tlp='$tlp', level='$leve', tipe='$tipe', status='$stats', password='$pass_baru' WHERE username='$user'";
                                } else {
                                    $query_update = "UPDATE user SET nama='$nama', alamat='$alamat', kota='$kota', tlp='$tlp', level='$leve', tipe='$tipe', status='$stats' WHERE username='$user'";
                                }

                                $eksekusi = mysqli_query($koneksi, $query_update);
                                if ($eksekusi) {
                                    echo "<script>alert('User berhasil diubah!'); window.location.replace('index.php?p=user');</script>";
                                } else {
                                    echo "<script>alert('Gagal mengubah user: " . mysqli_error($koneksi) . "');</script>";
                                }
                            }
                        } elseif(isset($_GET['p'])) {
                            $p = $_GET['p'];
                            if($p == "user_edit" && isset($_GET['user'])) {

                                $user = mysqli_real_escape_string($koneksi, $_GET['user']);
                                $q = mysqli_query($koneksi, "SELECT password,nama,alamat,kota,tlp,level,tipe,status FROM user WHERE username='$user'");
                                
                                $d = mysqli_fetch_row($q);
                                $pass2  = ''; // Jangan tampilkan hash password
                                $nama   = $d[1];
                                $alamat = $d[2];
                                $kota   = $d[3];
                                $tlp    = $d[4];
                                $leve   = $d[5];
                                $tipe   = $d[6];
                                $stats  = $d[7];
                            } elseif($p == "user_delete" && isset($_GET['user'])) {
                                $user_del = mysqli_real_escape_string($koneksi, $_GET['user']);
                                $del = mysqli_query($koneksi, "DELETE FROM user WHERE username='$user_del'");
                                if ($del) {
                                    echo "<script>alert('User \"$user_del\" berhasil dihapus!'); window.location.replace('index.php?p=user');</script>";
                                } else {
                                    echo "<script>alert('Gagal menghapus user: " . mysqli_error($koneksi) . "');</script>";
                                }
                            }
                        }
                        ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-user-plus me-2 text-success fa-fade"></i>
                                 User
                            </div>
                            <div class="card-body">
                                <form method="post" class="need-validation" id="user_form" action="index.php<?php echo $edit_mode ? '?p=user_edit&user='.urlencode($edit_user) : ''; ?>">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($edit_user); ?>">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="username_lbl" class="form-label">Username :</label>
                                    <?php if ($edit_mode): ?>
                                        <input type="text" class="form-control" id="username" placeholder="Enter Username" value="<?php echo htmlspecialchars($edit_user); ?>" disabled>
                                    <?php else: ?>
                                        <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="<?php echo htmlspecialchars($user ?? ''); ?>" required>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="pwd" class="form-label">Password :</label>
                                    <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="password" value="<?php echo $edit_mode ? '__NOCHANGE__' : ''; ?>" <?php echo $edit_mode ? '' : 'required'; ?>>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama :</label>
                                    <input type="text" class="form-control" id="nama" placeholder="Enter Nama" name="nama" value="<?php echo htmlspecialchars($nama ?? ''); ?>" required>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="alamat">Alamat :</label>
                                    <textarea class="form-control" rows="5" id="alamat" name="alamat"><?php echo htmlspecialchars($alamat ?? ''); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota :</label>
                                    <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="<?php echo htmlspecialchars($kota ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="tlp" class="form-label">Telepon :</label>
                                    <input type="text" class="form-control" id="tlp" placeholder="Enter Telepon" name="tlp" value="<?php echo htmlspecialchars($tlp ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level :</label>
                                    <select class="form-select" name="level">
                                        <option value="">Level</option>
                                        <?php 
                                        $lv_options = array("admin","bendahara","petugas","warga");
                                        foreach($lv_options as $lv2){
                                            $sel = (isset($leve) && $leve == $lv2) ? 'selected' : '';
                                            echo "<option value='$lv2' $sel>".ucwords($lv2)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe :</label>
                                    <select class="form-select" name="tipe">
                                        <option value="">Tipe</option>
                                        <?php 
                                        $tp_options = array("RT","Kos");
                                        foreach($tp_options as $tp2){
                                            $sel = (isset($tipe) && $tipe == $tp2) ? 'selected' : '';
                                            echo "<option value='$tp2' $sel>".ucwords($tp2)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status :</label>
                                    <select class="form-select" name="status">
                                        <option value="">Status</option>
                                        <?php 
                                        $st_options = array("AKTIF","TIDAK AKTIF");
                                        foreach($st_options as $st2){
                                            $sel = (isset($stats) && $stats == $st2) ? 'selected' : '';
                                            echo "<option value='$st2' $sel>$st2</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" name="tombol" value="<?php echo $edit_mode ? 'user_edit' : 'user_add'; ?>">
                                    <?php echo $edit_mode ? '<i class="fas fa-save me-1"></i> Update' : '<i class="fas fa-plus me-1"></i> Simpan'; ?>
                                </button>
                                <?php if ($edit_mode): ?>
                                <a href="index.php?p=user" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                                <?php endif; ?>
                                </form>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-users me-2 text-success fa-fade"></i>
                                Data User
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
                                            <th></th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        <?php 
                                        $q=mysqli_query($koneksi,"SELECT username,nama,alamat,kota,tlp,level,tipe, status FROm user ORDER BY level ASC ");
                                        while($d=mysqli_fetch_row($q)) {
                                            $user=$d[0];
                                            $nama=$d[1];
                                            $alamat=$d[2];
                                            $kota=$d[3];
                                            $tlp=$d[4];
                                            $level=$d[5];
                                            $tipe=$d[6];
                                            $status=$d[7];

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
                                                    <a href=index.php?p=user_edit&user=$user><button type=button class='btn btn-outline-success btn-sm'>Ubah</button></a>
                                                    <button type='button' class='btn btn-outline-danger btn-sm btn-hapus' data-bs-toggle='modal' data-bs-target='#modalHapus' data-user='$user'>Hapus</button>
                                                    </td>
                                                </tr>";
                                        }
                                        ?>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus Data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Yakin hapus data username: <strong id="namaUserHapus"></strong>?
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="btnKonfirmasiHapus" class="btn btn-danger">Ya</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
        <script src="../assets/demo/chart-area-demo.js"></script>
        <script src="../assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="../js/air.js"></script>
    </head>
    </body>
</html>
