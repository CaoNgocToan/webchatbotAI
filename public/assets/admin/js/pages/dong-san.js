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

function NhanHieuAutocomplete(){
    var nhan_hieu = ["Honda","Yamaha","Suzuki","SYM", "Toyota", "Ford", "KIA", "Mazda"];
    $("#nhan_hieu").autocomplete({
        lookup: nhan_hieu,
        onSelect: function (suggestion) {
            $("#nhan_hieu").val(suggestion.value);
        }
    });
}
function MauSonAutocomplete(){
    var mau_son = ["Đỏ","Đen","Trắng","Nho"];
    $("#mau_son").autocomplete({
        lookup: mau_son,
        onSelect: function (suggestion) {
            $("#mau_son").val(suggestion.value);
        }
    });
}

function LoaiXeAutocomplete(){
    var loai_xe = ["Ô tô con", "Tải mui phủ", "Xe khách"];
    $("#loai_xe").autocomplete({
        lookup: loai_xe,
        onSelect: function (suggestion) {
            $("#loai_xe").val(suggestion.value);
        }
    });
}
