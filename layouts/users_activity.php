<?php
$user_id=$_SESSION['user_id'];
$sql="select fname,users.user_id as user_id,msg ,notifications.date as date from users, notifications
where notifications.from_user_id='$user_id' and notifications.to_user_id=users.user_id order by notifications.date desc";
$result=$con->query($sql);
?>
<div id="sticky-sidebar">
<h4 class="grey"><?php echo $_SESSION['fname'] ?>'s activity</h4>
<a href="user_timeline?user_id="></a>
<?php
if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_array($result))
    {
        $msg="";
    if($row['user_id']==$user_id)
    {
        $msg="".($row['msg']=='Likes')?' likes his Post':' Comment on his Post';

    }
    else{
        $msg="".($row['msg']=='Likes')?' likes <a href="user-timeline.php?user_id='.$row['user_id'].'">'.$row['fname'].'s </a> Post':'Comment on <a href="user-timeline.php?user_id='.$row['user_id'].'">'.$row['fname'].'s </a>  Post';
    }
?>

    <div class="feed-item">
        <div class="live-activity">
            <p><a href="timeline.php" class="profile-link"><?php echo $_SESSION['fname'] ?>
            </a><?php echo $msg;?> </p>
            <p class="text-muted"><?php echo getDateTimeDifferenceString( $row['date']) ?></p>
        </div>
    </div>
   
<?php
    }
}
else{
    ?>
 <div class="feed-item">
        <div class="live-activity">
            <p><a href="timeline.php" class="profile-link"><?php echo $_SESSION['fname'] ?>
            </a> Has No Recent Activity </p>
        </div>
    </div>
    <?php
}
?>
</div>

