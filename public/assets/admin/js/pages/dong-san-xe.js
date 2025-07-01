
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