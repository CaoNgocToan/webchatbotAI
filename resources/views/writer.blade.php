<!doctype html>
<html>
  <head>
    <title>VietGPT-Chat</title>
  </head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ env('APP_URL') }}assets/css/style.css" />
  <body>
  <div class="container">
    <div class="title"><h1>VietGPT Chat</h1></div>
    <div id="messages">
      <div>Xin chào bạn đến với RASA Chat..., Bạn vui lòng nhập từ khóa cần trao đổi phía dưới.</div>
    {{-- <div class="loading"><img src="{{ env('APP_URL') }}assets/images/loading.svg" /></div> --}}
      <div class="loading" style="display:none;"><img src="{{ env('APP_URL') }}assets/images/loading.svg" /></div>
    </div>
  </div>
  <form id="ChatForm" action="{{ env('APP_URL') }}RASA/generate" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <input type="text" placeholder="Vui lòng nhập từ khóa cần trao đổi..." id="title" name="title" value="{{ $title }}" autocomplete="off" /><button>Send</button>
  </form>
  <script src="{{ env('APP_URL') }}assets/js/jquery-3.6.3.min.js"></script>
  <script type="text/javascript">
      jQuery(document).ready(function(){
        var ChatForm = $("#ChatForm");
        var href = ChatForm.attr("action");
        ChatForm.submit(function(e){
          e.preventDefault();
          var title = $("#title").val();
          if(title){
            $("#messages").append("<div>"+$("#title").val()+"</div>");
            $("#messages").append('<div class="loading"><img src="{{ env('APP_URL') }}assets/images/loading.svg" /></div>');
            window.scrollTo(0, document.body.scrollHeight);
            $.ajax({
              url: href,
              type: "POST",
              dataType: 'html',
              data: {
                title: title,
                _token: $("#_token").val()
              },
              success:function(result){
                $(".loading").remove();
                $("#messages").append(result);
                window.scrollTo(0, document.body.scrollHeight);
              }
            });
          }
        });
      });

      /*
      var messages = document.getElementById('messages');
      var form = document.getElementById('ChatForm');
      var input = document.getElementById('input');
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (input.value) {
          socket.emit('chat message', input.value);
          input.value = '';
        }
      });*/
  </script>
  </body>
</html>
