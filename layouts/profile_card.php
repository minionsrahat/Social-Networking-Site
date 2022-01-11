<div class="profile-card">
    <?php
    $user_id=$_SESSION['user_id'];
    ?>
            <img src="images/users/<?php echo $_SESSION['profile_pic']?>" alt="user" class="profile-photo" />
            <h5><a href="timeline.php" class="text-white"><?php echo $_SESSION['fname']." ".$_SESSION['lname'] ?></a></h5>
            <a href="#" class="text-white"><i class="ion ion-android-person-add"></i>   <?php echo followers($con,$_SESSION['user_id'])  ?>  Followers</a>
          </div>
          <!--profile card ends-->
          <ul class="nav-news-feed">
            <li><i class="icon ion-ios-paper"></i>
              <div><a href="newsfeed.php">My Newsfeed</a></div>
            </li>
            <li><i class="icon ion-ios-people"></i>
              <div><a href="newsfeed-people-nearby.php">People Nearby</a></div>
            </li>
            <li><i class="icon ion-ios-people-outline"></i>
              <div><a href="newsfeed-friends.php">Friends</a></div>
            </li>
            <li><i class="icon ion-person-add"></i>
              <div><a href="newsfeed-followers.php">Friend-Requests</a></div>
            </li>
            <li><i class="icon ion-chatboxes"></i>
              <div><a href="newsfeed-messages.php">Messages</a></div>
            </li>
            <li><i class="icon ion-log-out"></i>
              <div><a href="php functions/logout.php">Logout</a></div>
            </li>
          </ul>
          <!--news-feed links ends-->
          <div id="chat-block">
            <div class="title">Chat Online</div>
            <ul class="online-users list-inline">
             
            </ul>
          </div>