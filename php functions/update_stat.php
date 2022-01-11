<?php
include('db_connection.php');
include('php_query_functions.php');
session_start();
$user_id = $_SESSION['user_id'];
$friend_ids=$_SESSION['friend_ids'];
$sql = "SELECT users.user_id as user_id ,fname,lname,user_profile_status.defualt_profile_pic as pro_pic ,user_profile_status.last_activity as last_activity ,TIME_TO_SEC(TIMEDIFF(NOW(), user_profile_status.last_activity)) as dif
FROM `users`,`user_profile_status` 
where users.user_id!='$user_id' and users.user_id=user_profile_status.user_id and (users.user_id IN $friend_ids) and 
user_profile_status.active_status='1' and  TIME_TO_SEC(TIMEDIFF(NOW(), user_profile_status.last_activity)) <= 180";
$users = $con->query($sql);
updateLastCheckin($con,$user_id);
echo $con->error;
$nrows=mysqli_num_rows($users);

?>
<div class="title">Chat online</div>
<ul class="online-users list-inline">
<?php
if($nrows<1)
{
?>
<li><p><span class="badge badge-primary">No Active Friends</span>
</p></li>
<?php
}
while ($row = mysqli_fetch_array($users)) 
{
    // echo strtotime($row['now']) - strtotime($row['last_activity']).'<br>';
    // echo $row['last_activity'].'<br>';
    // echo $row['dif'].'<br>';
    // echo $row['now'].'<br>';

?>

<li><a href="newsfeed-messages.php?friend_id=<?php echo $row['user_id'] ?>" title="<?php echo $row['fname']." ".$row['lname'] ?>"><img src="images/users/<?php echo $row['pro_pic'] ?>" alt="user" class="img-responsive profile-photo" /><span class="online-dot"></span></a></li>
<?php
}
?>
</ul>

<?php
function updateLastCheckin($con, $user_id)
{
    $query ="UPDATE `user_profile_status` SET `last_activity` = NOW() WHERE `user_id` = '$user_id'";
    $result=$con->query($query);
}

?>