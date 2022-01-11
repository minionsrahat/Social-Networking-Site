<?php 
include('db_connection.php');
include('php_query_functions.php');
session_start();

if(isset($_POST['post_submit']))
{
 $post_content=$_POST['post_text'];
 $image_file_name="";
 echo var_dump($_FILES['post_image']);
 if (isset($_FILES['post_image'])) {
    //  echo 'hi';
    $extention = array('jpeg', 'jpg', 'png','JPEG','JPG','PNG');
    $response = upload_file('post_image', $extention, '../images/post-images');
    if (!$response['error']) {
        $image_file_name = $response['file_name'];
        $columns=array('album_id','user_id','image_location','date');
        $image_location="images/post-images/".$image_file_name;
        $values=array(null,$_SESSION['user_id'],$image_location,''.date('Y-m-d H:i:s'));
        PushData($con,'users_album',$columns,$values);
    }
}
 $columns=array(
    'post_id', 'post_by', 'post_content', 'image', 'privacy', 'published_at'
 );
 $values=array(
     null,$_SESSION['user_id'],$post_content,$image_file_name,'2',''. date('Y-m-d H:i:s')
 );
 PushData($con,'posts',$columns,$values);
 header("location:../newsfeed.php");
}


else if (isset($_POST['action'])) {
    if($_POST['action']=='comment')
    {
    $comment=$_POST['text'];
    $post_id=$_POST['post_id'];
    $user_id=$_SESSION['user_id'];
    $columns=array(
        'comment_id', 'user_id', 'post_id', 'comment_text', 'comment_at'
    );
    $values=array(
            null,$_SESSION['user_id'],$post_id,$comment,''. date('Y-m-d H:i:s')
    );
    PushData($con,'comments',$columns,$values);
    $condition=array(
        'post_id'=>$post_id,
    );
    $result=PullData($con,'posts','post_by',$condition,"");
    $result=mysqli_fetch_array($result);
    $post_by=$result['post_by']; 
    $sql="INSERT INTO `notifications`(`not_id`, `from_user_id`, `msg`, `post_id`, `to_user_id`, `date`) VALUES (null,'$user_id','Comment','$post_id','$post_by',current_timestamp())";
    $con->query($sql);
    echo $con->error;
    }
    elseif ($_POST['action']=='friend_request_sent') {
        $user_id_two=$_POST['user_id_two'];
        $columns=array(
            'r_id', 'user_one_id', 'user_two_id', 'status', 'action_id', 'date'
        );
        $values=array(
                null,$_SESSION['user_id'],$user_id_two,'0',$_SESSION['user_id'],''. date('Y-m-d H:i:s')
        );
        PushData($con,'relationships',$columns,$values);
        echo $con->error;
    }
    elseif ($_POST['action']=='cancel_friend_request') {
        $user_id_two=$_POST['user_id_two'];
        $condition=array(
            'user_one_id'=>$_SESSION['user_id'],
            'user_two_id'=>$user_id_two,
            'status'=>'0'
        );
        delete_cell($con,'relationships',$condition,'and');
        echo $con->error;
    }
    elseif ($_POST['action']=='accept_friend_request') {
        $user_id_two=$_POST['user_id_two'];
        $condition=array(
            'user_one_id'=>$user_id_two,
            'user_two_id'=>$_SESSION['user_id'],
            'status'=>'0'
        );
        $columns=array('status');
        $values=array(
            'status'=>1
        );
        update_table($con,'relationships',$columns,$values,$condition,'and');
        $sql = " SELECT users.user_id as user_id  FROM  `users` where users.user_id!='$user_id' and  (users.user_id IN 
              (SELECT user_one_id FROM `relationships` WHERE (user_two_id = '$user_id' AND `status` = 1)) 
              OR users.user_id  IN 
              (SELECT user_two_id FROM `relationships` WHERE (user_one_id = '$user_id' AND `status` = 1)))";
        $users = $con->query($sql);
        $friend_ids = '(';
        if(mysqli_num_rows($users)>0)
        {
            while ($row = mysqli_fetch_array($users)) {
                echo $row['user_id'] . '<br>';
                $friend_ids .= "" . $row['user_id'] . ",";
            }
            $friend_ids = substr($friend_ids, 0, (strlen($friend_ids) - 1)) . ')';
        }
        else{
            $friend_ids='(0)';
        }
        
       
        $_SESSION['friend_ids']=$friend_ids;
        echo $con->error;
    }
    elseif ($_POST['action']=='like_post') {
      $post_id=$_POST['post_id'];
      $user_id=$_SESSION['user_id'];
      $condition=array(
          'post_id'=>$post_id
      );
      $result=PullData($con,'posts','post_by',$condition,"");
      $result=mysqli_fetch_array($result);
      $post_by=$result['post_by'];
      $sql="INSERT INTO `likes`(`like_id`, `post_id`, `liked_by`, `date`) VALUES (null,'$post_id','$user_id',current_timestamp())";
      $con->query($sql);
      $sql="INSERT INTO `notifications`(`not_id`, `from_user_id`, `msg`, `post_id`, `to_user_id`, `date`) VALUES (null,'$user_id','Likes','$post_id','$post_by',current_timestamp())";
      $con->query($sql);
      echo $post_by;
      echo $con->error;
    }
    elseif ($_POST['action']=='unlike_post') {
        $post_id=$_POST['post_id'];
        $user_id=$_SESSION['user_id'];
        $condition=array(
            'post_id'=>$post_id,
        );
        $result=PullData($con,'posts','post_by',$condition,"");
        $result=mysqli_fetch_array($result);
        $post_by=$result['post_by'];
        $condition=array(
            'post_id'=>$post_id,
            'liked_by'=>$user_id
        );
        delete_cell($con,'likes',$condition,'and');
        $condition=array(
            'from_user_id'=>$user_id,
            'post_id'=>$post_id,
            'to_user_id'=>$post_by
        );
        delete_cell($con,'notifications',$condition,'and');
        echo $post_by;
        echo $con->error;
      }
}

?>