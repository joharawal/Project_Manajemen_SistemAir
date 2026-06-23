<?php
session_start();

if (empty($_SESSION['user'])) {
    echo json_encode(["jawaban" => "Session tidak ditemukan. Silakan login terlebih dahulu."]);
    exit;
}

include "../assets/func.php";
$air = new klas_air;
$koneksi = $air->koneksi();

// Ambil data user yang sedang login
$username = $_SESSION['user'];
$dt_user  = $air->dt_user($username); // [nama, tipe, level, kota]
$nama     = $dt_user[0];
$tipe     = $dt_user[1];
$level    = $dt_user[2];
$kota     = $dt_user[3];

// Apakah user adalah staff (bisa akses semua data)
$is_staff = in_array($level, ['admin', 'petugas', 'bendahara']);

include "intent.php";

$pesan           = strtolower(trim($_POST['pesan'] ?? ''));
$intent_ditemukan = "";

// Deteksi intent dari pesan
foreach ($intent as $nama_intent => $keywords) {
    foreach ($keywords as $keyword) {
        if (strpos($pesan, $keyword) !== false) {
            $intent_ditemukan = $nama_intent;
            break 2;
        }
    }
}

// =====================================================
// HANDLER SETIAP INTENT
// =====================================================

// --- SALAM ---
if ($intent_ditemukan == "salam") {
    $jawaban = "HALO, <b>$nama</b>! 👋<br>Ada yang bisa saya bantu hari ini?<br><br>"
             . "Ketik <b>bantuan</b> untuk melihat daftar pertanyaan yang bisa saya jawab.";

// --- BANTUAN ---
} elseif ($intent_ditemukan == "bantuan") {
    $daftar = "<b>🤖 Daftar pertanyaan yang bisa saya jawab:</b><br><br>";

    if ($level === 'warga') {
        $daftar .= "💧 <b>Pemakaian Air:</b><br>";
        $daftar .= "• pemakaian saya<br>";
        $daftar .= "• pemakaian saya bulan [nama bulan]<br><br>";
        $daftar .= "🧾 <b>Tagihan:</b><br>";
        $daftar .= "• tagihan saya<br>";
        $daftar .= "• tagihan saya bulan [nama bulan]<br><br>";
        $daftar .= "✅ <b>Status Pembayaran:</b><br>";
        $daftar .= "• status pembayaran saya<br>";
        $daftar .= "• status tagihan bulan [nama bulan]<br><br>";
        $daftar .= "📋 <b>Lainnya:</b><br>";
        $daftar .= "• riwayat pemakaian saya<br>";
        $daftar .= "• tarif air<br>";
        $daftar .= "• profil saya<br>";
    } elseif ($level === 'petugas') {
        $daftar .= "💧 <b>Pemakaian:</b><br>";
        $daftar .= "• total pemakaian per bulan<br>";
        $daftar .= "• pemakaian warga [nama]<br><br>";
        $daftar .= "👥 <b>Pelanggan:</b><br>";
        $daftar .= "• jumlah pelanggan<br>";
        $daftar .= "• rekap per bulan<br>";
        $daftar .= "• data warga [nama/username]<br>";
    } elseif ($level === 'bendahara') {
        $daftar .= "💰 <b>Tagihan & Pemasukan:</b><br>";
        $daftar .= "• total tagihan per bulan<br>";
        $daftar .= "• siapa yang belum lunas per bulan<br><br>";
        $daftar .= "📊 <b>Rekap:</b><br>";
        $daftar .= "• rekap per bulan<br>";
        $daftar .= "• tarif air<br>";
    } else {
        $daftar .= "💧 <b>Pemakaian:</b><br>";
        $daftar .= "• jumlah pelanggan<br>";
        $daftar .= "• total pemakaian per bulan<br>";
        $daftar .= "• pemakaian warga [nama]<br><br>";
        $daftar .= "💰 <b>Tagihan:</b><br>";
        $daftar .= "• total tagihan per bulan<br>";
        $daftar .= "• siapa yang belum lunas per bulan<br><br>";
        $daftar .= "📊 <b>Rekap:</b><br>";
        $daftar .= "• rekap per bulan<br>";
        $daftar .= "• data warga [nama/username]<br>";
        $daftar .= "• tarif air<br>";
    }
    $daftar .= "<br><i style='color:#78909c;font-size:12px;'>💡 Tambahkan nama bulan di pertanyaan untuk data bulan tertentu.<br>"
             . "Contoh: <b>tagihan saya bulan maret</b></i>";
    $jawaban = $daftar;

// --- INFO DIRI ---
} elseif ($intent_ditemukan == "info_saya") {
    $jawaban = "👤 <b>Profil Anda:</b><br>"
             . "• Username : <b>$username</b><br>"
             . "• Nama     : <b>$nama</b><br>"
             . "• Role     : <b>$level</b><br>"
             . "• Tipe     : <b>$tipe</b><br>"
             . "• Kota     : <b>$kota</b>";

// --- TARIF ---
} elseif ($intent_ditemukan == "tarif") {
    $q = mysqli_query($koneksi, "SELECT tipe, tarif FROM tarif WHERE status='AKTIF' ORDER BY tipe");
    if (mysqli_num_rows($q) > 0) {
        $jawaban = "💰 <b>Tarif Air Aktif:</b><br>";
        while ($d = mysqli_fetch_assoc($q)) {
            $jawaban .= "• Tipe <b>{$d['tipe']}</b> : Rp " . number_format($d['tarif'], 0, ',', '.') . " / m³<br>";
        }
        $tarif_user = $air->idtarif_to_tarif($air->user_to_idtarif($username));
        if (!$is_staff && $tarif_user > 0) {
            $jawaban .= "<br>Tarif Anda (tipe $tipe): <b>Rp " . number_format($tarif_user, 0, ',', '.') . " / m³</b>";
        }
    } else {
        $jawaban = "Belum ada data tarif aktif.";
    }

// --- PENGGUNAAN / PEMAKAIAN ---
} elseif ($intent_ditemukan == "penggunaan") {
    $bln      = deteksiBulan($pesan);
    $bln_nama = labelBulan($bln);
    if ($is_staff) {
        $target = cariWarga($koneksi, $pesan, $username);
        if ($target) {
            $sql = mysqli_query($koneksi, "SELECT username, pemakaian, tgl FROM pemakaian WHERE username='$target' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
            $d   = mysqli_fetch_assoc($sql);
            if (!$d) $sql = mysqli_query($koneksi, "SELECT username, pemakaian, tgl FROM pemakaian WHERE username='$target' ORDER BY no DESC LIMIT 1");
            $d = $d ?: mysqli_fetch_assoc($sql);
            if ($d) {
                $jawaban = "💧 Pemakaian air <b>{$d['username']}</b> ($bln_nama):<br>"
                         . "• Pemakaian : <b>{$d['pemakaian']} m³</b><br>"
                         . "• Tanggal   : <b>{$d['tgl']}</b>";
            } else {
                $jawaban = "Belum ada data pemakaian untuk warga tersebut.";
            }
        } else {
            $sql     = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian),0) as total FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d       = mysqli_fetch_assoc($sql);
            $jawaban = "💧 Total pemakaian air seluruh warga bulan <b>$bln_nama</b>:<br><b>{$d['total']} m³</b>";
        }
    } else {
        $sql = mysqli_query($koneksi, "SELECT pemakaian, tgl FROM pemakaian WHERE username='$username' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
        $d   = mysqli_fetch_assoc($sql);
        if ($d) {
            $jawaban = "💧 Pemakaian air Anda bulan <b>$bln_nama</b>:<br>"
                     . "• Pemakaian : <b>{$d['pemakaian']} m³</b><br>"
                     . "• Tanggal   : <b>{$d['tgl']}</b>";
        } else {
            $jawaban = "Belum ada data pemakaian untuk bulan <b>$bln_nama</b>.";
        }
    }

// --- TAGIHAN ---
} elseif ($intent_ditemukan == "tagihan") {
    $bln      = deteksiBulan($pesan);
    $bln_nama = labelBulan($bln);
    if ($is_staff) {
        $target = cariWarga($koneksi, $pesan, $username);
        if ($target) {
            $sql = mysqli_query($koneksi, "SELECT username, tagihan, status, tgl FROM pemakaian WHERE username='$target' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
            $d   = mysqli_fetch_assoc($sql);
            if (!$d) $sql = mysqli_query($koneksi, "SELECT username, tagihan, status, tgl FROM pemakaian WHERE username='$target' ORDER BY no DESC LIMIT 1");
            $d = $d ?: mysqli_fetch_assoc($sql);
            if ($d) {
                $jawaban = "🧾 Tagihan <b>{$d['username']}</b> ($bln_nama):<br>"
                         . "• Tagihan : <b>Rp " . number_format($d['tagihan'], 0, ',', '.') . "</b><br>"
                         . "• Status  : <b>{$d['status']}</b><br>"
                         . "• Tanggal : <b>{$d['tgl']}</b>";
            } else {
                $jawaban = "Belum ada data tagihan untuk warga tersebut.";
            }
        } else {
            $sql     = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan),0) as total FROM pemakaian WHERE tgl LIKE '$bln%'");
            $d       = mysqli_fetch_assoc($sql);
            $jawaban = "🧾 Total tagihan seluruh warga bulan <b>$bln_nama</b>:<br><b>Rp " . number_format($d['total'], 0, ',', '.') . "</b>";
        }
    } else {
        $sql = mysqli_query($koneksi, "SELECT tagihan, tgl, status FROM pemakaian WHERE username='$username' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
        $d   = mysqli_fetch_assoc($sql);
        if ($d) {
            $jawaban = "🧾 Tagihan air Anda bulan <b>$bln_nama</b>:<br>"
                     . "• Tagihan : <b>Rp " . number_format($d['tagihan'], 0, ',', '.') . "</b><br>"
                     . "• Status  : <b>{$d['status']}</b><br>"
                     . "• Tanggal : <b>{$d['tgl']}</b>";
        } else {
            $jawaban = "Belum ada data tagihan untuk bulan <b>$bln_nama</b>.";
        }
    }

// --- STATUS ---
} elseif ($intent_ditemukan == "status") {
    $bln      = deteksiBulan($pesan);
    $bln_nama = labelBulan($bln);
    if ($is_staff) {
        $target = cariWarga($koneksi, $pesan, $username);
        if ($target) {
            $sql = mysqli_query($koneksi, "SELECT username, status, tgl FROM pemakaian WHERE username='$target' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
            $d   = mysqli_fetch_assoc($sql);
            if (!$d) $sql = mysqli_query($koneksi, "SELECT username, status, tgl FROM pemakaian WHERE username='$target' ORDER BY no DESC LIMIT 1");
            $d = $d ?: mysqli_fetch_assoc($sql);
            if ($d) {
                $ikon    = strtolower($d['status']) == 'lunas' ? '✅' : '❌';
                $jawaban = "$ikon Status tagihan <b>{$d['username']}</b> ($bln_nama):<br>"
                         . "• Status  : <b>{$d['status']}</b><br>"
                         . "• Tanggal : <b>{$d['tgl']}</b>";
            } else {
                $jawaban = "Belum ada data untuk warga tersebut.";
            }
        } else {
            $q_lunas = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)='lunas'");
            $d_lunas = mysqli_fetch_assoc($q_lunas);
            $q_blm   = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)!='lunas'");
            $d_blm   = mysqli_fetch_assoc($q_blm);
            $jawaban = "📊 Status tagihan bulan <b>$bln_nama</b>:<br>"
                     . "✅ Sudah Lunas : <b>{$d_lunas['jml']} warga</b><br>"
                     . "❌ Belum Lunas : <b>{$d_blm['jml']} warga</b>";
        }
    } else {
        $sql = mysqli_query($koneksi, "SELECT status, tgl FROM pemakaian WHERE username='$username' AND tgl LIKE '$bln%' ORDER BY no DESC LIMIT 1");
        $d   = mysqli_fetch_assoc($sql);
        if ($d) {
            $ikon    = strtolower($d['status']) == 'lunas' ? '✅' : '❌';
            $jawaban = "$ikon Status pembayaran Anda bulan <b>$bln_nama</b>:<br>"
                     . "• Status  : <b>{$d['status']}</b><br>"
                     . "• Tanggal : <b>{$d['tgl']}</b>";
        } else {
            $jawaban = "Belum ada data status pembayaran untuk bulan <b>$bln_nama</b>.";
        }
    }

