<?php
include('db_connection.php');
include('php_query_functions.php');
session_start();
$user_id = $_SESSION['user_id'];
$friend_ids = $_SESSION['friend_ids'];
$friends_sql = "SELECT users.user_id as user_id ,fname,lname,user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity
FROM `users`,`user_profile_status` 
where users.user_id!='$user_id' and users.user_id=user_profile_status.user_id and (users.user_id IN $friend_ids)";
$result = $con->query($friends_sql);
while ($friends = mysqli_fetch_array($result)) {
    $friend_id = $friends['user_id'];
    echo $friends['fname'] . "<br>";
    $msg = "";
    $friend_msg_sql = "select * from messages where (msg_from='$user_id' and msg_to='$friend_id') or (msg_to='$user_id' and msg_from='$friend_id') ORDER BY msg_id DESC LIMIT 1";
    $result2 = $con->query($friend_msg_sql);
    
    if (mysqli_num_rows($result2) > 0) {
        $result2 = mysqli_fetch_array($result2);
        $msg = "" . $result2['msg_content'];
        (strlen($msg) > 28) ? $msg =  substr($msg, 0, 28) . '...' : $msg = $msg;
        ($user_id == $result2['msg_from']) ? $you = "You: " : $you = "";
        $msg=$you.$msg;
    } else {
        $msg = "No message available";
    }
?>
    <li class="active">
        <a href="#contact-1" onclick="show_message_tab('<?php echo $friends['user_id'] ?>')" data-toggle="tab">
            <div class="contact">
                <!-- <img src="images/users/user-2.jpg" alt="" class="profile-photo-sm pull-left" /> -->
                <div class="msg-preview">
                    <h6><?php echo $friends['fname'] . " " . $friends['lname'] ?></h6>
                    <p class="text-muted"><?php echo $msg ?>'</p>
                    <small class="text-muted"><?php getDateTimeDifferenceString($friends['last_activity']) ?></small>
                    <!-- <div class="chat-alert"></div> -->
                </div>
            </div>
        </a>
    </li>
<?php
    // echo $msg . "<br>";
}

?>