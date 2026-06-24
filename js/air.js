$(document).ready(function () {
  //console.log("Script air.js jalan...");

  // Fungsi untuk load data tarif saat edit
  function loadTarifData(id_tarif) {
    $.post("../assets/ajax.php", { p: "fetch_tarif", id_tarif: id_tarif }, function (response) {
      const data = JSON.parse(response);
      if (data.status === "success") {
        // Populate form dengan data tarif
        $("#id_tarif").val(data.data.id_tarif);
        $("#tipe_tarif").val(data.data.tipe_tarif);
        $("#tarif").val(data.data.tarif);
        $("input[name='status'][value='" + data.data.status + "']").prop("checked", true);
      }
    });
  }

  // Event handler untuk tombol edit tarif
  $(document).on("click", "a[href*='tarif_edit']", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    const params = new URLSearchParams(url.split("?")[1]);
    const id_tarif = params.get("id_tarif");
    
    // Load data tarif
    loadTarifData(id_tarif);
    
    // Ubah form state untuk edit
    $("#tarif_form button").val("tarif_edit");
    $("#tarif_form input[name='id_tarif']").val(id_tarif).prop("readonly", true);
    
    // Remove hidden id_tarif jika sudah ada (untuk avoid duplikasi)
    $("#tarif_form input[type='hidden'][name='id_tarif']").remove();
    
    // Tampilkan form dan sembunyikan tabel
    $("#form_tarif").show();
    $("#data_tarif").hide();
    
    // Scroll ke form
    $("html, body").animate({ scrollTop: $("#form_tarif").offset().top - 100 }, 500);
  });

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
      "<button type='button' class='btn btn-outline-primary float-start me-2'><i class='fa-solid fa-user-plus fa-fade'></i> User</button>",
    );

    //menambahkan klik add user
    $(".datatable-dropdown button").click(function () {
      //console.log("Tombol Di Klik");
      $("#form_user").show();
      $("#data_user").hide();
    });

    if ($("#alert-user").hasClass("alert-danger")) {
      // entry user gagal - tetap di form
      $("#form_user").show();
      $("#data_user").hide();
    } else if ($("#alert-user").hasClass("alert-success")) {
      // entry user berhasil - kembali ke data
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

    if ($("#alert-tarif").hasClass("alert-danger")) {
      // entry tarif gagal - tetap di form
      $("#form_tarif").show();
      $("#data_tarif").hide();
    } else if ($("#alert-tarif").hasClass("alert-success")) {
      // entry tarif berhasil - kembali ke data
      $("#form_tarif").hide();
      $("#data_tarif").show();
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
      // Reset form untuk add baru
      $("#tarif_form")[0].reset();
      $("#id_tarif").prop("readonly", false);
      $("#tarif_form button").val("tarif_add");
      // Remove hidden id_tarif jika ada
      $("#tarif_form input[type='hidden'][name='id_tarif']").remove();
      
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

    if ($("#alert-meter").hasClass("alert-danger")) {
      // entry meter gagal - tetap di form
      $("#form_meter").show();
      $("#data_meter").hide();
    } else if ($("#alert-meter").hasClass("alert-success")) {
      // entry meter berhasil - kembali ke data
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
        "<button type='button' class='btn btn-outline-danger float-start me-2'><i class='fa-solid fa-square-plus fa-fade me-1'></i> Catat Meter</button>",
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
    //diklik dashboard
    $("#sumary,#chart").show();
    $("#pilih_waktu select[name='pilih_waktu']").on("change", function () {
      bln = $(this).val();
      var level = $("#user_level").val();
      var user = $("#yuser").val();
      
      // console.log("bulan dipilih: "+bln+" level: "+level);
      $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p:"sumary",t:bln,l:level,u:user},
          dataType: "json"
      })
      .done(function(d){
          // console.log("data: "+d[0].jml_pelanggan+" level: "+level)
          if(level=="admin" || level=="petugas") {
              blm_dicatat=d[0].jml_pelanggan-d[2].tercatat;
              $("#sumary .bg-primary h1").text(d[0].jml_pelanggan);
              
              pemakaian = new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(d[1].pemakaian);
              $("#sumary .bg-warning h1").text(pemakaian);
              $("#sumary .bg-success h1").text(d[2].tercatat);
              $("#sumary .bg-danger h1").text(blm_dicatat);
          } else if(level=="bendahara") {
              blm_lunas=d[0].jml_pelanggan-d[2].lunas;
              $("#sumary .bg-primary h1").text(d[0].jml_pelanggan);
              
              pemasukan = new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(d[1].pemasukan);
              $("#sumary .bg-warning h1").text(pemasukan);
              $("#sumary .bg-warning .me-2").text("Rp. ");
              $("#sumary .bg-warning .small").text("Pemasukan");

              $("#sumary .bg-success h1").text(d[2].lunas);
              $("#sumary .bg-success .small").text("Sudah Lunas");

              $("#sumary .bg-danger h1").text(blm_lunas);
              $("#sumary .bg-danger .small").text("Belum Bayar");
          } else {
              // ===== ROLE WARGA =====
              if (bln === "" || bln === undefined) {
                  // Mode default: tampilkan tanggal terakhir (sudah diisi PHP di HTML)
                  // Pastikan kembali ke mode default jika user ganti ke "Bulan"
                  $("#val_waktu_default").removeClass("d-none");
                  $("#val_waktu_pencatatan").addClass("d-none");
                  var tglTerakhir = $("#warga_tgl_terakhir").val();
                  var waktuTerakhir = $("#warga_waktu_terakhir").val();
                  $("#val_waktu_default h1").text(tglTerakhir || '-');                  $("#label_waktu_pencatatan").text(waktuTerakhir ? "Pencatatan Terakhir: " + waktuTerakhir : "Waktu Pencatatan");
              } else {
                  // Mode setelah pilih bulan: tampilkan hari + jam
                  $("#val_waktu_default").addClass("d-none");
                  $("#val_waktu_pencatatan").removeClass("d-none");
                  $("#val_hari_pencatatan").text(d[0].tgl);
                  $("#val_jam_pencatatan").text(d[0].waktu);
                  $("#label_waktu_pencatatan").text("Waktu Pencatatan");
              }

              $("#sumary .bg-warning h1").text(d[0].pemakaian);

              tagihan = new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(d[0].tagihan);
              $("#sumary .bg-success h1").text(tagihan);
              $("#sumary .bg-success .me-2").text("Rp. ");
              $("#sumary .bg-success .small").text("Tagihan");

              stat = d[0].status;
              var statLabel = (stat === "Belum Lunas") ? "BLM LUNAS" : stat;
              $("#sumary .bg-danger h1").text(statLabel);
              $("#sumary .bg-danger .small").text("Status Tagihan");
          }
      })
      .fail(function () {
        console.log("AJAX gagal")
      })

      // ================================================================
      // CHART UNTUK ROLE ADMIN DAN BENDAHARA
      // ================================================================
      if (level == "admin" || level == "bendahara") {

        // --- Chart 1 (Baris 1 Kiri): Total Pemakaian Air/Bulan (Line) ---
        $.ajax({
          
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar", u: user, l: level},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myLineChart");
          if (ctx) {
            if (window.myLineChartInstance) { window.myLineChartInstance.destroy(); }
            window.myLineChartInstance = new Chart(ctx, {
              type: 'line',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Pemakaian (m³)",
                  backgroundColor: "rgba(2,117,216,0.15)",
                  borderColor: "rgba(2,117,216,1)",
                  pointBackgroundColor: "rgba(2,117,216,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverBackgroundColor: "rgba(2,117,216,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  fill: true,
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { color: "rgba(0,0,0,.125)" } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_pemakaian").text(tot + " m³");
          }
        });

        // --- Chart 2 (Baris 1 Kanan): Pie RT vs Kos ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_pie_tipe"},
          dataType: "json"
        })
        .done(function(respon) {
          var ctx = document.getElementById("myPieChart");
          if (ctx) {
            if (window.myPieChartInstance) { window.myPieChartInstance.destroy(); }
            window.myPieChartInstance = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: ["Rumah Tinggal", "Kos"],
                datasets: [{
                  data: [respon["RT"] || 0, respon["Kos"] || 0],
                  backgroundColor: ["rgba(255, 42, 0, 0.85)", "rgba(2,117,216,0.85)"],
                  borderColor: ["rgba(255, 42, 0, 0.85)", "rgba(2,117,216,0.85)"],
                  borderWidth: 2
                }]
              },
              options: {
                legend: { position: 'bottom' },
                tooltips: {
                  callbacks: {
                    label: function(item, data) {
                      var label = data.labels[item.index];
                      var val = data.datasets[0].data[item.index];
                      var total = data.datasets[0].data.reduce(function(a, b){ return a + b; }, 0);
                      var pct = total > 0 ? Math.round(val / total * 100) : 0;
                      return label + ": " + val + " orang (" + pct + "%)";
                    }
                  }
                }
              }
            });
          }
        });

        // --- Chart 3 (Baris 2 Kiri): Total Tagihan/Bulan (Line) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_line", u: user, l: level},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myAreaChart");
          if (ctx) {
            if (window.myAreaChartInstance) { window.myAreaChartInstance.destroy(); }
            window.myAreaChartInstance = new Chart(ctx, {
              type: 'line',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Tagihan (Rp)",
                  backgroundColor: "rgba(255,165,0,0.15)",
                  borderColor: "rgba(255,165,0,1)",
                  pointBackgroundColor: "rgba(255,165,0,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverBackgroundColor: "rgba(255,165,0,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  fill: true,
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { color: "rgba(0,0,0,.125)" } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_tagihan").text("Rp " + new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(tot));
          }
        });

        // --- Chart 4 (Baris 2 Kanan): Total Pemasukan/Bulan (Line) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_line_pemasukan"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myPemasukanChart");
          if (ctx) {
            if (window.myPemasukanChartInstance) { window.myPemasukanChartInstance.destroy(); }
            window.myPemasukanChartInstance = new Chart(ctx, {
              type: 'line',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Pemasukan (Rp)",
                  backgroundColor: "rgba(40,167,69,0.15)",
                  borderColor: "rgba(40,167,69,1)",
                  pointBackgroundColor: "rgba(40,167,69,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverBackgroundColor: "rgba(40,167,69,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  fill: true,
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { color: "rgba(0,0,0,.125)" } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_pemasukan").text("Rp " + new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(tot));
          }
        })
        .fail(function(xhr) { console.log("[DEBUG] chart_line_pemasukan FAIL:", xhr.responseText); });

        // --- Chart 5 (Baris 3 Kiri): Pelanggan Sudah Dicatat/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_sdh_dicatat"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("mySdhDicatatChart");
          if (ctx) {
            if (window.mySdhDicatatChartInstance) { window.mySdhDicatatChartInstance.destroy(); }
            window.mySdhDicatatChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Sudah Dicatat",
                  backgroundColor: "rgba(40,167,69,0.85)",
                  borderColor: "rgba(40,167,69,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

        // --- Chart 6 (Baris 3 Kanan): Pelanggan Belum Dicatat/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_blm_dicatat"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myBlmDicatatChart");
          if (ctx) {
            if (window.myBlmDicatatChartInstance) { window.myBlmDicatatChartInstance.destroy(); }
            window.myBlmDicatatChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Belum Dicatat",
                  backgroundColor: "rgba(220,53,69,0.85)",
                  borderColor: "rgba(220,53,69,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

        // --- Chart 7 (Baris 4 Kiri): Tagihan Sudah Lunas/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_sdh_lunas"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("mySdhLunasChart");
          if (ctx) {
            if (window.mySdhLunasChartInstance) { window.mySdhLunasChartInstance.destroy(); }
            window.mySdhLunasChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Sudah Lunas",
                  backgroundColor: "rgba(2,117,216,0.85)",
                  borderColor: "rgba(2,117,216,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

        // --- Chart 8 (Baris 4 Kanan): Tagihan Belum Lunas/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_blm_lunas"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myBlmLunasChart");
          if (ctx) {
            if (window.myBlmLunasChartInstance) { window.myBlmLunasChartInstance.destroy(); }
            window.myBlmLunasChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Belum Lunas",
                  backgroundColor: "rgba(255,165,0,0.85)",
                  borderColor: "rgba(255,165,0,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

      } else if (level == "petugas") {
        // ================================================================
        // CHART UNTUK ROLE PETUGAS
        // ================================================================

        // --- Chart 1 (Baris 1 Kiri): Total Pemakaian Air/Bulan (Line) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar", u: user, l: level},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myLineChart");
          if (ctx) {
            if (window.myLineChartInstance) { window.myLineChartInstance.destroy(); }
            window.myLineChartInstance = new Chart(ctx, {
              type: 'line',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Pemakaian (m³)",
                  backgroundColor: "rgba(2,117,216,0.15)",
                  borderColor: "rgba(2,117,216,1)",
                  pointBackgroundColor: "rgba(2,117,216,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverBackgroundColor: "rgba(2,117,216,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  fill: true,
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { color: "rgba(0,0,0,.125)" } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_pemakaian").text(tot + " m³");
          }
        });

        // --- Chart 2 (Baris 1 Kanan): Pie RT vs Kos ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_pie_tipe"},
          dataType: "json"
        })
        .done(function(respon) {
          var ctx = document.getElementById("myPieChart");
          if (ctx) {
            if (window.myPieChartInstance) { window.myPieChartInstance.destroy(); }
            window.myPieChartInstance = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: ["Rumah Tinggal", "Kos"],
                datasets: [{
                  data: [respon["RT"] || 0, respon["Kos"] || 0],
                  backgroundColor: ["rgba(255, 42, 0, 0.85)", "rgba(2,117,216,0.85)"],
                  borderColor: ["rgba(255, 42, 0, 0.85)", "rgba(2,117,216,0.85)"],
                  borderWidth: 2
                }]
              },
              options: {
                legend: { position: 'bottom' },
                tooltips: {
                  callbacks: {
                    label: function(item, data) {
                      var label = data.labels[item.index];
                      var val = data.datasets[0].data[item.index];
                      var total = data.datasets[0].data.reduce(function(a, b){ return a + b; }, 0);
                      var pct = total > 0 ? Math.round(val / total * 100) : 0;
                      return label + ": " + val + " orang (" + pct + "%)";
                    }
                  }
                }
              }
            });
          }
        });

        // --- Chart 3 (Baris 2 Kiri): Pelanggan Sudah Dicatat/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_sdh_dicatat"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("mySdhDicatatChart");
          if (ctx) {
            if (window.mySdhDicatatChartInstance) { window.mySdhDicatatChartInstance.destroy(); }
            window.mySdhDicatatChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Sudah Dicatat",
                  backgroundColor: "rgba(40,167,69,0.85)",
                  borderColor: "rgba(40,167,69,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

        // --- Chart 4 (Baris 2 Kanan): Pelanggan Belum Dicatat/Bulan (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar_blm_dicatat"},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myBlmDicatatChart");
          if (ctx) {
            if (window.myBlmDicatatChartInstance) { window.myBlmDicatatChartInstance.destroy(); }
            window.myBlmDicatatChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Belum Dicatat",
                  backgroundColor: "rgba(220,53,69,0.85)",
                  borderColor: "rgba(220,53,69,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, stepSize: 1 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
          }
        });

      } else {
        // ================================================================
        // CHART UNTUK ROLE WARGA
        // ================================================================

        // --- Chart 1 (Kiri): Total Pemakaian Air/Bulan milik warga (Bar) ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_bar", u: user, l: level},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myBarChart");
          if (ctx) {
            if (window.myBarChartInstance) { window.myBarChartInstance.destroy(); }
            window.myBarChartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Pemakaian (m³)",
                  backgroundColor: "rgba(2,117,216,0.85)",
                  borderColor: "rgba(2,117,216,1)",
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { display: true } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_pemakaian").text(tot + " m³");
          }
        });

        // --- Chart 2 (Kanan): Tagihan Air/Bulan milik warga (Line/Area) + BLM LUNAS ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_line", u: user, l: level},
          dataType: "json"
        })
        .done(function(respon) {
          var sumbuX = respon.filter(function(v, i) { return i % 2 === 0; });
          var sumbuY = respon.filter(function(v, i) { return i % 2 !== 0; });
          var ctx = document.getElementById("myAreaChart");
          if (ctx) {
            if (window.myAreaChartInstance) { window.myAreaChartInstance.destroy(); }
            window.myAreaChartInstance = new Chart(ctx, {
              type: 'line',
              data: {
                labels: sumbuX,
                datasets: [{
                  label: "Tagihan (Rp)",
                  backgroundColor: "rgba(2,117,216,0.2)",
                  borderColor: "rgba(2,117,216,1)",
                  pointBackgroundColor: "rgba(2,117,216,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverBackgroundColor: "rgba(2,117,216,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  fill: true,
                  data: sumbuY,
                }]
              },
              options: {
                scales: {
                  xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 12 } }],
                  yAxes: [{ ticks: { min: 0, maxTicksLimit: 5 }, gridLines: { color: "rgba(0,0,0,.125)" } }]
                },
                legend: { display: false }
              }
            });
            var tot = sumbuY.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
            $("#tot_tagihan").text("Rp " + new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(tot));
          }
        });

        // --- Hitung total tagihan BLM LUNAS milik warga ---
        $.ajax({
          type: "post",
          url: "../assets/ajax.php",
          data: {p: "chart_warga_blm_lunas", u: user},
          dataType: "json"
        })
        .done(function(respon) { 
          if (respon.blm_lunas > 0) {
            $("#tot_blm_lunas").text("| BLM LUNAS: Rp " + new Intl.NumberFormat('en-ID', { style: 'decimal' }).format(respon.blm_lunas));
          } else {
            $("#tot_blm_lunas").text("");
          }
        });

      } // end if level == admin/bendahara | petugas | warga

    }).trigger("change"); // Memicu auto-load ringkasan dan grafik saat pertama kali dimuat

    $("#form_user, #data_user, #form_tarif, #data_tarif, #data_meter, #form_meter, #data_pemakaian").hide();
  }
});