// --- RIWAYAT ---
} elseif ($intent_ditemukan == "riwayat") {
    if ($is_staff) {
        $target = cariWarga($koneksi, $pesan, $username);
        $filter = $target ? "WHERE username='$target'" : "";
        $label  = $target ? "warga <b>$target</b>" : "semua warga";
        $sql    = mysqli_query($koneksi, "SELECT username, pemakaian, tagihan, status, tgl FROM pemakaian $filter ORDER BY no DESC LIMIT 5");
    } else {
        $label  = "Anda";
        $sql    = mysqli_query($koneksi, "SELECT pemakaian, tagihan, status, tgl FROM pemakaian WHERE username='$username' ORDER BY no DESC LIMIT 5");
    }

    if (mysqli_num_rows($sql) > 0) {
        $jawaban = "📋 Riwayat pemakaian $label (5 terakhir):<br>";
        $jawaban .= "<table style='width:100%;border-collapse:collapse;margin-top:6px;font-size:13px;'>";
        $jawaban .= "<tr style='background:#e3f2fd;'>";
        if ($is_staff) $jawaban .= "<th style='padding:4px 6px;border:1px solid #ccc;'>User</th>";
        $jawaban .= "<th style='padding:4px 6px;border:1px solid #ccc;'>Tanggal</th>"
                  . "<th style='padding:4px 6px;border:1px solid #ccc;'>Pakai (m³)</th>"
                  . "<th style='padding:4px 6px;border:1px solid #ccc;'>Tagihan</th>"
                  . "<th style='padding:4px 6px;border:1px solid #ccc;'>Status</th></tr>";
        while ($d = mysqli_fetch_assoc($sql)) {
            $warna = strtolower($d['status']) == 'lunas' ? '#e8f5e9' : '#fff3e0';
            $jawaban .= "<tr style='background:$warna;'>";
            if ($is_staff) $jawaban .= "<td style='padding:4px 6px;border:1px solid #ccc;'>{$d['username']}</td>";
            $jawaban .= "<td style='padding:4px 6px;border:1px solid #ccc;'>{$d['tgl']}</td>"
                      . "<td style='padding:4px 6px;border:1px solid #ccc;text-align:center;'>{$d['pemakaian']}</td>"
                      . "<td style='padding:4px 6px;border:1px solid #ccc;'>Rp " . number_format($d['tagihan'], 0, ',', '.') . "</td>"
                      . "<td style='padding:4px 6px;border:1px solid #ccc;'>{$d['status']}</td></tr>";
        }
        $jawaban .= "</table>";
    } else {
        $jawaban = "Belum ada riwayat pemakaian.";
    }

// --- JUMLAH PELANGGAN (staff only) ---
} elseif ($intent_ditemukan == "jumlah_pelanggan") {
    if ($is_staff) {
        $sql = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM user WHERE level='warga'");
        $d   = mysqli_fetch_assoc($sql);
        $jawaban = "👥 Jumlah pelanggan terdaftar: <b>{$d['jml']} warga</b>";
    } else {
        $jawaban = "Maaf, informasi ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- TOTAL PEMAKAIAN (staff only) ---
} elseif ($intent_ditemukan == "total_pemakaian") {
    if ($is_staff) {
        $bln      = deteksiBulan($pesan);
        $bln_nama = labelBulan($bln);
        $sql      = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian),0) as total, COUNT(*) as jml_warga FROM pemakaian WHERE tgl LIKE '$bln%'");
        $d        = mysqli_fetch_assoc($sql);
        $jawaban  = "💧 Total pemakaian air bulan <b>$bln_nama</b>:<br>"
                  . "• Total     : <b>{$d['total']} m³</b><br>"
                  . "• Tercatat  : <b>{$d['jml_warga']} warga</b>";
    } else {
        $jawaban = "Maaf, informasi ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- TOTAL TAGIHAN (staff only) ---
} elseif ($intent_ditemukan == "total_tagihan") {
    if ($is_staff) {
        $bln      = deteksiBulan($pesan);
        $bln_nama = labelBulan($bln);
        $sql      = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan),0) as total FROM pemakaian WHERE tgl LIKE '$bln%'");
        $d        = mysqli_fetch_assoc($sql);
        $sql_l    = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan),0) as lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)='lunas'");
        $d_l      = mysqli_fetch_assoc($sql_l);
        $jawaban  = "💰 Total tagihan bulan <b>$bln_nama</b>:<br>"
                  . "• Semua Tagihan : <b>Rp " . number_format($d['total'], 0, ',', '.') . "</b><br>"
                  . "• Sudah Lunas   : <b>Rp " . number_format($d_l['lunas'], 0, ',', '.') . "</b><br>"
                  . "• Belum Lunas   : <b>Rp " . number_format($d['total'] - $d_l['lunas'], 0, ',', '.') . "</b>";
    } else {
        $jawaban = "Maaf, informasi ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- WARGA BELUM LUNAS (staff only) ---
} elseif ($intent_ditemukan == "warga_belum_lunas") {
    if ($is_staff) {
        $bln      = deteksiBulan($pesan);
        $bln_nama = labelBulan($bln);
        $sql      = mysqli_query($koneksi, "SELECT p.username, u.nama, p.tagihan, p.tgl FROM pemakaian p JOIN user u ON p.username=u.username WHERE p.tgl LIKE '$bln%' AND LOWER(p.status)!='lunas' ORDER BY p.tgl DESC");
        if (mysqli_num_rows($sql) > 0) {
            $jawaban  = "❌ <b>Warga belum lunas bulan $bln_nama:</b><br>";
            $jawaban .= "<table style='width:100%;border-collapse:collapse;margin-top:6px;font-size:13px;'>";
            $jawaban .= "<tr style='background:#ffebee;'><th style='padding:4px 6px;border:1px solid #ccc;'>Nama</th><th style='padding:4px 6px;border:1px solid #ccc;'>Tagihan</th><th style='padding:4px 6px;border:1px solid #ccc;'>Tanggal</th></tr>";
            while ($d = mysqli_fetch_assoc($sql)) {
                $jawaban .= "<tr><td style='padding:4px 6px;border:1px solid #ccc;'>{$d['nama']}</td>"
                          . "<td style='padding:4px 6px;border:1px solid #ccc;'>Rp " . number_format($d['tagihan'], 0, ',', '.') . "</td>"
                          . "<td style='padding:4px 6px;border:1px solid #ccc;'>{$d['tgl']}</td></tr>";
            }
            $jawaban .= "</table>";
        } else {
            $jawaban = "✅ Semua warga sudah lunas bulan <b>$bln_nama</b>!";
        }
    } else {
        $jawaban = "Maaf, informasi ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- REKAP BULAN (staff only) ---
} elseif ($intent_ditemukan == "rekap_bulan") {
    if ($is_staff) {
        $bln      = deteksiBulan($pesan);
        $bln_nama = labelBulan($bln);
        $q1       = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM user WHERE level='warga'");
        $q2       = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian),0) as pakai, COALESCE(SUM(tagihan),0) as tagih, COUNT(*) as tercatat FROM pemakaian WHERE tgl LIKE '$bln%'");
        $q3       = mysqli_query($koneksi, "SELECT COUNT(*) as lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND LOWER(status)='lunas'");
        $d1       = mysqli_fetch_assoc($q1);
        $d2       = mysqli_fetch_assoc($q2);
        $d3       = mysqli_fetch_assoc($q3);
        $blm_lunas   = $d2['tercatat'] - $d3['lunas'];
        $blm_dicatat = $d1['jml'] - $d2['tercatat'];
        $jawaban     = "📊 <b>Rekap Bulan $bln_nama:</b><br>"
                     . "👥 Total Pelanggan   : <b>{$d1['jml']} warga</b><br>"
                     . "📋 Sudah Dicatat     : <b>{$d2['tercatat']} warga</b><br>"
                     . "⏳ Belum Dicatat     : <b>$blm_dicatat warga</b><br>"
                     . "💧 Total Pemakaian   : <b>{$d2['pakai']} m³</b><br>"
                     . "💰 Total Tagihan     : <b>Rp " . number_format($d2['tagih'], 0, ',', '.') . "</b><br>"
                     . "✅ Sudah Lunas       : <b>{$d3['lunas']} warga</b><br>"
                     . "❌ Belum Lunas       : <b>$blm_lunas warga</b>";
    } else {
        $jawaban = "Maaf, informasi ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- CARI WARGA (staff only) ---
} elseif ($intent_ditemukan == "cari_warga") {
    if ($is_staff) {
        // Ekstrak kata kunci setelah kata 'warga' atau 'user'
        $kata = preg_replace('/(cari|data|info|warga|user|cek)\s*/i', '', $pesan);
        $kata = trim($kata);
        if (!empty($kata)) {
            $kata_esc = mysqli_real_escape_string($koneksi, $kata);
            $sql = mysqli_query($koneksi, "SELECT u.username, u.nama, u.level, u.tipe, p.pemakaian, p.tagihan, p.status, p.tgl FROM user u LEFT JOIN pemakaian p ON u.username=p.username WHERE (u.username LIKE '%$kata_esc%' OR u.nama LIKE '%$kata_esc%') AND u.level='warga' ORDER BY p.no DESC LIMIT 1");
            $d   = mysqli_fetch_assoc($sql);
            if ($d) {
                $jawaban = "🔍 Data warga <b>{$d['nama']}</b> ({$d['username']}):<br>"
                         . "• Tipe      : <b>{$d['tipe']}</b><br>"
                         . "• Pemakaian : <b>" . ($d['pemakaian'] ?? '-') . " m³</b><br>"
                         . "• Tagihan   : <b>Rp " . number_format($d['tagihan'] ?? 0, 0, ',', '.') . "</b><br>"
                         . "• Status    : <b>" . ($d['status'] ?? '-') . "</b><br>"
                         . "• Tanggal   : <b>" . ($d['tgl'] ?? '-') . "</b>";
            } else {
                $jawaban = "Warga dengan nama atau username '<b>$kata</b>' tidak ditemukan.";
            }
        } else {
            $jawaban = "Sebutkan nama atau username warga yang ingin dicari. Contoh: <i>data warga budi</i>";
        }
    } else {
        $jawaban = "Maaf, fitur ini hanya bisa diakses oleh petugas, bendahara, atau admin.";
    }

// --- TIDAK DIKENALI ---
} else {
    // Coba cari nama warga dari pesan jika staff
    if ($is_staff) {
        $target = cariWarga($koneksi, $pesan, $username);
        if ($target) {
            $sql = mysqli_query($koneksi, "SELECT p.pemakaian, p.tagihan, p.status, p.tgl, u.nama FROM pemakaian p JOIN user u ON p.username=u.username WHERE p.username='$target' ORDER BY p.no DESC LIMIT 1");
            $d   = mysqli_fetch_assoc($sql);
            if ($d) {
                $jawaban = "🔍 Data terakhir <b>{$d['nama']}</b>:<br>"
                         . "• Pemakaian : <b>{$d['pemakaian']} m³</b><br>"
                         . "• Tagihan   : <b>Rp " . number_format($d['tagihan'], 0, ',', '.') . "</b><br>"
                         . "• Status    : <b>{$d['status']}</b><br>"
                         . "• Tanggal   : <b>{$d['tgl']}</b>";
            } else {
                $jawaban = "Tidak ada data pemakaian untuk warga tersebut.";
            }
        } else {
            $jawaban = "Maaf, saya belum memahami pertanyaan tersebut. 🤔<br>Ketik <b>bantuan</b> untuk melihat daftar pertanyaan yang bisa saya jawab.";
        }
    } else {
        $jawaban = "Maaf, saya belum memahami pertanyaan tersebut. 🤔<br>Ketik <b>bantuan</b> untuk melihat daftar pertanyaan yang bisa saya jawab.";
    }
}

