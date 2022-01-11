<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="This is social network html5 template available in themeforest......" />
  <meta name="keywords" content="Social Network, Social Media, Make Friends, Newsfeed, Profile Page" />
  <meta name="robots" content="index, follow" />
  <title>News Feed | Check what your friends are doing</title>

  <!-- Stylesheets
    ================================================= -->
  <?php 
  session_start();
  include('layouts/css_links.php');
  if(!isset($_SESSION['user_id']))
  {
    header("location:index-register.php");
  }
  ?>



  <!--Favicon-->
  <!-- <link rel="shortcut icon" type="image/png" href="images/fav.png"/> -->
  <style>
  .cover{
    width: 100% !important;
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
          <!--chat block ends-->
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
          </div><!-- Post Create Box End-->




          <div class="friend-list">
            <div class="row">
              <?php
              $user_id = $_SESSION['user_id'];
              $sql = " SELECT fname,lname,users.user_id as user_id ,user_profile_status.defualt_profile_pic as pro_pic,user_profile_status.defualt_cover_pic as cover_pic FROM  `users`,`user_profile_status`
              where users.user_id!='$user_id' and users.user_id=user_profile_status.user_id and
              users.user_id NOT IN 
              (SELECT user_one_id FROM `relationships` WHERE (user_two_id = '$user_id')  AND ( `status` = 1 or `status` = 0)) 
              and users.user_id NOT IN 
              (SELECT user_two_id FROM `relationships` WHERE (user_one_id = '$user_id')  AND ( `status` = 1 or `status` = 0))";


              $users = $con->query($sql);
              // echo $sql ."<br>";
              // $result = get_user_profile_info($con);
              echo $con->error;
              while ($row = mysqli_fetch_array($users)) {


              ?>
                <div class="col-md-6 col-sm-6">
                  <div class="friend-card">
                    <img src="images/covers/<?php echo $row['cover_pic']?>" alt="profile-cover" class="img-responsive cover" />
                    <div class="card-info">
                      <img src="images/users/<?php echo $row['pro_pic']?>" alt="user" class="profile-photo-lg" />
                      <div class="friend-info">
                        <h5><a href="user-timeline.php?user_id=<?php echo $row['user_id']?>" class="profile-link"><?php echo $row['fname']." ".$row['lname']?></a></h5>
                        <button onclick="sent_request('<?php echo $row['user_id'] ?>')" id="<?php echo $row['user_id'] ?>" class=" btn btn-primary">Add friend</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php

              }
              ?>
          
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
  <div id="spinner-wrapper">
    <div class="spinner"></div>
  </div>

  <!--Buy button-->
  <!-- <a href="https://themeforest.net/cart/add_items?item_ids=18711273&ref=thunder-team" target="_blank" class="btn btn-buy"><span class="italy">Buy with:</span><img src="images/envato_logo.png" alt="" /><span class="price">Only $20!</span></a> -->

  <!-- Scripts
    ================================================= -->
  <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTMXfmDn0VlqWIyoOxK8997L-amWbUPiQ&callback=initMap"></script> -->
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
    });
  </script> -->
</body>


</html>