<?php
include('php_query_functions.php');
include('db_connection.php');
session_start();
if (isset($_POST['login'])) {
    $email = $_POST['Email'];
    $pass = $_POST['password'];
    $condition = array(
        'email' => $email,
        'password' => $pass
    );
    $result = PullData($con, 'users', '*', $condition, 'and');
    $n_rows = mysqli_num_rows($result);
    if ($n_rows > 0) {
        $row = mysqli_fetch_array($result);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['lname'] = $row['lname'];
        $condition = array(
            'user_id' => $row['user_id']
        );
        $image = PullData($con, 'user_profile_status', '*', $condition, '');
        $image = mysqli_fetch_array($image);
        $_SESSION['profile_pic'] = $image['defualt_profile_pic'];
        $_SESSION['cover_pic'] = $image['defualt_cover_pic'];
        $user_id=$row['user_id'];
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
        header("location:../newsfeed.php");
    } else {
        $_SESSION['error'] = true;
        $_SESSION['msg'] = 'Login failed: Invalid username or password.';
        header("location:../index-register.php");
    }
}
