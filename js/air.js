$(document).ready(function () {
    console.log("Script air.js jalan...");
    
    //id summary dan chart disembunyikan
    $("#sumary, #chart").hide();
    //menambhakan tombol add user
    $(".datatable-dropdown").append("<button type='button' class='btn btn-outline-success float-start me-2'><i class='fa-solid fa-user-plus'></i> User</button>");
    //menambahkan klik add user
    $(".datatable-dropdown button").click(function (){
        console.log("Tombol Di Klik");
    })
})