$(document).ready(function () {
  //console.log("Script air.js jalan...");

  uri = window.location.href;
  e = uri.split("=");
  console.log("URI: " + uri + " e[1]:" + e[1] + " e[2]:" + e[2]);

  if (e[1] == "user" || e[1] == "user_edit&user") {
    $(
      "#pilih_waktu, #sumary, #chart, #form_user, #form_tarif, #data_tarif, #form_meter, #data_meter, #data_pemakaian",
    ).hide();
    if (e[1] == "user") {
      //id summary dan chart disembunyikan
      $("#pilih_waktu, #sumary, #chart, #form_user").hide();
    } else {
      $("#pilih_waktu, #sumary, #chart, #data_user").hide();
      $("#form_user").show();

      //reset value tombol user_add jadi user_edit
      $("#user_form button").val("user_edit");

      //mendisble premier key username
      $("#user_form input[name='username']").attr("disabled", true);

      //menambah element input dengan tipe hidden
      $("#user_form").append(
        "<input type=hidden name=username value=" + e[2] + ">",
      );
    }

    //menambhakan tombol add user
    $(".datatable-dropdown").append(
      "<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-user-plus fa-fade'></i> User</button>",
    );

    //menambahkan klik add user
    $(".datatable-dropdown button").click(function () {
      //console.log("Tombol Di Klik");
      $("#form_user").show();
      $("#data_user").hide();
    });

    if ($("alert-user").hasClass("alert-danger")) {
      // entry meter gagal
      $("#form_user").show();
      $("#data_user").hide();
    } else if ($("alert-user").hasClass("alert-success")) {
      // entry meter berhasil
      $("#form_user").hide();
      $("#data_user").show();
    }

    //konfirmasi hapus data user dengan modal
    $("button[data-bs-toggle='modal'][data_user]").click(function () {
      console.log("Tombol Hapus Di Klik");
      user = $(this).attr("data_user");
      $("#myModal .modal-body").text(
        "Yakin hapus data username : " + user + " ?",
      );
      $("#modal_delete_btn").val("user_hapus");
      $(".modal-footer form .modal-hidden").remove();
      $(".modal-footer form").append(
        "<input type=hidden class='modal-hidden' name=user value=" + user + ">",
      );
    });
  } else if (e[1] == "tarif" || e[1] == "tarif_edit&id_tarif") {
    $(
      "#pilih_waktu, #sumary, #chart, #form_user, #data_user, #form_meter, #data_meter, #data_pemakaian",
    ).hide();

    if (e[1] == "tarif") {
      //id summary dan chart disembunyikan
      $("#form_tarif").hide();
      $("#data_tarif").show();
    } else {
      $("#pilih_waktu, #sumary, #chart, #data_tarif").hide();
      $("#form_tarif").show();

      //reset value tombol user_add jadi user_edit
      $("#tarif_form button").val("tarif_edit");

      //mendisble premier key id_tarif
      $("#tarif_form input[name='id_tarif']").attr("disabled", true);

      //menambah element input dengan tipe hidden
      $("#tarif_form").append(
        "<input type=hidden name=id_tarif value=" + e[2] + ">",
      );
    }

    const datatablesSimple = document.getElementById("tarif_table");
    if (datatablesSimple) {
      new simpleDatatables.DataTable(datatablesSimple);
    }

    //menambhakan tombol add tarif
    $(".datatable-dropdown").append(
      "<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-money-bill-1 fa-fade'></i> Tarif</button>",
    );

    //menambahkan klik add tarif
    $(".datatable-dropdown button").click(function () {
      //console.log("Tombol Di Klik");
      $("#form_tarif").show();
      $("#data_tarif").hide();
    });

    //konfirmasi hapus data tarif dengan modal
    $("button[data-bs-toggle='modal'][data_id_tarif]").click(function () {
      console.log("Tombol Hapus Di Klik");
      id_tarif = $(this).attr("data_id_tarif");
      $("#myModal .modal-body").text(
        "Yakin hapus data tarif : " + id_tarif + " ?",
      );
      $("#modal_delete_btn").val("tarif_hapus");
      $(".modal-footer form .modal-hidden").remove();
      $(".modal-footer form").append(
        "<input type=hidden class='modal-hidden' name=id_tarif value=" +
        id_tarif +
        ">",
      );
    });
  } else if (e[1] == "catat_meter" || e[1] == "meter_edit&no") {
    $(
      "#pilih_waktu, #sumary, #chart, #form_user, #data_user, #form_tarif, #data_tarif, #data_pemakaian",
    ).hide();

    if (e[1] == "catat_meter") {
      //id summary dan chart disembunyikan
      $("#form_meter").hide();
    } else {
      $("#pilih_waktu, #sumary, #chart, #data_meter").hide();
      $("#form_meter").show();

      //reset value tombol user_add jadi user_edit
      $("#meter_form button").val("meter_edit");

      //mendisble premier key id_tarif
      $("#meter_form input[name='no']").attr("disabled", true);

      //menambah element input dengan tipe hidden
      $("#meter_form").append("<input type=hidden name=no value=" + e[2] + ">");

      //menambah element input hidden untuk username (karena select disabled tidak mengirim value)
      var selectedUsername = $("#meter_form select[name='username']").val();
      $("#meter_form").append(
        "<input type=hidden name=username value=" + selectedUsername + ">",
      );
    }

    if ($("alert-meter").hasClass("alert-danger")) {
      // entry meter gagal
      $("#form_meter").show();
      $("#data_meter").hide();
    } else if ($("alert-meter").hasClass("alert-success")) {
      // entry meter berhasil
      $("#form_meter").hide();
      $("#data_meter").show();
    }

    const datatablesSimple = document.getElementById("meter_table");
    if (datatablesSimple) {
      new simpleDatatables.DataTable(datatablesSimple);
    }
    //menambhakan tombol catat meter (hanya untuk admin dan petugas)
    var userLevel = $("#user_level").val();
    if (userLevel != "bendahara") {
      $(".datatable-dropdown").append(
        "<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-square-plus fa-fade me-1'></i> Catat Meter</button>",
      );

      //menambahkan klik add meter
      $(".datatable-dropdown button").click(function () {
        //console.log("Tombol Di Klik");
        $("#form_meter").show();
        $("#data_meter").hide();
        $("#meter_form input, #meter_form textarea").val("");
      });
    }

    //konfirmasi hapus data tarif dengan modal
    $("button[data-bs-toggle='modal'][data_no]").click(function () {
      console.log("Tombol Hapus Di Klik");
      no = $(this).attr("data_no");
      $("#myModal .modal-body").text("Yakin hapus data meter : " + no + " ?");
      $("#modal_delete_btn").val("meter_hapus");
      $(".modal-footer form .modal-hidden").remove();
      $(".modal-footer form").append(
        "<input type=hidden class='modal-hidden' name=no value=" + no + ">",
      );
    });
  } else if (e[1] == "lihat_pemakaian_warga") {
    //klik lihat pemakaian warga
    $(
      "#pilih_waktu, #sumary, #chart, #form_user, #data_user, #form_tarif, #data_tarif, #form_meter, #data_meter",
    ).hide();
    $("#data_pemakaian").show();

    const datatablesSimple = document.getElementById("pemakaian_table");
    if (datatablesSimple) {
      new simpleDatatables.DataTable(datatablesSimple);
    }
  } else if (e[1] == "pembayaran_warga") {
    //klik pembayaran warga - tampilkan data meter dengan status pembayaran
    $(
      "#pilih_waktu, #sumary, #chart, #form_user, #data_user, #form_tarif, #data_tarif, #form_meter, #data_pemakaian",
    ).hide();
    $("#data_meter").show();

    const datatablesSimple = document.getElementById("meter_table");
    if (datatablesSimple) {
      new simpleDatatables.DataTable(datatablesSimple);
    }
  } else {
    //klik dashboard
    //id summary dan chart disembunyikan
    $("#pilih_waktu, #sumary, #chart").show();
    $("#pilih_waktu select[name='pilih_waktu']").on("change", function () {
      bln = $(this).val();
      var userLevel = $("#user_level").val();
      console.log("bulan yang dipilih: " + bln + ", level: " + userLevel);

      $.ajax({
        type: "post",
        url: "../assets/ajax.php",
        data: { p: "sumary", t: bln, level: userLevel },
        dataType: "json"
      })
        .done(function (d) {
          if (userLevel == 'admin' || userLevel == 'petugas') {
            $("#val_pelanggan").text(d.jml_pelanggan);
            $("#val_pemakaian").text(d.jml_pemakaian);
            $("#val_tercatat").text(d.tercatat);
            $("#val_belum_tercatat").text(d.belum_tercatat);
          } else if (userLevel == 'bendahara') {
            $("#val_pelanggan").text(d.jml_pelanggan);
            $("#val_pemasukan").text(d.total_pemasukan);
            $("#val_lunas").text(d.warga_lunas);
            $("#val_belum_lunas").text(d.warga_belum_lunas);
          } else if (userLevel == 'warga') {
            $("#val_waktu_pencatatan").html(d.waktu_pencatatan);
            $("#val_pemakaian_warga").text(d.pemakaian);
            $("#val_tagihan_warga").text(d.tagihan);
            $("#val_status_warga").text(d.status);
          }
        })
        .fail(function () {
          console.log("AJAX gagal")
        })
    })
    $("#form_user, #data_user, #form_tarif, #data_tarif, #data_meter, #form_meter, #data_pemakaian",).hide();
  }
});
