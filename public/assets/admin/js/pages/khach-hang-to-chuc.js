function chontinh(app_url){
    $("#address_1").change(function(){
        $.get(app_url + 'address/get/'+$(this).val(), function(tinh){
            $("#address_2").html(tinh);chonhuyen();
        });
    });
}

function chonhuyen(app_url){
    $("#address_2").change(function(){
        $.get(app_url +'address/get/'+$(this).val(), function(huyen){
            $("#address_3").html(huyen);
        });
    });
}

function addFiles(){
    $(".addFiles").click(function(){
        var id = $(this).attr("name"); $("#"+id).click();
    });
}

function addImage(){
    $(".addImage").click(function(){
        var id = $(this).attr('name');$("#"+id).click();
    });
}

function uploadFiles(input, fileID, path){
    var formData = new FormData($("#dinhkemform")[0]);
    $.ajax({
        url: path, type: "POST",
        cache: false, contentType: false,
        data: formData, processData:false,
        success: function(html) {
            if(html=='Failed'){
                alert('Lỗi không thể Upload đính kèm.');
            } else {
                $("#"+fileID+"-list").prepend(html);delete_file();
                $('.draggable-element').arrangeable();
            }
        },
    }).fail(function() {
        alert('Lỗi không thể Upload đính kèm.');
    });
}

function delete_file(){
    var link_delete; var _this;
    $(".delete_file").click(function(){
        link_delete = $(this).attr("href"); _this = $(this);
        $.ajax({
            url: link_delete,
            type: "GET",
            success: function(datas) {
                _this.parents("div.items").fadeOut("slow", function(){
                    $(this).remove();
                });
            }
        }).fail(function() {
            alert('Không thể xoá.');
        });
    });
}

