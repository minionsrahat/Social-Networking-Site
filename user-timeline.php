<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is social network html5 template available in themeforest......" />
    <meta name="keywords" content="Social Network, Social Media, Make Friends, Newsfeed, Profile Page" />
    <meta name="robots" content="index, follow" />
    <title>My Timeline | This is My Coolest Profile</title>

    <!-- Stylesheets
    ================================================= -->
    <?php
    include('php functions/db_connection.php');
    include('php functions/php_query_functions.php');
    ?>
    <?php
    session_start();

    include('layouts/css_links.php');
    if (!isset($_SESSION['user_id'])) {
        header("location:index-register.php");
    }
    if (isset($_GET['user_id'])) {
        $friend_id = $_GET['user_id'];
        $user_details = "select fname,lname,user_profile_status.defualt_profile_pic as pro_pic,user_profile_status.defualt_cover_pic as cover_pic ,user_profile_status.last_activity as last_activity from users,user_profile_status
        where user_profile_status.user_id='$friend_id' and users.user_id='$friend_id'";
        $user_details = $con->query($user_details);
        $user_details = mysqli_fetch_array($user_details);
        $name = $user_details['fname'] . " " . $user_details['lname'];
        $last_activity = $user_details['last_activity'];
        $pro_pic = $user_details['pro_pic'];
        $cover_pic = $user_details['cover_pic'];
    }
    ?>



    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">

    <!--Favicon-->
    <link rel="shortcut icon" type="image/png" href="images/fav.png" />

    <style>
        .timeline-cover {
            background: url("images/covers/<?php echo $cover_pic ?>") no-repeat;
            background-position: center;
            background-size: cover;
            min-height: 400px;
            border-radius: 0 0 4px 4px;
            position: relative;
        }
    </style>
</head>

<body>


    <!-- Header
    ================================================= -->
    <?php include('layouts/navbar.php') ?>

    <!--Header End-->


    <div class="container">

        <!-- Timeline
      ================================================= -->
        <div class="timeline">

            <div class="timeline-cover">
                <!--Timeline Menu for Large Screens-->
                <div class="timeline-nav-bar hidden-sm hidden-xs">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="profile-info">

                                <img src="images/users/<?php echo $pro_pic ?>" alt="" class="img-responsive profile-photo" />


                                <h3><?php echo $name ?></h3>
                                <p class="text-muted">Creative Director</p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <ul class="list-inline profile-menu">
                                <li><a href="timline.html" class="active">Timeline</a></li>
                                <li><a href="timeline-about.html">About</a></li>
                                <li><a href="timeline-album.html">Album</a></li>
                                <li><a href="timeline-friends.html">Friends</a></li>
                            </ul>
                            <ul class="follow-me list-inline">
                                <li> <?php echo followers($con, $friend_id)  ?> Peoples Following this User</li>

                                <li><?php echo friend_status($con,$_SESSION['user_id'],$friend_id)?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--Timeline Menu for Large Screens End-->

                <!--Timeline Menu for Small Screens-->
                <div class="navbar-mobile hidden-lg hidden-md">
                    <div class="profile-info">
                        <img src="images/users/user-1.jpg" alt="" class="img-responsive profile-photo" />
                        <h4>Sarah Cruiz</h4>
                        <p class="text-muted">Creative Director</p>
                    </div>
                    <div class="mobile-menu">
                        <ul class="list-inline">
                            <li><a href="timline.html" class="active">Timeline</a></li>
                            <li><a href="timeline-about.html">About</a></li>
                            <li><a href="timeline-album.html">Album</a></li>
                            <li><a href="timeline-friends.html">Friends</a></li>
                        </ul>
                        <?php echo friend_status($con,$_SESSION['user_id'],$friend_id)?>
                    </div>
                </div>
                <!--Timeline Menu for Small Screens End-->

            </div>
            <div id="page-contents" style="margin-top: 100px;">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-7 ">

                        <!-- Post Create Box
              ================================================= -->
                        <!-- Post Create Box End-->

                        <!-- Post Content
              ================================================= -->
                        <?php
                        $condition = array(
                            'post_by' => $friend_id
                        );
                        $result = PullData($con, 'posts', '*', $condition, '');
                        $nrows = mysqli_num_rows($result);
                        if ($nrows > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $onclick_function = (is_liked($con, $row['post_id'], $_SESSION['user_id'])) == True ? 'onclick="unlike_post(' . $row['post_id'] . ')"' : 'onclick="like_post(' . $row['post_id'] . ')"';
                                $text_color = (is_liked($con, $row['post_id'], $_SESSION['user_id'])) == True ? 'text-primary' : 'text-green';
                        ?>
                                <div class="post-content">

                                    <!--Post Date-->
                                    <div class="post-date hidden-xs hidden-sm">
                                        <h5><?php echo $name ?></h5>
                                        <p class="text-grey"><?php echo "" . getDateTimeDifferenceString($row['published_at']) ?></p>
                                    </div>
                                    <!--Post Date End-->

                                    <?php
                                    if ($row['image'] != "") {
                                        echo '<img src="images/post-images/' . $row['image'] . '" alt="post-image" class="img-responsive img-fluid post-image" />';
                                    }
                                    ?>
                                    <div class="post-container">
                                        <img src="images/users/<?php echo $pro_pic ?>" alt="user" class="profile-photo-md pull-left" />
                                        <div class="post-detail">
                                            <div class="user-info">
                                                <h5><a href="user-timeline.php?user_id=<?php echo $friend_id ?>" class="profile-link"><?php echo $name ?></a></h5>
                                                <p class="text-muted">Published a post about <?php echo "" . getDateTimeDifferenceString($row['published_at']) ?></p>
                                            </div>
                                            <div class="reaction">
                                                <a class="btn <?php echo $text_color ?>" id="<?php echo $row['post_id'] ?>" <?php echo $onclick_function ?>><i class="icon fa-2x  ion-thumbsup"></i> <span><?php echo num_of_likes($con, $row['post_id']) ?></span></a>
                                                <a class="btn text-primary" onclick="toggle('<?php echo $row['post_id'] ?>')"><i class="fa fa-2x  fa-comment" aria-hidden="true"></i> <?php echo num_of_comments($con, $row['post_id']) ?></a>
                                            </div>
                                            <div class="line-divider"></div>
                                            <div class="post-text">
                                                <p><?php echo $row['post_content'] ?><i class="em em-anguished"></i> <i class="em em-anguished"></i> <i class="em em-anguished"></i></p>
                                            </div>
                                            <div class="line-divider"></div>
                                            <div id="<?php echo $row['post_id'] . 'c' ?>" class="collapse">
                                                <?php
                                                $post_id = $row['post_id'];

                                                $sql = "select comments.*,users.user_id as user_id,fname,user_profile_status.defualt_profile_pic as pic from users,user_profile_status,comments
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
                        } else {
                            ?>
                            <div class="post-content">
                                <div class="post-container">
                                    <div class="post-detail">

                                        <h5>Sorry!! This User Havent Any Available Post.</h5>

                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>



                    </div>
                    <div class="col-md-2 static">
                        <?php include('layouts/side_bar_right.php') ?>
                    </div>
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
                    // console.log(response)
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