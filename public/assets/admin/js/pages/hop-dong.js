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


function ky_ngoai(path){
    $(".ky_ngoai").change(function(){
        var _this = $(this);
        var name = _this.attr("data-name");
        var list = $("#"+name).val();
        for(i=0;i < list.length;i++){
            if(_this.is(":checked")){
                $.get(path+list[i], function(data){
                    $("#ky-ngoai-list").append(data);delete_ky_ngoai();
                });
            } else {
                //remove item
                $(".item_"+list[i]).remove();
            }
        }
    });
}

function delete_ky_ngoai(){
    $(".delete_item").click(function(){
        var _this = $(this);
        var name = _this.attr("href");
        _this.parents("."+name).remove();
    });
}