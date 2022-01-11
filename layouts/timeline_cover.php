<div class="timeline-cover">
    <!--Timeline Menu for Large Screens-->
    <div class="timeline-nav-bar hidden-sm hidden-xs">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-info">
                    <form action="php functions/manage_picture.php" method="post" id="profile" enctype='multipart/form-data'>
                        <input type='file' id="change_profile_image" name="change_profile_image" onchange="this.form.submit();" style="display:none;">
                        <img src="images/users/<?php echo $_SESSION['profile_pic'] ?>" alt="" class="img-responsive profile-photo" />
                    </form>
                    <button class="btn btn-primary" onclick="document.getElementById('change_profile_image').click()" name="change_profile_picture">Change</button>

                    <h3><?php echo $_SESSION['fname'] . " " . $_SESSION['lname'] ?></h3>
                    <!-- <p class="text-muted">Creative Director</p> -->
                </div>
            </div>
            <div class="col-md-9">
                <ul class="list-inline profile-menu">
                    <li><a href="newsfeed-following.php" class="active">Following</a></li>
                    <li><a href="timeline-about.html">About</a></li>
                    <li><a href="newsfeed-followers.php">Followers</a></li>
                    <li><a href="newsfeed-friends.php">Friends</a></li>
                </ul>
                <ul class="follow-me list-inline">
                    <li><?php echo followers($con,$_SESSION['user_id'])  ?>  Peoples Following You</li>

                    <li><button class="btn-primary" onclick="document.getElementById('change_cover_image').click()">Change Cover Pic</button></li>
                </ul>
            </div>
        </div>
    </div>
    <!--Timeline Menu for Large Screens End-->
    <form action="php functions/manage_picture.php" method="post" id="profile" enctype='multipart/form-data'>
        <input type='file' id="change_cover_image" name="change_cover_image" onchange="this.form.submit();" style="display:none;">
    </form>

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
            <button class="btn-primary">Add Friend</button>
        </div>
    </div>
    <!--Timeline Menu for Small Screens End-->

</div>