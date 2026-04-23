$(document).ready(function () {
  //console.log("Script air.js jalan...");

  uri = window.location.href;
  e = uri.split("=");
  console.log("URI: " + uri + " e[1]:" + e[1] + " e[2]:" + e[2]);

  if (e[1] == "user" || e[1] == "user_edit&user") {
    $("#sumary, #chart, #form_user, #form_tarif, #data_tarif").hide();
    if (e[1] == "user") {
      //id summary dan chart disembunyikan
      $("#sumary, #chart, #form_user").hide();
    } else {
      $("#sumary, #chart, #data_user").hide();
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
      "<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-user-plus'></i> User</button>",
    );

    //menambahkan klik add user
    $(".datatable-dropdown button").click(function () {
      //console.log("Tombol Di Klik");
      $("#form_user").show();
      $("#data_user").hide();
    });

    //konfirmasi hapus data user dengan modal
    $("button[data-bs-toggle='modal']").click(function () {
      console.log("Tombol Hapus Di Klik");
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
    $("#sumary, #chart, #form_user, #data_user").hide();

    if (e[1] == "tarif") {
      //id summary dan chart disembunyikan
      $("#form_tarif").hide();
      $("#data_tarif").show();
    } else {
      $("#sumary, #chart, #data_tarif").hide();
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
      "<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-money-bill-1-wave fa-beat'></i> Tarif</button>",
    );

    //menambahkan klik add tarif
    $(".datatable-dropdown button").click(function () {
      //console.log("Tombol Di Klik");
      $("#form_tarif").show();
      $("#data_tarif").hide();
    });

    //konfirmasi hapus data tarif dengan modal
    $("button[data-bs-toggle='modal']").click(function () {
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
  } else {
    //klik dashboard
    //id summary dan chart disembunyikan
    $("#sumary, #chart").show();
    $("#form_user, #data_user, #form_tarif, #data_tarif").hide();
  }
});
