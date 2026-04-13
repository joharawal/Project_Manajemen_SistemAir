<?php
require 'assets/func.php';
$air = new klas_air;
$k = $air->koneksi();
$q = mysqli_query($k, 'SELECT username FROM user');
var_dump($q);
if(!$q) echo mysqli_error($k);
?>
