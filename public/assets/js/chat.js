var ChatForm = $("#ChatForm");
var Messages = $('#messages');
var ChatHistory = $('#chat-history');
var loadInterval;

function loader(element) {
    element.textContent = ''
    loadInterval = setInterval(function(){
        // Update the text content of the loading indicator
        //element.textContent += '•';
        element.textContent += '▊';
        // If the loading indicator has reached three dots, reset it
        if (element.textContent === '▊▊' || element.length > 10) {
            element.textContent = '';
        }
    }, 100);
}

function typeText(element, text) {
    var index = 0;
    var interval = setInterval(function(){
        if (index < text.length) {
            element.append(text.charAt(index));
            index++;
        } else {
            clearInterval(interval);
        }
    }, 10);
}

// generate unique ID for each message div of bot
// necessary for typing text effect for that specific reply
// without unique ID, typing text will work on every element
function generateUniqueId() {
    const timestamp = Date.now();
    const randomNumber = Math.random();
    const hexadecimalString = randomNumber.toString(16);
    return `id-${timestamp}-${hexadecimalString}`;
}

function chatStripe(isAi, value, uniqueId) {
    return (`<div class="message ${isAi ? 'other-message' : 'my-message'} "><i class="fa-solid ${isAi ? 'fa-robot' : 'fa-user'}"></i> <span id="${uniqueId}">${value}</span></div>`);
}

function handleSubmit() {
    var uniqueId; var messageDiv;
    ChatForm.submit(function(e){
        e.preventDefault();
        clearInterval(loadInterval);
        var title = $("#title").val();
        if(title){
            uniqueId = generateUniqueId();
            Messages.append(chatStripe(false, title, uniqueId));
            var href = ChatForm.attr("action");
            $(".loading").show();$("#title").val("");
            uniqueId = generateUniqueId();
            Messages.append(chatStripe(true, " ", uniqueId));
            ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
            messageDiv = document.getElementById(uniqueId);
            // messageDiv.innerHTML = "..."
            loader(messageDiv);
            $.ajax({
              url: href,
              type: "POST",
              dataType: 'html',
              data: {
                title: title,
                _token: $("#_token").val()
              }
            }).done(function(result){
                if(result) {
                    clearInterval(loadInterval);
                    messageDiv.innerHTML = " ";
                    typeText(messageDiv, result);
                    ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
                } else {
                    messageDiv.innerHTML = 'Có lỗi xảy ra, Vui lòng tải lại trang...';
                    ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
                    clearInterval(loadInterval);
                }
                //$(".loading").hide();
            }).fail(function(result){
                messageDiv.innerHTML = 'Có lỗi xảy ra, Vui lòng tải lại trang...';
                ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
                clearInterval(loadInterval);
                //alert("Có lỗi xảy ra, Vui lòng tải lại trang...");
            });
        }
    });
}
handleSubmit();
//$(".loading").hide();
/*$(".loading").hide();
        var ChatHistory=$('#chat-history');
        ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
        var ChatForm = $("#ChatForm");
        var href = ChatForm.attr("action");
        ChatForm.submit(function(e){
          e.preventDefault();
          var title = $("#title").val();
          if(title){
            $(".messages").append('<div class="message my-message"><i class="fa-solid fa-user"></i> '+$("#title").val()+'</div>');
            $(".loading").show();$("#title").val("");
            ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
            $.ajax({
              url: href,
              type: "POST",
              dataType: 'html',
              data: {
                title: title,
                _token: $("#_token").val()
              },
              success:function(result){
                $(".messages").append(result);
                ChatHistory.animate({scrollTop: ChatHistory.prop("scrollHeight")});
                $(".loading").hide();
              }
            });
          }
        });
*/