echo json_encode(["jawaban" => $jawaban]);

// =====================================================
// FUNGSI HELPER: Deteksi bulan dari teks pesan
// Contoh: "bulan januari" => "2026-01", "bulan ini" => bulan sekarang
// =====================================================
function deteksiBulan($pesan) {
    $nama_bln = [
        'januari'=>'01','februari'=>'02','maret'=>'03','april'=>'04',
        'mei'=>'05','juni'=>'06','juli'=>'07','agustus'=>'08',
        'september'=>'09','oktober'=>'10','november'=>'11','desember'=>'12'
    ];
    foreach ($nama_bln as $nama => $no) {
        if (strpos($pesan, $nama) !== false) {
            return date('Y') . '-' . $no;
        }
    }
    // Default: bulan saat ini
    return date('Y-m');
}

function labelBulan($bln_ym) {
    $bulan = [
        '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
        '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
        '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
    ];
    $parts = explode('-', $bln_ym);
    $no    = $parts[1] ?? date('m');
    $tahun = $parts[0] ?? date('Y');
    return ($bulan[$no] ?? $no) . ' ' . $tahun;
}

// =====================================================
// FUNGSI HELPER: Cari username warga dari teks pesan
// =====================================================
function cariWarga($koneksi, $pesan, $username_login) {
    $stop = ['berapa', 'pemakaian', 'tagihan', 'status', 'data', 'info', 'cek', 'saya', 'aku', 'riwayat', 'warga', 'cari', 'total', 'semua', 'yang', 'dari', 'untuk', 'air', 'bulan', 'ini', 'terakhir', 'lihat'];
    $kata_arr = explode(' ', $pesan);
    foreach ($kata_arr as $kata) {
        $kata = trim($kata);
        if (strlen($kata) < 2 || in_array($kata, $stop)) continue;
        $kata_esc = mysqli_real_escape_string($koneksi, $kata);
        $q = mysqli_query($koneksi, "SELECT username FROM user WHERE (username LIKE '%$kata_esc%' OR nama LIKE '%$kata_esc%') AND level='warga' LIMIT 1");
        if ($q && mysqli_num_rows($q) > 0) {
            $d = mysqli_fetch_assoc($q);
            return $d['username'];
        }
    }
    return null;
}