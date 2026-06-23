<?php

$intent = [

    // =====================================================
    // INTENT UMUM (semua role)
    // =====================================================

    "salam" => [
        "halo", "hai", "hi", "hey", "selamat", "permisi",
        "assalamualaikum", "pagi", "siang", "sore", "malam",
        "hei", "alo", "hello"
    ],

    "bantuan" => [
        "bantuan", "help", "bisa apa", "apa saja", "apa yang bisa",
        "fitur", "menu", "panduan", "cara", "petunjuk", "tolong",
        "gimana", "bagaimana", "apa itu", "tentang chatbot"
    ],

    "info_saya" => [
        "siapa saya", "siapa aku", "profil saya", "profil aku",
        "data saya", "info saya", "akun saya", "identitas saya",
        "nama saya", "siapa namaku"
    ],

    "tarif" => [
        "tarif", "harga air", "biaya air", "tarif air", "berapa harganya",
        "harga per meter", "tarif per m3", "berapa tarif", "biaya per kubik"
    ],

    // =====================================================
    // INTENT WARGA (data diri sendiri)
    // =====================================================

    "penggunaan" => [
        "pemakaian", "penggunaan", "air saya", "berapa penggunaan",
        "berapa pemakaian", "pakai berapa", "kubik saya", "m3 saya",
        "konsumsi air", "pemakaian saya", "penggunaan saya"
    ],

    "tagihan" => [
        "tagihan", "bayar", "harus bayar", "berapa tagihan",
        "tagihan saya", "biaya saya", "berapa biaya", "nominal tagihan",
        "berapa yang harus dibayar", "besar tagihan", "total tagihan saya"
    ],

    "status" => [
        "status", "lunas", "belum bayar", "sudah bayar",
        "status pembayaran", "sudah lunas", "belum lunas",
        "status tagihan", "cek status", "pembayaran saya"
    ],

    "riwayat" => [
        "riwayat", "histori", "catatan", "semua data saya", "data terakhir",
        "history", "rekaman", "record", "lihat data", "data pemakaian",
        "catatan saya", "riwayat pemakaian"
    ],

    // =====================================================
    // INTENT KHUSUS PETUGAS / BENDAHARA / ADMIN
    // =====================================================

    "jumlah_pelanggan" => [
        "jumlah pelanggan", "berapa pelanggan", "jumlah warga",
        "berapa warga", "total pelanggan", "total warga",
        "banyak pelanggan", "banyak warga"
    ],

    "total_pemakaian" => [
        "total pemakaian", "semua pemakaian", "pemakaian semua warga",
        "pemakaian bulan ini", "total konsumsi", "keseluruhan pemakaian",
        "pemakaian keseluruhan", "jumlah pemakaian"
    ],

    "total_tagihan" => [
        "total tagihan", "total pendapatan", "semua tagihan",
        "tagihan semua warga", "tagihan bulan ini", "pemasukan",
        "total pemasukan", "total bayaran"
    ],

    "warga_belum_lunas" => [
        "belum lunas", "tunggakan", "siapa belum bayar", "yang belum bayar",
        "warga belum lunas", "daftar belum lunas", "belum membayar",
        "siapa saja yang belum", "warga nunggak"
    ],

    "rekap_bulan" => [
        "rekap", "laporan", "laporan bulan", "rekap bulan",
        "rangkuman", "summary", "ikhtisar", "ringkasan",
        "laporan bulanan", "data bulan ini"
    ],

    "cari_warga" => [
        "cari warga", "data warga", "info warga", "cek warga",
        "pemakaian warga", "tagihan warga", "status warga",
        "cari user", "data user", "cek user"
    ]

];