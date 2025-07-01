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
function CoQuanXacNhanBienDongAutocomplete(){
    var co_quan = ["Văn phòng đăng ký đất đai chi nhánh Long Xuyên", "Văn phòng đăng ký đất đai chi nhánh Thoại Sơn", "Văn phòng đăng ký đất đai chi nhánh Châu Thành", "Văn phòng đăng ký đất đai chi nhánh Chợ Mới", "Văn phòng đăng ký đất đai chi nhánh Châu Phú", "Văn phòng đăng ký đất đai chi nhánh Phú Tân", "Văn phòng đăng ký đất đai chi nhánh Tân Châu", "Văn phòng đăng ký đất đai chi nhánh Tri Tôn", "Văn phòng đăng ký đất đai chi nhánh Tịnh Biên", "Văn phòng đăng ký đất đai chi nhánh An Phú", "Văn phòng đăng ký đất đai chi nhánh Tân Châu", "Văn phòng đăng ký đất đai chi nhánh Châu Đốc"];
    $(".co_quan_xac_nhan_bien_dong").autocomplete({
        lookup: co_quan,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function NoiCapGCNAutocomplete(){
    var noi_cap = ["Sở Tài nguyên và Môi trường","UBND Thành phố Long Xuyên, tỉnh An Giang", "UBND huyện Thoại Sơn, tỉnh An Giang", "UBND huyện Châu Thành, tỉnh An Giang", "UBND huyện Chợ Mới, tỉnh An Giang", "UBND huyện Châu Phú tỉnh An Giang", "UBND Thành phố Long Xuyên, tỉnh An Giang", "UBND huyện Phú Tân, tỉnh An Giang", "UBND Thành phố Châu Đốc, tỉnh An Giang"];
    $(".noi_cap").autocomplete({
        lookup: noi_cap,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function NguonGocSuDungAutocomplete(){
    var nguon_goc = ["Công nhận QSDĐ như giao đất có thu tiền sử dụng đất","Công nhận QSDĐ như giao đất không thu tiền sử dụng đất","Nhà nước giao đất không thu tiền sử dụng đất","Nhà nước giao đất có thu tiền sử dụng đất","Nhận chuyển nhượng đất được Nhà nước giao có thu tiền sử dụng đất","Nhận chuyển nhượng đất được Nhà nước giao không thu tiền sử dụng đất"];
    $(".nguon_goc_su_dung").autocomplete({
        lookup: nguon_goc,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function ThoiHanSuDungAutocomplete(){
    var thoi_han = ["Lâu dài"];
    $(".thoi_han_su_dung").autocomplete({
        lookup: thoi_han,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function HinhThucSuDungAutocomplete(){
    var hinh_thuc = ["Sử dụng riêng", "Sử dụng chung"];
    $(".hinh_thuc_su_dung").autocomplete({
        lookup: hinh_thuc,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });
}
function LoaiDatChange(){
    var muc_dich_su_dung = {"T" : "Thổ cư", "ODT" : "Đất ở Đô thị", "ONT" : "Đất ở Nông thôn", "2L" : "Đất trồng cây hàng năm", "CLN" : "Đất trồng cây lâu năm", "LNK": "Đất trồng cây lâu năm khác", "LUC" : "Đất chuyên trồng lúa nước", "RSX" : "Đất rừng sản xuất", "NTS" : "Đất nuôi trồng thủy sản", "NKH" : "Đất nông nghiệp khác", "LUA" : "Đất trồng lúa"};
    $(".loai_dat").change(function(){
        var _this = $(this);
        var id = _this.val();
        var loaidat = muc_dich_su_dung[id];
        _this.parent().next().children(".muc_dich_su_dung").val(loaidat);
    });
}
function LoaiDatAutocomplete(){
    var loai_dat = ["T", "ODT", "ONT", "2L", "LUA", "LNK", "CLN", "AO", "LUC", "LUK", "LUN", "BHK", "NHK", "CLN", "RSX", "RPH", "RĐ", "NTS", "LMU", "NKH", "TSC", "DTS", "DVH", "DYT", "DGD", "DTT", "DKH", "DXH","DNG", "DSK", "CQP","CAN","SKK","SKT","SKN","SKC","TMD","SKS","SKX","DGT","DTL","DNL","DBV","DSH","DKV","DCH","ĐT","ĐL","DRA","DCK","TON","TIN","NTD","SON","MNC","PNK"];
    $(".loai_dat").autocomplete({
        lookup: loai_dat,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
        }
    });

    /*
    lookup: function(query, done){
            var result = {
                suggestions: [
                    { "value": " United Arab Emirates", "data": "AE" },
                    { "value": "United Kingdom",       "data": "UK" },
                    { "value": "United States",        "data": "US" }
                ]
            };
            done(result);
        },
    */
}
function ThemLoaiDat(){
    $("#them_loaidat").click(function(){
        var html = $("#loai_dat_html").html();
        $("#loai_dat_list").append(html);
        LoaiDatChange();ThoiHanSuDungAutocomplete();NguonGocSuDungAutocomplete();LoaiDatAutocomplete();XoaLoaiDat();
        ld_dien_tich();$(".sotien").number(true, 2, ".", ",");
    });
}
function XoaLoaiDat(){
    $(".xoa_loaidat").click(function(){
        var _this = $(this);
        _this.parents(".items").remove();
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

function ld_dien_tich(){
    $(".ld_dien_tich").focus(function(e){
        var _this = $(this);
        var dien_tich = $("#dien_tich").val();
        dien_tich = parseFloat(dien_tich);
        var ld_dien_tich = 0;
        $(".ld_dien_tich").each(function(index){
            var ld = $(this).val();
            ld = parseFloat(ld);
            ld_dien_tich = ld_dien_tich + ld;
        });
        _this.val(dien_tich - ld_dien_tich);
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
                a[2] = '19' + a[2];
            }
            _this.val(a.join("/"));
        } else if(val.includes("/") == false && val.includes(".") == false ) {
            if(val.length == 6){
                var nam = "19" + val.substr(4,2);
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
