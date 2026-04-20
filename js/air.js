$(document).ready(function () {
    console.log("Script air.js jalan...");

    // id summary dan chart disembunyikan
    $("#sumary, #chart").hide();

    // Menambahkan tombol "Tambah User" di sebelah dropdown datatable
    $(".datatable-dropdown").append(
        "<a href='index.php?p=user' class='btn btn-outline-success float-start me-2'>" +
        "<i class='fa-solid fa-user-plus'></i> Tambah User</a>"
    );

    // Saat tombol Hapus diklik, isi modal dengan username yang akan dihapus
    $(document).on("click", ".btn-hapus", function () {
        var username = $(this).data("user");
        $("#namaUserHapus").text(username);
        $("#btnKonfirmasiHapus").attr("href", "index.php?p=user_delete&user=" + encodeURIComponent(username));
    });
});