function tinhAutoComplete(path){
    $('.tinhAutocomplete').autocomplete({
        serviceUrl: path,
        dataType: 'json',
        paramName: 'search',
        type: "GET",
        onSelect: function (suggestion) {
            $(this).val(suggestion.data);
        }
    });
}
function ChucVuAutoComplete(){
    var chuc_vu = ["Giám đốc", "Phó Giám đốc", "Chủ tịch", "Phó Chủ tịch"];
    $(".chuc_vu").autocomplete({
        lookup: chuc_vu,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function addNguoiDaiDien(){
    $("#addNuoiDaiDien_html").click(function(){
        var html = $("#nguoidaidienphapluat_html").html();
        html = html.replace(/select_canhan/g,"select2");
        $("#nguoidaidienphapluat").append(html);xoa_nguoidaidien();
        $(".select2").select2();ChucVuAutoComplete();
    });
}

function xoa_nguoidaidien(){
    $(".xoanguoidaidien").click(function(){
        var _this = $(this);
        _this.parents("div.item-nguoidaidien").remove();
    });
}

function create_canhan(){
    $("#addCaNhanForm").submit(function(event){
        event.preventDefault();
        var path = $(this).attr("action");
        $.ajax({
            url: path, type: "POST",
            data: $("#addCaNhanForm").serialize(),
            success: function(html) {
                if(html=='Failed'){
                    alert('Số CMND đã tồn tại CSDL.');
                } else {
                    $("#nguoidaidienphapluat").append(html);xoa_nguoidaidien();$(".select2").select2();
                    $("#modalAddCaNhan").modal("hide");
                }
            },
        }).fail(function() {
            alert('Lỗi không thể thêm');
        });
    });
}

function ngaythangnam(){
    jQuery('.ngaythangnam').change(function(){
        var _this = $(this);
        var val = _this.val();
        val =  val.split('.').join('/');
        val =  val.split('-').join('/');
        val =  val.split('\\').join('/');
        if(val.length == 4) {
            _this.val(val);
        } else if(val.includes("/", 2) && val.includes("/", 5)){
            var a = val.split('/');
            if(parseInt(a[0]) > 31 || parseInt(a[0]) <= 0  ){
                alert('Ngày nhập sai ['+ a[0] +'] nên chuyển sang 31');a[0] = 31;
            }
            if(parseInt(a[1]) > 12 || parseInt(a[1]) <= 0){
                alert('Tháng nhập sai ['+ a[1] +'] nên chuyển sang 12');a[1] = 12;
            }
            if(a[2].length <= 2){
                if(parseInt(a[2]) > 20){
                    a[2] = '19' + a[2];
                } else {
                    a[2] = '20' + a[2];
                }
            }
            _this.val(a.join("/"));
        } else if(val.includes("/") == false && val.includes(".") == false ) {
            if(val.length == 6){
                if(parseInt(val.substr(4,2)) > 20){
                    var nam = "19" + val.substr(4,2);
                } else {
                    var nam = "20" + val.substr(4,2);
                }
            }
            if(val.length == 8){
                var nam = val.substr(4,4);
            }
            var ngay = val.substr(0,2);
            var thang = val.substr(2,2);
            if(parseInt(ngay) > 31 || parseInt(ngay) <= 0  ){
                alert('Ngày nhập sai ['+ ngay +'] nên chuyển sang 31');ngay = 31;
            }
            if(parseInt(thang) > 12 || parseInt(thang) <= 0){
                alert('Tháng nhập sai ['+ thang +'] nên chuyển sang 12');thang = 12;
            }
            _this.val(ngay+"/"+thang+"/"+nam);
        } else {
            alert('Nhập liệu sai, vui lòng nhập lại');
        }
    });
}

function add_khachhang(app_url){
    $(".add_khachhang").click(function(){
        var _this = $(this);
        var id_khachhang = _this.parent().prev().prev();
        var path = app_url + 'admin/khach-hang/get-form-ca-nhan';
        $.get(path, function(data){
            $("#khachhangform").html(data);$(".select2").select2();
            addFiles();addImage();
            vk_get_noi_cap(app_url);
            vk_chontinh(app_url);vk_chonhuyen(app_url);
            vk_add_cmnd(app_url);vk_add_diachi(app_url);
            ngaythangnam();create_canhan_quanhe(id_khachhang, app_url);
        });
    });
}

function create_canhan_quanhe(id_khachhang, app_url){
    $("#vkdinhkemform").submit(function(event){
        event.preventDefault();
        var path = $(this).attr("action");
        $.ajax({
            url: path, type: "POST",
            data: $("#vkdinhkemform").serialize(),
            success: function(html) {
                if(html=='Failed'){
                    alert('Số CMND đã tồn tại CSDL.');
                } else {
                    id_khachhang.prepend(html);
                    $("#modalAddCaNhan").modal("hide");
                }
            },
        }).fail(function() {
            alert('Lỗi không thể thêm');
        });
    });
}
function vk_get_noi_cap(app_url){
    $('.vk_cmnd').on('change', function (){
        var _this = $(this);
        var cmnd = _this.val();
        var id_cmnd = _this.closest("div").prev().find(".id_cmnd").val();
        var path = app_url + "admin/danh-muc/noi-cap-"+id_cmnd+"/get-noi-cap/"+cmnd;
        var noicap = _this.parent().next().next().next().next().children(".vk_noicap");
        $.get(path, function(data){
            noicap.val(data);
        });
    });
}

function vk_chontinh(app_url){
    $(".vk_diachi_tinh").change(function(){
        var _this = $(this);
        $.get(app_url + 'address/get/'+_this.val(), function(tinh){
            _this.closest("div").next().find(".vk_diachi_huyen").html(tinh);
        });
    });
}

function vk_chonhuyen(app_url){
    $(".vk_diachi_huyen").change(function(){
        var _this = $(this);
        $.get(app_url +'address/get/'+_this.val(), function(huyen){
            _this.closest("div").next().find(".vk_diachi_xa").html(huyen);
        });
    });
}

function vk_add_cmnd(app_url){
    $("#vk_add_cmnd").click(function(){
        var _this = $(this);
        var html = $("#vk_cmnd_html").html();
        html = html.replace(/select_cmnd/g,"select2");
        $("#vk_cmnd_list").append(html);delete_cmnd();
        $(".select2").select2();vk_get_noi_cap(app_url);
    });
}

function vk_add_diachi(app_url){
    $("#vk_add_diachi").click(function(){
        var _this = $(this);
        var html = $("#vk_diachi_html").html();
        html = html.replace(/select_diachi/g,"select2");
        $("#vk_diachi_list").append(html);delete_diachi();
        vk_chontinh(app_url); vk_chonhuyen(app_url);
        $(".select2").select2();
    });
}

function vkuploadSingle(input, imgID, path){
    var formData = new FormData($("#vkdinhkemform")[0]);
    $.ajax({
        url: path, type: "POST",
        cache: false, contentType: false,
        data: formData, processData:false,
        success: function(html) {
            if(html=='Failed'){
                alert('Lỗi không thể Upload hình ảnh.');
            } else {
                $("#vk-"+imgID+"Img").html(html);delete_file();
            }
        },
    }).fail(function() {
        alert('Lỗi không thể Upload hình ảnh.');
    });
}

function vkuploadFiles(input, fileID, path){
    var formData = new FormData($("#vkdinhkemform")[0]);
    $.ajax({
        url: path, type: "POST",
        cache: false, contentType: false,
        data: formData, processData:false,
        success: function(html) {
            if(html=='Failed'){
                alert('Lỗi không thể Upload đính kèm.');
            } else {
                $("#vk-"+fileID+"-files").prepend(html);delete_file();
                $('.draggable-element').arrangeable();
            }
        },
    }).fail(function() {
        alert('Lỗi không thể Upload đính kèm.');
    });
}
