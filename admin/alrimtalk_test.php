
<html>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>알림톡 테스트 페이지</title>
  <script src="//code.jquery.com/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
    });
    function sendTalk() {
      let phone = $('#phone').val();
      $.ajax({
            url: '/admin/sendtalk.php',
            type: 'post',
            data: {
                'phone': phone
            },
            success: function (data) {
              alert('send success');
                console.log(data);
            },
            error: function (request, status, error) {
              alert('send error');
                console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
            }
        });
    }
</script>
  <body>
    <input type="number" id="phone" placeholder="010xxxxxxxx" value="">
    <button id="send_btn" onclick="sendTalk()">send</button>
  </body>
  
</html>