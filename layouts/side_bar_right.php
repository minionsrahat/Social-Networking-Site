<div class="suggestions" id="sticky-sidebar">
    <h4 class="grey">Who to Follow</h4>
    <?php
    $user_id = $_SESSION['user_id'];
   
    $sql = " SELECT fname,lname,users.user_id as user_id ,user_profile_status.defualt_profile_pic as pic FROM  `users`,`user_profile_status`
    where users.user_id!='$user_id' and users.user_id=user_profile_status.user_id and
    users.user_id NOT IN 
    (SELECT user_one_id FROM `relationships` WHERE (user_two_id = '$user_id')  AND ( `status` = 1 or `status` = 0)) 
    and users.user_id NOT IN 
    (SELECT user_two_id FROM `relationships` WHERE (user_one_id = '$user_id')  AND ( `status` = 1 or `status` = 0)) limit 10";


    $users = $con->query($sql);
    // echo $sql ."<br>";
    // $result = get_user_profile_info($con);
    echo $con->error;
    while ($row = mysqli_fetch_array($users)) {


    ?>
        <div class="follow-user">
            <img src="images/users/<?php echo $row['pic'] ?>" alt="" class="profile-photo-sm pull-left" />
            <div>
                <h5><a href="user-timeline.php?user_id=<?php echo $row['user_id'] ?>" style="font-size: 15px;"><?php echo $row['fname'] . " " . $row['lname']; ?></a></h5>
                <button onclick="sent_request('<?php echo $row['user_id'] ?>')" id="<?php echo $row['user_id'] ?>" class=" btn btn-primary">Add friend</button>
            </div>
        </div>
    <?php
    }
    ?>
</div>

