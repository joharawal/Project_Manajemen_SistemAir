$(document).ready(function () {
    //console.log("Script air.js jalan...");

    uri = window.location.href;
    e=uri.split("=");
    console.log("URI: " + uri + " e[1]:" + e[1] + " e[2]:" + e[2]);

    if (e[1] == "user" || e[1] == "user_edit&user") {

        $("#sumary, #chart, #form_user").hide();
        if (e[1] == "user") {
            //id summary dan chart disembunyikan
            $("#sumary, #chart, #form_user").hide();
        } else {
            $("#sumary, #chart, #data_user").hide();
            $("#form_user").show();

            //reset value tombol user_add jadi useer_edit
            $("#user_form button").val('user_edit');

            //mendisble premier key username
            $("#user_form input[name='username']").attr("disabled", true);

            //menambah element input dengan tipe hidden
            $("#user_form").append("<input type=hidden name=username value=" + e[2] +">");
        }


        //menambhakan tombol add user
        $(".datatable-dropdown").append("<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-user-plus'></i> User</button>");
        
        //menambahkan klik add user
        $(".datatable-dropdown button").click(function (){
            //console.log("Tombol Di Klik");
            $("#form_user").show();
            $("#data_user").hide();
        })

        //konfirmasi hapus data user dengan modal
        $("button[data-bs-toggle='modal']").click(function () {
            console.log("Tombol Hapus Di Klik");
            user = $(this).attr('data_user');
            $("#myModal .modal-body").text("Yakin ingin menghapus data username : " + user + " ?");
            $(".modal-footer form").append("<input type=hidden name=user value=" + user +">");
        });

    } else {
        //klik dashboard
        //id summary dan chart disembunyikan
        $("#sumary, #chart").show();
        $("#form_user, #data_user").hide();

    }
    
    
})