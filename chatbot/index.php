<?php
session_start();

// Redirect ke login jika belum login
if (empty($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

include '../assets/func.php';
$air     = new klas_air;
$dt_user = $air->dt_user($_SESSION['user']);
$nama    = $dt_user[0];
$level   = $dt_user[2];

// Quick reply berdasarkan role
$quick_warga = [
    "pemakaian saya bulan ini",
    "tagihan saya bulan ini",
    "status pembayaran saya",
    "riwayat pemakaian saya",
    "tarif air"
];
$quick_petugas = [
    "total pemakaian bulan ini",
    "jumlah pelanggan",
    "rekap bulan ini",
    "data warga "
];
$quick_bendahara = [
    "total tagihan bulan ini",
    "siapa yang belum lunas",
    "rekap bulan ini",
    "tarif air"
];
$quick_admin = [
    "rekap bulan ini",
    "siapa yang belum lunas",
    "total pemakaian bulan ini",
    "total tagihan bulan ini",
    "jumlah pelanggan"
];

if ($level === 'warga')          $quick_list = $quick_warga;
elseif ($level === 'petugas')    $quick_list = $quick_petugas;
elseif ($level === 'bendahara')  $quick_list = $quick_bendahara;
else                             $quick_list = $quick_admin;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot - Sistem Manajemen Air</title>
    <meta name="description" content="Asisten virtual Sistem Manajemen Air untuk membantu pertanyaan seputar pemakaian dan tagihan air.">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/Icon.png">
</head>
<body>

<div class="chat-wrapper">

    <!-- HEADER -->
    <div class="chat-header">
        <div class="header-avatar">🤖</div>
        <div class="header-info">
            <div class="header-title">Asisten Air</div>
            <div class="header-sub">Sistem Manajemen Pemakaian Air</div>
        </div>
        <div class="header-status">
            <div class="status-dot"></div>
            Online
        </div>
    </div>

    <!-- USER BADGE -->
    <div class="user-badge">
        👤 Login sebagai: <span><?= htmlspecialchars($nama) ?></span>
        <span class="role-chip"><?= htmlspecialchars($level) ?></span>
        &nbsp;|&nbsp;
        <a href="../login/index.php" style="color:rgba(39, 134, 243, 0.7);text-decoration:none;font-size:11px;">← Kembali ke Dashboard</a>
    </div>

    <!-- CHAT BOX -->
    <div id="chat-box"></div>

    <!-- QUICK REPLIES -->
    <div class="quick-replies" id="quick-area">
        <?php foreach ($quick_list as $q): ?>
            <button class="quick-btn" onclick="quickReply('<?= htmlspecialchars($q) ?>')"><?= htmlspecialchars($q) ?></button>
        <?php endforeach; ?>
        <button class="quick-btn" onclick="quickReply('bantuan')">❓ bantuan</button>
    </div>

    <!-- INPUT AREA -->
    <div class="input-area">
        <input type="text" id="pesan" placeholder="Ketik pertanyaan Anda..." autocomplete="off">
        <button class="btn-send" id="btn-send" onclick="kirimPesan()" title="Kirim">
            ➤
        </button>
    </div>

</div>

<script src="script.js"></script>
<script>
    // Tampilkan greeting otomatis saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const nama  = <?= json_encode($nama) ?>;
        const level = <?= json_encode($level) ?>;

        const greet = `HALO, <b>${nama}</b>! 👋<br>`
            + `Saya Asisten Air, siap membantu Anda seputar informasi pemakaian dan tagihan air.<br><br>`
            + `Anda login sebagai <b>${level}</b>. `
            + (level === 'warga'
                ? `Saya hanya menampilkan data milik Anda.`
                : `Anda dapat menanyakan data seluruh warga.`)
            + `<br><br>Ketik <b>bantuan</b> untuk melihat daftar pertanyaan yang bisa saya jawab. 😊`;

        appendMsg('bot', greet, getNow());
    });
</script>

</body>
</html>
