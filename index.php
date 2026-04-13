<?php 
// 1. Letakkan session_start di paling atas untuk menghindari error header
session_start();

// Koneksi ke database MariaDb
include './assets/func.php';
$air = new klas_air;
$koneksi = $air->koneksi();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login - SB Admin</title>
        
        <!-- SB Admin CSS -->
        <link href="css/styles.css" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        
        <!-- Custom CSS Purple Pill -->
        <style>
            .btn-purple-pill {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                background-color: #0d6efd; /* Ungu tua pekat */
                color: #ffffff !important;
                padding: 12px 30px;
                border-radius: 50px; /* Bentuk Pill/Kapsul */
                text-decoration: none !important;
                font-weight: 700;
                font-size: 14px;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                border: none;
                width: auto;
                margin-top: 10px;
            }

            .btn-purple-pill:hover {
                background-color: #0d6efd;
                transform: translateY(-3px);
                box-shadow: 0 8px 15px rgba(0, 76, 175, 0.3);
            }

            .btn-purple-pill i {
                font-size: 18px;
            }

            /* Perbaikan tombol login bawaan agar serasi */
            .btn-primary-custom {
                background-color: #0d6efd;
                border-radius: 8px;
                padding: 10px 25px;
                font-weight: 600;
            }
        </style>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">Login</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        if(isset($_POST['tombol'])) {
                                            $username = mysqli_real_escape_string($koneksi, $_POST['user']);
                                            $password = $_POST['password'];

                                            $qc = mysqli_query($koneksi, "SELECT username,password FROM user WHERE username = '$username'");
                                            $dc = mysqli_fetch_row($qc);

                                            if(!empty($dc[0])) {
                                                $pass_cek = $dc[1];
                                                if(password_verify($password, $pass_cek)) {
                                                    $_SESSION['user'] = $username;
                                                    echo "<script>window.location.replace('./login/index.php')</script>";
                                                } else {
                                                    echo "<div class='alert alert-danger alert-dismissible fade show'>
                                                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                                                            <strong>Login Gagal!</strong> Password salah.
                                                          </div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger alert-dismissible fade show'>
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                                                        <strong>Username!</strong> tidak valid.
                                                      </div>";
                                            }
                                        }
                                        ?>
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputUser" type="text" placeholder="Username" name="user" required/>
                                                <label for="inputUser">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" required/>
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small text-decoration-none" href="password.html">Forgot Password?</a>
                                                <input type="submit" name="tombol" value="Login" class="btn btn-primary btn-primary-custom">
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- FOOTER CARD FINAL -->
                                    <div class="card-footer text-center py-4 border-0 bg-transparent">
                                        <div class="small mb-3">
                                            <a href="register.html" class="text-muted text-decoration-none">Need an account? Sign up!</a>
                                        </div>
                                        
                                        <!-- Tombol Meet the Developers sesuai Gambar -->
                                        <a href="profile.html" class="btn-purple-pill">
                                            <i class="fas fa-user-circle"></i> 
                                            <span>Meet the Developers</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#" class="text-decoration-none">Privacy Policy</a>
                                &middot;
                                <a href="#" class="text-decoration-none">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>