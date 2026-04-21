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
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="../js/air.js"></script>

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
                                Lihat Pemakaian Warga
                            </a>
                                <a class="nav-link" href="index.php?p=pembayaran_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Pembayaran Warga
                            </a>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                            <?php 
                            } 
                            elseif($level=="bendahara") {
                            ?>
                                <a class="nav-link" href="index.php?p=pembayaran_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Pembayaran Warga
                            </a>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                                <a class="nav-link" href="index.php?p=manajemen_tarif_air">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Manajemen Tarif Air
                            </a>
                                <a class="nav-link" href="index.php?p=tagihan_warga_bendahara">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Tagihan Warga
                            </a>
                            <?php
                            }
                             elseif($level=="petugas") {
                            ?>
                                <a class="nav-link" href="index.php?p=ubah_datameter_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Ubah Datameter Warga
                            </a>
                                <a class="nav-link" href="index.php?p=lihat_pemakaian_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Pemakaian Air
                            </a>
                      
                            <?php
                             }
                             elseif($level=="warga") {
                            ?>
                                <a class="nav-link" href="index.php?p=pemakaian_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Pemakaian Warga
                            </a>
                            <a class="nav-link" href="index.php?p=lihat_tagihan_warga">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt fa-spin text-success"></i></div>
                                Lihat Tagihan Warga
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
                            if($e[1]=="user" || $e[1]=="user_edit&user") {
                                $h1="Manajemen User";
                                $li="Menu Untuk CRUD User";
                            }
                            elseif($e[1]=="pemakaian_warga") {
                                $h1="Lihat Pemakaian Warga";
                                $li="Lihat Data Pemakaian Air Warga";
                            }
                            elseif($e[1]=="pembayaran_warga") {
                                $h1="Lihat Pembayaran Warga";
                                $li="Lihat Data Pembayaran Air Warga";
                            }
                            elseif($e[1]=="ubah_datameter_warga") {
                                $h1="Ubah Datameter Warga";
                                $li="Ubah Data Meter Warga";
                            }
                            elseif($e[1]=="manajemen_tarif_air") {
                                $h1="Manajemen Tarif Air";
                                $li="Menu Untuk CRUD Tarif Air";
                            }
                            elseif($e[1]=="tagihan_warga_bendahara") {
                                $h1="Lihat Tagihan Warga";
                                $li="Lihat Data Tagihan Air Warga";
                            }
                            elseif($e[1]=="lihat_pemakaian_warga") {
                                $h1="Lihat Pemakaian Air Warga";
                                $li="Lihat Data Pemakaian Air Warga";
                            }
                            elseif($e[1]=="lihat_tagihan_warga") {
                                $h1="Lihat Tagihan Air Warga";
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
                                $level=$_POST['level'];
                                $tipe=$_POST['tipe'];
                                $status=$_POST['status'];
                                
                                //cek username sudah ada atau belum
                                $qc= mysqli_query($koneksi,"SELECT username FROM user WHERE username='$user'");
                                $qj=mysqli_num_rows($qc);
                                // echo "jumlah data: $qj";
                                //username tidak ada
                                if (empty($qj)) {
                                        mysqli_query($koneksi,"INSERT INTO user (username, password, level, tipe, status, nama, alamat, kota, tlp) VALUES ('$user','$pass','$level','$tipe','$status',\"$nama\",'$alamat','$kota','$tlp')");
                                        if (mysqli_affected_rows($koneksi) > 0) {
                                            echo "<div class='alert alert-success alert-dismissible fade show'>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Berhasil Disimpan
                                            </div>";
                                        }
                                        else {
                                            echo "<div class='alert alert-danger alert-dismissible fade show'>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Gagal Disimpan
                                            </div>";
                                        }
                                }else { //username sudah ada
                                    echo "<div class='alert alert-danger alert-dismissible fade show'>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Username $user</strong> Sudah Ada
                                    </div>";
                                }
                            } elseif ($t == "user_edit") {
                                $user=$_POST['username'];
                                $pass=$_POST['password'];
                                // $pass2= $_POST['password'];
                                $nama=$_POST['nama'];
                                $alamat=$_POST['alamat'];
                                $kota=$_POST['kota'];
                                $tlp=$_POST['tlp'];
                                $level=$_POST['level'];
                                $tipe=$_POST['tipe'];
                                $status=$_POST['status'];

                                //cek password yg ada di tabel user
                                $qcp= mysqli_query($koneksi,"SELECT password FROM user WHERE username='$user'");
                                $dcp=mysqli_fetch_row($qcp);
                                $pass_db=$dcp[0];

                                if ($pass == $pass_db) {
                                    //tidak ada perubahan password
                                    mysqli_query($koneksi,"UPDATE user SET nama= \"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                                }
                                else {
                                    //ada perubahan password
                                    $pass2= password_hash($pass, PASSWORD_DEFAULT);
                                    mysqli_query($koneksi,"UPDATE user SET password='$pass2', nama= \"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                                }
                                        if (mysqli_affected_rows($koneksi) > 0) {
                                            echo "<div class='alert alert-success alert-dismissible fade show'>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Berhasil Diupdate
                                            </div>";
                                        }
                                        else {
                                            echo "<div class='alert alert-primary alert-dismissible fade show'>
                                                    <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                    <strong>Data</strong> Tidak Ada Perubahan
                                            </div>";
                                        }
                            } elseif ($t == "user_hapus") {
                                $user=$_POST['user'];
                                mysqli_query($koneksi,"DELETE FROM user WHERE username='$user'");
                                if (mysqli_affected_rows($koneksi) > 0) {
                                    echo "<div class='alert alert-success alert-dismissible fade show'>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Berhasil Dihapus
                                    </div>";
                                }
                                else {
                                    echo "<div class='alert alert-danger alert-dismissible fade show'>
                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> Gagal Dihapus
                                    </div>";
                                }
                            }
                        } elseif (isset($_GET['p'])) {
                            $p=$_GET['p'];
                            if ($p=="user_edit") {
                                $user=$_GET['user'];
                                $q=mysqli_query($koneksi,"SELECT password,nama,alamat,kota,tlp,level,tipe,status FROM user WHERE username='$user'");
                                $d=mysqli_fetch_row($q);
                                $pass=$d[0];
                                $pass2=password_hash($pass, PASSWORD_DEFAULT);
                                $nama=$d[1];
                                $alamat=$d[2];
                                $kota=$d[3];
                                $tlp=$d[4];
                                $level=$d[5];
                                $tipe=$d[6];
                                $status=$d[7];                           
                                }
                            }   
                        ?>
                        <div class="card mb-4" id="form_user">
                            <div class="card-header">
                                <i class="fa-solid fa-user-plus me-2 text-success fa-fade"></i>
                                 User
                            </div>
                            <div class="card-body">
                                <form method="post" class="need-validation" id="user_form">
                                <div class="mb-3">
                                    <label for="usernmae" class="form-label">Username :</label>
                                    <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="<?php echo $user ?? ''; ?>" required>
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
                                        $lv=array("admin","bendahara","petugas","warga");
                                        foreach($lv as $lv2){
                                            if($level==$lv2) $sel="SELECTED";
                                            else $sel= "";
                                            echo "<option value=$lv2 $sel>".ucwords($lv2)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe :</label>
                                    <select class="form-select" name="tipe">
                                        <option value="">Tipe</option>
                                        <?php 
                                        $tp=array("RT","Kos");
                                        foreach($tp as $tp2){
                                            if($tipe==$tp2) $sel="SELECTED";
                                            else $sel= "";
                                            echo "<option value=$tp2 $sel>".ucwords($tp2)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status :</label>
                                    <select class="form-select" name="status">
                                        <option value="">Status</option>
                                        <?php 
                                        $st=array("AKTIF","TIDAK AKTIF");
                                        foreach($st as $st2){
                                            if($status==$st2) $sel="SELECTED";
                                            else $sel= "";
                                            echo "<option value='$st2' $sel>$st2</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" name="tombol" value="user_add">Simpan</button>
                                </form>
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
                                        <button type="submit" name="tombol" value="user_hapus" class="btn btn-danger" data-bs-dismiss="modal">Ya</button>
                                    </form>
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                                </div>

                            </div>
                        </div>
                        </div>   

                        <div class="card mb-4" id="data_user">
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
                                                    <button type=button class='btn btn-outline-danger btn-sm' data-bs-toggle=modal data-bs-target=#myModal data_user=$user>Hapus</button> 
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
        </head>
    </body>
</html>
