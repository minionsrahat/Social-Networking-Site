<?php
include('db_connection.php');
include('php_query_functions.php');
session_start();
$image_file_name="";
if(isset($_FILES['change_profile_image']))
{
    $extention = array('jpeg', 'jpg', 'png','JPEG','JPG','PNG');
    $response = upload_file('change_profile_image', $extention, '../images/users');
    if (!$response['error']) {
        $image_file_name = $response['file_name'];
        $columns=array('defualt_profile_pic');
        $value=array(
            'defualt_profile_pic'=>$image_file_name
        );
        $condition=array(
            'user_id'=>$_SESSION['user_id']
        );
     update_table($con,'user_profile_status',$columns,$value,$condition,'');
     $columns=array('album_id', 'user_id', 'image_location', 'date');
     $image_location="images/users".$image_file_name;
     $values=array(null,$_SESSION['user_id'],$image_location,''.date('Y-m-d H:i:s'));
     PushData($con,'users_album',$columns,$values);
     $_SESSION['profile_pic']=$image_file_name;
     echo $con->error;
     header('location:../timeline.php');
    }
    else{
        $_SESSION['error']=true;
         echo $response['msg'];
        $_SESSION['msg']="Sorry This Image File Type is not supported for security reasons ";
        header('location:../timeline.php');
    }

}
elseif ($_FILES['change_cover_image']) {
    $extention = array('jpeg', 'jpg', 'png','JPEG','JPG','PNG');
    $response = upload_file('change_cover_image', $extention, '../images/covers');
    if (!$response['error']) {
        $image_file_name = $response['file_name'];
        $columns=array('defualt_cover_pic');
        $value=array(
            'defualt_cover_pic'=>$image_file_name
        );
        $condition=array(
            'user_id'=>$_SESSION['user_id']
        );
     update_table($con,'user_profile_status',$columns,$value,$condition,'');
     $columns=array('album_id', 'user_id', 'image_location', 'date');
     $image_location="images/covers/".$image_file_name;
     $values=array(null,$_SESSION['user_id'],$image_location,''.date('Y-m-d H:i:s'));
     PushData($con,'users_album',$columns,$values);
     $_SESSION['cover_pic']=$image_file_name;
     echo $con->error;
     header('location:../timeline.php');
    }
    else{
        $_SESSION['error']=true;
         echo $response['msg'];
        $_SESSION['msg']="Sorry This Image File Type is not supported for security reasons ";
        header('location:../timeline.php');
    }
    # code...
}
?>