function sent_request(id) {
      // console.log('hii')
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          user_id_two: id,
          action: 'friend_request_sent'
        },
        success: function(response) {
           console.log(response)
          $('#' + id).text('Friend Request Sent');
          $('#' + id).attr('disabled', true);

        }
      });
    }
    function accept_sent_request(id) {
      // console.log('hii')
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          user_id_two: id,
          action: 'accept_friend_request'
        },
        success: function(response) {
          console.log(response)
          location.reload();

        }
      });
    }
    function cancel_sent_request(id) {
      // console.log('hii')
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          user_id_two: id,
          action: 'cancel_friend_request'
        },
        success: function(response) {
          console.log(response)
          location.reload();

        }
      });
    }
    function like_post(p_id)
    {
      post_id=p_id;
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          post_id:post_id,
          action:'like_post'
        },
        success: function (response) {
          $('#'+post_id).attr('onclick', 'unlike_post('+post_id+')')
          $('#'+post_id).removeClass('text-green');
          $('#'+post_id).addClass('text-primary');
          val= parseInt($('#'+post_id+" span").text())+1;
          $('#'+post_id+" span").text(""+val)
          
        }
      });

    }
    function unlike_post(p_id)
    {
      post_id=p_id;
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          post_id:post_id,
          action:'unlike_post'
        },
        success: function (response) {
          console.log(response)
          $('#'+post_id).attr('onclick', 'like_post('+post_id+')')
          $('#'+post_id).removeClass('text-green text-primary');
          $('#'+post_id).addClass('text-green');
          val= parseInt($('#'+post_id+" span").text())-1;
          $('#'+post_id+" span").text(""+val)
          
        }
      });

    }
    function send_message() {
      var id = $('#friend_id').val();
      var msg = $('#text-message').val();
      console.log(msg)
      if (msg != '') {
        $.ajax({
          type: "post",
          url: "php functions/2.php",
          data: {
            id: id,
            msg: msg,
            action: 'send_message'
          },
          success: function(response) {
            $('#text-message').val("");
            var element = $('#text-message').emojioneArea();
              element[0].emojioneArea.setText('');
            // initializemoji()
            scrollToBottom();
          }
        });
      }
    }
    function initializemoji()
    {
      $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: 'vendor/emoji-picker/lib/img/',
          popupButtonClasses: 'icon-smile'
        });

        window.emojiPicker.discover();
      });

    }

    function get_chat_list() {
      $.ajax({
        type: "post",
        url: "php functions/2.php",
        data: {
          action: 'get_user_list'
        },
        success: function(response) {

          $('.' + 'user-list').html(response);
        }
      });
    }

    function get_chat_messages() {
      var id = $('#friend_id').val();
      $.ajax({
        type: "post",
        url: "php functions/2.php",
        data: {
          id: id,
          action: 'get_chat_messages'
        },
        success: function(response) {
          $('.chat-body').html(response);
          if (!$('.chat-room').hasClass('scrolled')) {
             scrollToBottom()
          }
        }
      });
    }

    function updateOnlineFriends() {
      $.ajax({
        url: 'php functions/update_stat.php',
        success: function(response) {
          // console.log(response)
        $('#chat-block').html(response);
        }
      });
    }
    
    // Update friends list every 3 seconds.
    function toggle(elementId) {
      elementId = elementId + "c";
      var ele = document.getElementById(elementId);
      $(ele).collapse('toggle')
      // if ($(ele).hasClass('collapse')) {
      //     $(ele).removeClass('collapse');
      // } else {
      //     $(ele).addClass('collapse');
      // }
    }
    function scrollToBottom() {

      $('.tab-content').scrollTop($('.chat-body').height())
    }
  

    $(document).ready(function() { 
      $(document).on('keyup', '#search_friend', function(){
    	var search_value = $('#search_friend').val();
    	if(search_value.trim() != '')
    	{
        console.log(search_value)
        $.ajax({
          type: "post",
          url: "php functions/search_action.php",
          data: {
            search_value:search_value,
            action:'filter_results'
          },
          
          success: function (response) {
            // console.log(response)
            $('.searchlist').html(response);
          }
        });
    	}
    	else
    	{
        $('.searchlist').html('');
    		console.log('None')
    	}
    });
      $(".inputComment").keypress(function(e) {
        if (e.which == 13) {
          var val = $(this).val();
          var id = $(this).attr('id');
          $.ajax({
            type: "post",
            url: "php functions/curd_data_man.php",
            data: {
              text: val,
              post_id: id,
              action: 'comment'
            },
            success: function(response) {
              location.reload();
            }
          });
        }
      });
      var id = $('#friend_id').val();
      if (id != '') {
        setInterval(get_chat_messages, 1000);
      }
      setInterval(updateOnlineFriends, 5000);
      setInterval(get_chat_list, 2000);
      // initializemoji()
      $('#text-message').emojioneArea({
        pickerPosition:"top",
        toneStyle: "bullet"
       });

      $(".chat-room").mouseenter(function() {
        if(!$(".chat-room").hasClass('scrolled')){
          $(".chat-room").addClass('scrolled');
        }
      });
      $(".chat-room").mouseleave(function () { 
        $(".chat-room").removeClass('scrolled');
      });
    });
  