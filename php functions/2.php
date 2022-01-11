<?php
include('db_connection.php');
include('php_query_functions.php');
session_start();
$user_id = $_SESSION['user_id'];
$friend_ids = $_SESSION['friend_ids'];
$html = "";
$response = array(
    'error' => true,
    'html' => ''
);

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'get_user_list') {
        $sql = "SELECT messages.* FROM messages, (SELECT MAX(msg_id) as lastid FROM messages WHERE (messages.msg_to = '$user_id' OR messages.msg_from = '$user_id')
        GROUP BY CONCAT(LEAST(messages.msg_to,messages.msg_from),'.',
        GREATEST(messages.msg_to, messages.msg_from))) as conversations
        WHERE msg_id = conversations.lastid
        ORDER BY messages.date DESC";
        $result = $con->query($sql);
        $msg_friend_ids = '(';
        $i = 1;
?>
        <ul id=" " class="nav nav-tabs contact-list scrollbar-wrapper scrollbar-outer">
            <?php
            while ($friends = mysqli_fetch_array($result)) {
                $msg = $friends['msg_content'];
                (strlen($msg) > 28) ? $msg =  substr($msg, 0, 28) . '...' : $msg = $msg;
                ($user_id == $friends['msg_from']) ? $you = "You: " : $you = "";
                $msg = $you . $msg;

                $last_activity = "";
                $name = "";
                $friend_id = "";
                $pic = "";
                if ($friends['msg_from'] != $user_id) {
                    $msg_friend_ids .= "" . $friends['msg_from'] . ",";
                    $friend_id = $friends['msg_from'];

                    $user_details = "select fname,lname,user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity from users,user_profile_status
            where user_profile_status.user_id='$friend_id' and users.user_id='$friend_id'";
                    $user_details = $con->query($user_details);
                    $user_details = mysqli_fetch_array($user_details);
                    $name = $user_details['fname'] . " " . $user_details['lname'];
                    $last_activity = $user_details['last_activity'];
                    $pic = $user_details['pro_pic'];
                } else {
                    $msg_friend_ids .= "" . $friends['msg_to'] . ",";
                    $friend_id = $friends['msg_to'];
                    $user_details = "select fname,lname,user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity from users,user_profile_status
                where user_profile_status.user_id='$friend_id' and users.user_id='$friend_id'";
                    $user_details = $con->query($user_details);
                    $user_details = mysqli_fetch_array($user_details);
                    $name = $user_details['fname'] . " " . $user_details['lname'];
                    $last_activity = $user_details['last_activity'];
                    $pic = $user_details['pro_pic'];
                }
            ?>
                <li class="">
                    <a href="newsfeed-messages.php?friend_id=<?php echo $friend_id ?>">
                        <div class="contact">
                            <img src="images/users/<?php echo $pic ?>" alt="" class="profile-photo-sm pull-left" />
                            <div class="msg-preview">
                                <h6><?php echo $name ?></h6>
                                <p class="text-muted"><?php echo $msg ?></p>
                                <small class="text-muted"><?php echo getDateTimeDifferenceString($last_activity) ?></small>
                                <!-- <div class="chat-alert"></div> -->
                            </div>
                        </div>
                    </a>
                </li>
            <?php
                $i = $i + 1;
            }
            
            if(strlen($msg_friend_ids)==1)
            {
                $msg_friend_ids="(0)" ;
            }
            else{
                $msg_friend_ids = substr($msg_friend_ids, 0, (strlen($msg_friend_ids) - 1)) . ')';
            }
            
            // echo $msg_friend_ids;
            $sql = "select fname,lname,users.user_id as user_id, user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity from users,user_profile_status
        where user_profile_status.user_id=users.user_id and users.user_id in $friend_ids and users.user_id not in $msg_friend_ids";
        // echo $sql.'<br>';
            $result = $con->query($sql);
            echo $con->error;
            while ($friends = mysqli_fetch_array($result)) {
                $last_activity = "" . $friends['last_activity'];
                $name = $friends['fname'] . " " . $friends['lname'];
                $friend_id = "" . $friends['user_id'];
                $pic = "" . $friends['pro_pic'];
            ?>
                <li class="">
                    <a href="newsfeed-messages.php?friend_id=<?php echo $friend_id ?>">
                        <div class="contact">
                            <img src="images/users/<?php echo $pic ?>" alt="" class="profile-photo-sm pull-left" />
                            <div class="msg-preview">
                                <h6><?php echo $name ?></h6>
                                <p class="text-muted"><?php echo "No message Available" ?></p>
                                <small class="text-muted"><?php echo getDateTimeDifferenceString($last_activity) ?></small>
                                <!-- <div class="chat-alert"></div> -->
                            </div>
                        </div>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
    <?php
    } elseif ($_POST['action'] == 'get_chat_messages') {
        $friend_id = $_POST['id'];
        $user_details = "select fname,lname,user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity from users,user_profile_status
        where user_profile_status.user_id='$friend_id' and users.user_id='$friend_id'";
        $user_details = $con->query($user_details);
        $user_details = mysqli_fetch_array($user_details);
        $name = $user_details['fname'] . " " . $user_details['lname'];
        $last_activity = $user_details['last_activity'];
        $pic = $user_details['pro_pic'];
        $friend_msg_sql = "select * from messages where (msg_from='$user_id' and msg_to='$friend_id') or (msg_to='$user_id' and msg_from='$friend_id')";
        $result = $con->query($friend_msg_sql);
    ?>
        <div class="chat-header" >
            <h5><?php echo $name ?></h5>
        </div>
       
        <ul class="chat-message">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($friend_msg = mysqli_fetch_array($result)) {
                    $msg_text = html_entity_decode($friend_msg['msg_content']);
                    if ($friend_msg['msg_from'] == $friend_id) {
            ?>
                        <li class="left">
                            <img src="images/users/<?php echo $pic ?>" alt="" class="profile-photo-sm pull-left" />
                            <div class="chat-item">
                                <div class="chat-item-header">
                                    <h5><?php echo $name ?></h5>
                                    <small class="text-muted"><?php echo getDateTimeDifferenceString($friend_msg['date']) ?></small>
                                </div>
                                <p><?php echo $msg_text ?></p>
                            </div>
                        </li>

                    <?php
                    } else {
                    ?>
                        <li class="right">
                            <img src="images/users/<?php echo $_SESSION['profile_pic'] ?>" alt="" class="profile-photo-sm pull-right" />
                            <div class="chat-item">
                                <div class="chat-item-header">
                                    <h5><?php echo $_SESSION['fname'] . " " . $_SESSION['lname'] ?></h5>
                                    <small class="text-muted"><?php echo getDateTimeDifferenceString($friend_msg['date']) ?></small>
                                </div>
                                <p><?php echo $msg_text ?></p>
                            </div>
                        </li>
                <?php
                    }
                }
            } else {
                ?>
                <li>
                    <p>No message Available</p>
                </li>
        <?php
            }
        ?>
  </ul>
        <?php
        }
        elseif ($_POST['action']=='send_message') {
            $friend_id=$_POST['id'];
            $msg = mysqli_real_escape_string($con, $_POST['msg']);
            $msg = htmlentities($msg, ENT_QUOTES, $encoding = 'UTF-8');
            $sql="INSERT INTO `messages`(`msg_id`, `msg_from`, `msg_to`, `msg_content`, `date`) VALUES (null,'$user_id','$friend_id','$msg',current_timestamp())";
            $result=$con->query($sql);
            
        }
    }
        ?>

    