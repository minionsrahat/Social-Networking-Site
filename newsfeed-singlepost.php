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


  <!--Favicon-->
  <!-- <link rel="shortcut icon" type="image/png" href="images/fav.png"/> -->
</head>

<body>

  <?php

  include('php functions/db_connection.php');
  include('php functions/php_query_functions.php');

  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
  } 
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

          <!-- Post Content
            ================================================= -->
          <?php
       
          $user_id = $_SESSION['user_id'];
          $sql = "select posts.*,fname,lname,users.user_id as user_id,user_profile_status.defualt_profile_pic as pic from users,user_profile_status,posts
          where posts.post_id='$post_id' and posts.post_by=users.user_id and user_profile_status.user_id=posts.post_by";
          $result = $con->query($sql);
          while ($row = mysqli_fetch_array($result)) {
            $page_link = ($row['user_id'] == $_SESSION['user_id']) ? 'timeline.php' : 'user-timeline.php?user_id=' . $row['user_id'];
            $onclick_function = (is_liked($con, $row['post_id'], $_SESSION['user_id'])) == True ? 'onclick="unlike_post(' . $row['post_id'] . ')"' : 'onclick="like_post(' . $row['post_id'] . ')"';
            $text_color = (is_liked($con, $row['post_id'], $_SESSION['user_id'])) == True ? 'text-primary' : 'text-green';

          ?>

            <div class="post-content">
              <?php
              if ($row['image'] != "") {
                echo '<img src="images/post-images/' . $row['image'] . '" alt="post-image" class="img-responsive img-fluid post-image" />';
              }
              ?>

              <div class="post-container">
                <img src="images/users/<?php echo $row['pic'] ?>" alt="user" class="profile-photo-md pull-left" />
                <div class="post-detail">
                  <div class="user-info">
                    <h5><a href="<?php echo $page_link ?>" class="profile-link"><?php echo $row['fname'] . " " . $row['lname'] ?> </a><span class="following">following</span></h5>
                    <p class="text-muted"> <small>Published a post about <?php echo "" . getDateTimeDifferenceString($row['published_at']) ?></small></p>
                  </div>
                  <div class="reaction">
                    <a class="btn <?php echo $text_color ?>" id="<?php echo $row['post_id'] ?>" <?php echo $onclick_function ?>><i class="icon fa-2x  ion-thumbsup"></i> <span><?php echo num_of_likes($con, $row['post_id']) ?></span></a>
                    <a class="btn text-primary" onclick="toggle('<?php echo $row['post_id'] ?>')"><i class="fa fa-2x  fa-comment" aria-hidden="true"></i> <?php echo num_of_comments($con, $row['post_id']) ?></a>
                  </div>
                  <div class="line-divider"></div>
                  <div class="post-text">
                    <p><?php echo $row['post_content'] ?> <i class="em em-anguished"></i> <i class="em em-anguished"></i> <i class="em em-anguished"></i></p>
                  </div>
                  <div class="line-divider"></div>
                  <div id="<?php echo $row['post_id'] . 'c' ?>" class="collapse">
                    <?php
                    $post_id = $row['post_id'];

                    $sql = "select comments.*,fname,user_profile_status.defualt_profile_pic as pic from users,user_profile_status,comments
                 where comments.post_id='$post_id' and  comments.user_id=user_profile_status.user_id and comments.user_id=users.user_id";
                    //  echo $sql."<br>";
                    $comments = $con->query($sql);
                    // $comment=PullData($con,'comments','*',$conditon,'');
                    while ($row2 = mysqli_fetch_array($comments)) {
                      $page_link = ($row2['user_id'] == $_SESSION['user_id']) ? 'timeline.php' : 'user-timeline.php?user_id=' . $row2['user_id']

                    ?>
                      <div class="post-comment">
                        <img src="images/users/<?php echo $row2['pic'] ?>" alt="" class="profile-photo-sm" />
                        <p><a href="<?php echo $page_link ?>" class="profile-link"><?php echo $row2['fname'] ?></a><i class="em em-laughing"></i><?php echo $row2['comment_text'] ?></p>

                      </div>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="post-comment">
                    <img src="images/users/<?php echo $_SESSION['profile_pic'] ?>" alt="" class="profile-photo-sm" />
                    <input type="text" id="<?php echo $post_id ?>" class="form-control inputComment" name="comment" placeholder="Post a comment">
                  </div>
                </div>
              </div>
            </div>

          <?php
          }
          ?>
   
       
               
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

</body>


</html>