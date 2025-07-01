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