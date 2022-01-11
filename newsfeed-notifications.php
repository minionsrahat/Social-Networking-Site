<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is social network html5 template available in themeforest......" />
    <meta name="keywords" content="Social Network, Social Media, Make Friends, Newsfeed, Profile Page" />
    <meta name="robots" content="index, follow" />
    <title>News Feed | Check what your friends are doing</title>

    <?php
    session_start();
    include('layouts/css_links.php');
    if (!isset($_SESSION['user_id'])) {
        header("location:index-register.php");
    }
    ?>

    <style>
        .notification-box-outer {
            background-color: #e6e6fa;
        }

        .notification-box {
            padding: 30px
        }

        .notification-box p {
            font-size: 18px;
            color: black;
        }

        .notification {
            padding: 10px;
            border-bottom: 2px solid #008080;
        }
    </style>
    <!--Favicon-->
    <!-- <link rel="shortcut icon" type="image/png" href="images/fav.png"/> -->
</head>

<body>

    <?php

    include('php functions/db_connection.php');
    include('php functions/php_query_functions.php');

    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    $no_of_records_per_page = 5;
    $offset = ($pageno - 1) * $no_of_records_per_page;
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

                    <div class="notification-box-outer rounded shadow">
                        <div class="notification-box ">
                            <?php
                            $user_id = $_SESSION['user_id'];
                            $total_pages_sql = "select COUNT(*) FROM notifications where to_user_id='$user_id' and from_user_id!='$user_id'";
                            $result = mysqli_query($con, $total_pages_sql);
                            $total_rows = mysqli_fetch_array($result)[0];
                            $total_pages = ceil($total_rows / $no_of_records_per_page);
                            echo $con->error;

                            if ($total_rows > 0) {
                                $sql = "select fname,users.user_id as user_id,msg ,notifications.date as date ,post_id from users, notifications
                                        where notifications.to_user_id='$user_id' and notifications.from_user_id!='$user_id' and  notifications.from_user_id=users.user_id order by notifications.date desc LIMIT $offset, $no_of_records_per_page";
                                $result = $con->query($sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    $msg = "" . ($row['msg'] == 'Likes') ? '<a href="user-timeline.php?user_id=' . $row['user_id'] . '">' . $row['fname'] . ' </a> Likes Your <a href="newsfeed-singlepost.php?post_id='.$row['post_id'].'">Post</a>' : '<a href="user-timeline.php?user_id=' . $row['user_id'] . '">' . $row['fname'] . ' </a> Comment On Your     <a href="newsfeed-singlepost.php?post_id='.$row['post_id'].'">Post</a>';

                            ?>

                                    <div class="notification ">
                                        <p class=""><?php echo $msg ?></p>
                                        <span><?php echo getDateTimeDifferenceString($row['date']) ?></span>
                                    </div>

                                <?php
                                }
                                ?>
                                  <?php
                            } 
                            else {
                ?>
                    
                
                <?php
                            }
                ?>
                        </div>
                    </div>
                 

                    <!-- Notification Content
            ================================================= -->




                    <ul class="pagination">
                        <li><a href="?pageno=1">First</a></li>
                        <li class="<?php if ($pageno <= 1) {
                                        echo 'disabled';
                                    } ?>">
                            <a href="<?php if ($pageno <= 1) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno - 1);
                                        } ?>">Prev</a>
                        </li>
                        <li class="<?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                    } ?>">
                            <a href="<?php if ($pageno >= $total_pages) {
                                            echo '#';
                                        } else {
                                            echo "?pageno=" . ($pageno + 1);
                                        } ?>">Next</a>
                        </li>
                        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                    </ul>
              
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

    function like_post(p_id) {
      post_id = p_id;
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          post_id: post_id,
          action: 'like_post'
        },
        success: function(response) {
          $('#' + post_id).attr('onclick', 'unlike_post(' + post_id + ')')
          $('#' + post_id).removeClass('text-green');
          $('#' + post_id).addClass('text-primary');
          val = parseInt($('#' + post_id + " span").text()) + 1;
          $('#' + post_id + " span").text("" + val)

        }
      });

    }

    function unlike_post(p_id) {
      post_id = p_id;
      $.ajax({
        type: "post",
        url: "php functions/curd_data_man.php",
        data: {
          post_id: post_id,
          action: 'unlike_post'
        },
        success: function(response) {
          console.log(response)
          $('#' + post_id).attr('onclick', 'like_post(' + post_id + ')')
          $('#' + post_id).removeClass('text-green text-primary');
          $('#' + post_id).addClass('text-green');
          val = parseInt($('#' + post_id + " span").text()) - 1;
          $('#' + post_id + " span").text("" + val)

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
    // Update friends list every 3 seconds.



    $(document).ready(function() {


      $(document).on('keyup', '#search_friend', function() {
        var search_value = $('#search_friend').val();
        if (search_value.trim() != '') {
          console.log(search_value)
          $.ajax({
            type: "post",
            url: "php functions/search_action.php",
            data: {
              search_value: search_value,
              action: 'filter_results'
            },

            success: function(response) {
              console.log(response)
              $('.searchlist').html(response);
            }
          });
        } else {
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
      setInterval(updateOnlineFriends, 5000);
    });
  </script> -->
</body>


</html>