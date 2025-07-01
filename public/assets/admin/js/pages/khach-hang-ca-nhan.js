function chontinh(app_url){
    $(".diachi_tinh").change(function(){
        var _this = $(this);
        $.get(app_url + 'address/get/'+_this.val(), function(tinh){
            _this.closest("div").next().find(".diachi_huyen").html(tinh);
        });
    });
}

function chonhuyen(app_url){
    $(".diachi_huyen").change(function(){
        var _this = $(this);
        $.get(app_url +'address/get/'+_this.val(), function(huyen){
            _this.closest("div").next().find(".diachi_xa").html(huyen);
        });
    });
}

function readURL(input, imgID) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#"+imgID).attr('src', e.target.result).width(200);
            $("#"+imgID+"Data").val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
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
                $("#"+fileID+"-files").prepend(html);delete_file();
                $('.draggable-element').arrangeable();
            }
        },
    }).fail(function() {
        alert('Lỗi không thể Upload đính kèm.');
    });
}

function uploadSingle(input, imgID, path){
    var formData = new FormData($("#dinhkemform")[0]);
    $.ajax({
        url: path, type: "POST",
        cache: false, contentType: false,
        data: formData, processData:false,
        success: function(html) {
            if(html=='Failed'){
                alert('Lỗi không thể Upload hình ảnh.');
            } else {
                $("#"+imgID+"Img").html(html);delete_file();
            }
        },
    }).fail(function() {
        alert('Lỗi không thể Upload hình ảnh.');
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

function them_quanhegiadinh(app_url){
    $("#quan-he-gia-dinh-button").click(function(){
        var html = $("#quanhe_html").html();
        html = html.replace(/select_quanhe/g,"select2");
        $("#quanhegiadinh").append(html);xoa_quanhe();
        $(".select2").select2();change_id_khachhang(app_url);
        add_khachhang(app_url);id_quanhe_1(app_url);
    });
}
function xoa_quanhe(){
    $(".xoa-quanhe").click(function(){
        var _this = $(this);
        _this.parents("div.item-quanhe").remove();
    });
}

function delete_cmnd(){
   $(".delete_cmnd").click(function(){
        var _this = $(this);
        _this.parents("div.item").remove();
   });
}
function add_cmnd(app_url){
    $("#add_cmnd").click(function(){
        var _this = $(this);
        var html = $("#cmnd_html").html();
        html = html.replace(/select_cmnd/g,"select2");
        $("#cmnd_list").append(html);delete_cmnd();
        $(".select2").select2();get_noi_cap(app_url);ngaythangnam();
    });
}

function delete_diachi(){
   $(".delete_diachi").click(function(){
        var _this = $(this);
        _this.parents("div.item").remove();
   });
}

function add_diachi(app_url){
    $("#add_diachi").click(function(){
        var _this = $(this);
        var html = $("#diachi_html").html();
        html = html.replace(/select_diachi/g,"select2");
        $("#diachi_list").append(html);delete_diachi();
        chontinh(app_url); chonhuyen(app_url);
        $(".select2").select2();
    });
}

function get_noi_cap(app_url){
    $('.cmnd').on('change', function (){
        var id_cmnd = $('.id_cmnd').val();
        if(id_cmnd=="cmnd"){
            var _this = $(this);
            var cmnd = _this.val();
            var id_cmnd = _this.closest("div").prev().find(".id_cmnd").val();
            // Kiểm tra cmnd
            var path = app_url + "admin/khach-hang/kiem-tra-cmnd-cccd-hc/"+id_cmnd+"/"+cmnd;
            $.get(path, function(data){
                if(data==1){
                    alert('Số '+id_cmnd+" đã bị trùng");
                }else{
                }
            });
            //
            var path = app_url + "admin/danh-muc/noi-cap-"+id_cmnd+"/get-noi-cap/"+cmnd;
            var noicap = _this.parent().next().next().next().next().children(".noicap");
            $.get(path, function(data){
                //_this.closest("div").next().find(".noicap").val(noicap);
                noicap.val(data);
            });
        }
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

function tinhtranghonnhan(app_url){
    $("#tinhtranghonnhan").change(function(){
        var _this = $(this);
        var tt = _this.val();
        var tinh = $(".diachi_tinh:eq(0)").val();if(!tinh) tinh = '00';
        var huyen = $(".diachi_huyen:eq(0)").val(); if(!huyen) huyen = '000';
        var xa = $(".diachi_xa:eq(0)").val(); if(!xa) xa = '0000';
        var path = app_url + 'admin/danh-muc/dia-chi/get-option/'+tinh+'/'+huyen+'/'+xa;
        var gioitinh = $("#gioitinh").val();
        if(tt == 'Kết hôn' || tt == 'Độc thân'){
            $.get(path, function(data){
                $("#tthn_noicap").prepend('<option value="'+data+'" selected>'+data+'</option>');
            });
            if(tt == 'Kết hôn'){
                var html = $("#quanhe_html").html();
                html = html.replace(/select_quanhe/g,"select2");
                if(gioitinh == 'Nam'){
                    html = html.replace(/s1_vo/g," selected");
                    html = html.replace(/s_chong/g," selected");
                } else {
                    html = html.replace(/s1_chong/g," selected");
                    html = html.replace(/s_vo/g," selected");
                }
                $("#quanhegiadinh").append(html);xoa_quanhe();
                $(".select2").select2();change_id_khachhang(app_url);
                add_khachhang(app_url);id_quanhe_1(app_url);
                //5e5f6077ae92dc2b10003916 vk
            }
        } else {
            $("#tthn_noicap").html('<option value=""></option>');
        }
    });
}

function id_quanhe_1(app_url){
    $(".id_quanhe_1").change(function(){
        var _this = $(this);
        var value = _this.val();
        var path = app_url + 'admin/danh-muc/quan-he/get-list-option/'+value;
        if(value=="vo" || value=="chong"){
            var html = $("#quanhe_docthan_html").html();
            html = html.replace(/select_quanhe/g,"select2");
            var _input = _this.parent().parent().children(".class_id_khachhang");
            _input.html(html);
            $(".select2").select2();

        }else{
            var html = $("#quanhe_html_2").html();
            html = html.replace(/select_quanhe/g,"select2");
            var _input = _this.parent().parent().children(".class_id_khachhang");
            _input.html(html);
            $(".select2").select2();
            change_id_khachhang(app_url);
        }


        var qh = _this.parent().next().children(".id_quanhe");
        $.get(path, function(data){
            qh.html(data);
        });
    });
}
function tinhtrangkhachhang(app_url){
    $("#tinhtrangkhachhang").change(function(){
        var _this = $(this);
        var tt = _this.val();
        var tinh = $(".diachi_tinh:eq(0)").val();if(!tinh) tinh = '00';
        var huyen = $(".diachi_huyen:eq(0)").val(); if(!huyen) huyen = '000';
        var xa = $(".diachi_xa:eq(0)").val(); if(!xa) xa = '0000';
        var path = app_url + 'admin/danh-muc/dia-chi/get-option/'+tinh+'/'+huyen+'/'+xa;
        if(tt == 'Đã chết'){
            $.get(path, function(data){ $("#ttkh_noicap").val(data); });
        } else {
            $("#ttkh_noicap").val("");
        }
    });
}
function change_id_khachhang(app_url){
    $(".id_khachhang").change(function(){
        var _this = $(this);
        var id_kh = _this.val();
        var button = _this.parent().next().next().next().children(".button-upload");
        var list_file = _this.parent().parent().next();
        var html = '<button type="button" class="btn btn-success addFiles" name="quan-he-button" ><i class="fas fa-file-upload"></i> Đính kèm</button><input type="file" name="quan-he-gia-dinh-'+id_kh+'[]" id="quan-he-button" value="" onchange="uploadFiles(this,\'quan-he-gia-dinh-'+id_kh+'\',\''+app_url+'file/uploads/quan-he-gia-dinh-'+id_kh+'\');" multiple style="display: none;">';
        button.html(html);
        list_file.attr('id', 'quan-he-gia-dinh-'+id_kh+'-files');
        addFiles();
    });
}

// JS VK Modal Form
function add_khachhang(app_url){
    $(".add_khachhang").click(function(){
        var _this = $(this);
        var id_khachhang = _this.parent().prev().prev();
        var path = app_url + 'admin/khach-hang/get-form-ca-nhan';
        $.get(path, function(data){
            $("#khachhangform").html(data);$(".select2").select2();
            addFiles();addImage();vk_get_noi_cap(app_url);
            vk_chontinh(app_url);vk_chonhuyen(app_url);
            vk_add_cmnd(app_url);vk_add_diachi(app_url);
            create_canhan_quanhe(id_khachhang, app_url);change_gioitinh();ngaythangnam();
            chon_dongthuongtru(app_url);
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
        $(".select2").select2();vk_get_noi_cap(app_url);ngaythangnam();
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
                    var id_kh = id_khachhang.val();
                    var button = id_khachhang.parent().next().next().next().children(".button-upload");
                    var list_file = id_khachhang.parent().parent().next();
                    var html = '<button type="button" class="btn btn-success addFiles" name="quan-he-button" ><i class="fas fa-file-upload"></i> Đính kèm</button><input type="file" name="quan-he-gia-dinh-'+id_kh+'[]" id="quan-he-button" value="" onchange="uploadFiles(this,\'quan-he-gia-dinh-'+id_kh+'\',\''+app_url+'file/uploads/quan-he-gia-dinh-'+id_kh+'\');" multiple style="display: none;">';
                    button.html(html);
                    list_file.attr('id', 'quan-he-gia-dinh-'+id_kh+'-files');
                    addFiles();
                    $("#modalAddKhachHang").modal("hide");
                }
            },
        }).fail(function() {
            alert('Lỗi không thể thêm');
        });
    });
}

function change_gioitinh(){
    var gt = $("#gioitinh").val();
    if(gt == 'Nam'){
        $("#vk_gioitinh").val("Nữ");
    } else {
        $("#vk_gioitinh").val("Nam");
    }
    $("#vk_gioitinh").select2();
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

function capitalize(){
    $(".capitalize").keyup(function(event) {
        var _this = $(this);
        var box = event.target;
        var txt = $(this).val();
        var stringStart = box.selectionStart;
        var stringEnd = box.selectionEnd;
        //txt = txt.toLowerCase();
        _this.val(txt.replace(/^(.)|(\s|\-)(.)/g, function($word) {
            return $word.toUpperCase();
        }));
        box.setSelectionRange(stringStart , stringEnd);
    });
}
//// NCLINH
function chon_id_diachi(){
    $(".id_cmnd").change(function(){
		var value = $(this).val();
		if(value=="cmnd"){
			$(".noicap").val("");
		}
		if(value=="cccd"){
			$(".noicap").val("Cục Cảnh sát ĐKQL Cư trú và DLQG về Dân cư");
		}
		if(value=="hc"){
			$(".noicap").val("Cục Quản lý xuất nhập cảnh");
		}
	});
}

function chon_dongthuongtru(app_url){
    var value_id_cmnd = "cmnd";
    $(".vk_id_diachi").change(function(){
        var value = $(this).val();
        if(value=="dong-thuong-tru"){
            var tinh = $(".diachi_tinh").val();
            var huyen = $(".diachi_huyen").val();
            var xa = $(".diachi_xa").val();
            var ap = $(".diachi_ap").val();
            $(".vk_diachi_tinh").val($(".diachi_tinh").val()).change();
            setTimeout(() => {
                $.get(app_url+"address/get/"+tinh+"/"+huyen, function(huyen){
                    $(".vk_diachi_huyen").html(huyen);
                });
              }, 100);

              setTimeout(() => {
                $.get(app_url+"address/get/"+huyen+"/"+xa, function(xa){
                    $(".vk_diachi_xa").html(xa);
                });
            }, 100);
            $(".vk_diachi_ap").val(ap);
        }
    });

    $(".id_cmnd").change(function(){
        var value = $(this).val();
        value_id_cmnd = value;
        if(value=="cmnd"){
            $(".vk_noicap").val("");
        }
        if(value=="cccd"){
            $(".vk_noicap").val("Cục Cảnh sát ĐKQL Cư trú và DLQG về Dân cư");
        }
        if(value=="hc"){
            $(".vk_noicap").val("Cục Quản lý xuất nhập cảnh");
        }
    });

    $(".vk_cmnd").change(function(){
        var _this = $(this);
        var cmnd = $(".vk_cmnd").val();
        var value = value_id_cmnd;
        if(cmnd.length > 2 && value == "cmnd" ){
            $.get(app_url+"noi-cap-cmnd/get-noi-cap/"+cmnd, function(noicap){
                $(".vk_cmnd").val(noicap);
            });
        }


    });
}
