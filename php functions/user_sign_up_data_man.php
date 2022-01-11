<?php 
include('php_query_functions.php');
include('db_connection.php');
session_start();
if(isset($_POST['register']))
{
$fname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$Email=$_POST['Email'];
$password=$_POST['password'];
$day=$_POST['day'];
$month=$_POST['month'];
$year=$_POST['year'];
$gender=$_POST['optradio'];
$city=$_POST['city'];
$country=$_POST['country'];
$bdate = date('Y-m-d',strtotime($month."-".$day."-".$year));
echo $bdate;

$conditon=array(
    'email'=>$Email
);
$n=num_of_rows($con,'users',$conditon,'');
if($n>0)
{
 $_SESSION['error']=true;
 $_SESSION['msg']='There is already a minionsmate account associated with entered email. Please enter another email.Thank you';
 header("location:../index-register.php");
}
else{
    $columns=array(
        'user_id', 'fname', 'lname', 'bdate', 'gender', 'city', 'counry', 'email', 'password', 'created at'
    );
    
    $currentdate=date('Y-m-d');
    $values=array(
    null,$fname,$lastname,''.$bdate,$gender,$city,$country,$Email,$password,date('Y-m-d')
    );
    PushData($con,'users',$columns,$values);
  
    $user_id= mysqli_insert_id($con);
    
    $columns=array(
        'user_id', 'active_status', 'defualt_profile_pic', 'defualt_cover_pic'
    );
    $response1=make_avatar(mb_substr($fname, 0, 1),'../images/users');
    $response2=make_avatar(mb_substr($fname, 0, 1),'../images/covers');
    $values=array(
        $user_id,'1',$response1['file_name'],$response2['file_name']
    );
    PushData($con,'user_profile_status',$columns,$values);
    if($con->error)
    {
        $_SESSION['error']=true;
        $_SESSION['msg']=''.$con->error; 
        header("location:../index-register.php");
    }
    else{
        $_SESSION['user_id']=$user_id;
        $_SESSION['fname']=$fname;
        $_SESSION['lname']=$lastname;
        $_SESSION['profile_pic']=$response1['file_name'];
        $_SESSION['cover_pic']=$response2['file_name'];
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
       
    }
}


// $sql="INSERT INTO `users`(`user_id`, `fname`, `lname`, `bdate`, `gender`, `city`, `counry`, `email`, `password`, `created at`) VALUES (
//     null,'$fname','$lastname','$bdate','$gender','$city','$country','$Email','$password','$currentdate')";

// $result= $con->query($sql);


}

// $result=PullData($con,'users','*','','');
// while($row=mysqli_fetch_array($result))
// {
//     echo $row['fname'];
//     $columns=array(
//         'user_id', 'active_status', 'defualt_profile_pic', 'defualt_cover_pic'
//     );
//     $response=make_avatar(mb_substr($row['fname'], 0, 1),'images/users');
//     $values=array(
//     $row['user_id'],'1',$response['file_name'],$response['file_name']
//     );
//     PushData($con,'user_profile_status',$columns,$values);
//     echo $con->error;
// }


?>