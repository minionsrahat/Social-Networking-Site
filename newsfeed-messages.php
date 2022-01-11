<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="This is social network html5 template available in themeforest......" />
  <meta name="keywords" content="Social Network, Social Media, Make Friends, Newsfeed, Profile Page" />
  <meta name="robots" content="index, follow" />
  <title>Chatroom | Send and Receive Messages</title>

  <!-- Stylesheets
    ================================================= -->
  <?php
  session_start();
  include('layouts/css_links.php');
  if (!isset($_SESSION['user_id'])) {
    header("location:index-register.php");
  }
  ?>


  <!--Google Font-->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">

  <!--Favicon-->
  <link rel="shortcut icon" type="image/png" href="images/fav.png" />
  <style>
.icon-smile:before {
    content: " ";
    width: 16px;
    height: 16px;
    display: flex;
    background: url(icon-smile.png);
}
.emoji-picker-icon{
  position: absolute;
    right: 66px;
    top: 9px;
}

  </style>
</head>

<body>

  <?php
  include('php functions/db_connection.php');
  include('php functions/php_query_functions.php');
  ?>
  <!-- Header
    ================================================= -->
  <?php include('layouts/navbar.php') ?>
  <!--Header End-->

  <div id="page-contents">
    <div class="container">
      <div class="row">

        <!-- Newsfeed Common Side Bar Left
          ================================================= -->
        <div class="col-md-3 static">
          <?php include('layouts/profile_card.php') ?>
        </div>
        <div class="col-md-7">

          <!-- Post Create Box
            ================================================= -->
          <div class="create-post">
            <div class="row">
              <div class="col-md-7 col-sm-7">
                <form action="php functions/curd_data_man.php" method="post" enctype='multipart/form-data'>
                  <div class="form-group">
                    <img src="images/users/<?php echo $_SESSION['profile_pic'] ?>" alt="" class="profile-photo-md" />
                    <textarea name="post_text" required id="exampleTextarea" cols="30" rows="1" class="form-control" placeholder="Write what you wish"></textarea>
                    <input type='file' id="post_create" name="post_image" style="display:none;">
                  </div>
              </div>
              <div class="col-md-5 col-sm-5">
                <div class="tools">
                  <ul class="publishing-tools list-inline">
                    <!-- <li><a href="#"><i class="ion-compose"></i></a></li> -->
                    <li><i class="ion-images fa-2x" onclick="document.getElementById('post_create').click()"></i></li>

                    <!-- <li><label class="btn btn-info"for="">Select cover <input type="file" style="visibility:hidden;" name="" id=""></label></li> -->
                    <!-- <li><a href="#"><i class="ion-ios-videocam"></i></a></li> -->
                    <!-- <li><a href="#"><i class="ion-map"></i></a></li> -->
                  </ul>
                  <button type="submit" name="post_submit" class="btn btn-primary pull-right">Publish</button>
                  </form>
                </div>
              </div>
            </div>
          </div><!-- Post Create Box End -->

          <!-- Chat Room
            ================================================= -->
          <div class="chat-room">
            <div class="row">
              <div class="col-md-5">

                <!-- Contact List in Left-->
                <ul id=" " class=" user-list nav nav-tabs contact-list scrollbar-wrapper scrollbar-outer">




                </ul>
                <!--Contact List in Left End-->

              </div>
              <div class="col-md-7">


                <!--Chat Messages in Right-->
                <?php
                echo (isset($_GET['friend_id'])) ? '<input type="hidden" name="" value="' . $_GET['friend_id'] . '" id="friend_id">' : '   <input type="hidden" name="" value="" id="friend_id">';
                ?>
                <div class="tab-content scrollbar-wrapper wrapper scrollbar-outer">
                  <div class="tab-pane active scrolled" id="contact-1">
                    <div class="chat-body">
                      <?php
                      if (!isset($_GET['friend_id'])) {
                      ?>
                        <li class="my-auto">
                          <p class="text-muted text-center">Please Select A Friend For Chatting</p>
                        </li>
                      <?php
                      }
                      ?>
                    </div>
                  </div>

                </div>
                <!--Chat Messages in Right End-->
                <?php
                if (isset($_GET['friend_id'])) {
                ?>
                  <div class="send-message">
                    <div class="input-group emoji-picker-container">
                      <input type="text" class="form-control" id="text-message" data-emojiable="true" placeholder="Type your message">
                      <span class="input-group-btn">
                        <button class="btn btn-default" onclick="send_message()" type="button">Send</button>
                      </span>
                    </div>

                  

                  </div>
                <?php
                }
                ?>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>

        <!-- Newsfeed Common Side Bar Right
          ================================================= -->
        <div class="col-md-2 static">
          <?php include('layouts/side_bar_right.php') ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer
    ================================================= -->
  <?php include('layouts/footer.php') ?>

  <!--preloader-->
  <!-- <div id="spinner-wrapper">
      <div class="spinner"></div>
    </div>
     -->
  <!--Buy button-->

  <!-- Scripts
    ================================================= -->
  <?php include('layouts/js_links.php') ?>
  <!-- <script>
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

    function send_message() {
      var id = $('#friend_id').val();
      var msg = $('#text-message').val();
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
            $('#text-message').val('');
            scrollToBottom();
          }
        });
      }
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


    function scrollToBottom() {

      $('.tab-content').scrollTop($('.chat-body').height())
    }


    $(document).ready(function() {
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


      $(".chat-room").mouseenter(function() {
        if(!$(".chat-room").hasClass('scrolled')){
          $(".chat-room").addClass('scrolled');
        }
      });
      $(".chat-room").mouseleave(function () { 
        $(".chat-room").removeClass('scrolled');
      });
    });
  </script> -->
  

</body>

</html>