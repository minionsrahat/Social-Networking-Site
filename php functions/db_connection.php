<?php
$con = mysqli_connect('localhost', 'root', '', 'minionsmate');

function get_user_profile_info($con)
{
    $table1='users';
    $table2='user_profile_status';
    $query = "SELECT fname,lname,$table2.* FROM $table1,$table2 where $table1.user_id= $table2.user_id";
    $result =$con->query($query);
    return $result;
}

function followers($con,$user_id)
{
    $sql="SELECT * FROM  `relationships` where (user_two_id='$user_id' and (`status` = 1 or `status` = 0)) or (user_one_id = '$user_id' AND `status` = 1)";
    $result=$con->query($sql);
    return mysqli_num_rows($result);
}
function friend_status($con,$user_one_id,$user_two_id)
{
    $sql="SELECT `status`, action_id from relationships where (user_one_id='$user_one_id' and user_two_id='$user_two_id') or (user_one_id='$user_two_id' and user_two_id='$user_one_id')";
    $result=$con->query($sql);
    $num_rows=mysqli_num_rows($result);
    if($num_rows>0)
    {
        $result=mysqli_fetch_array($result);
        if($result['status']==0 && $result['action_id']==$user_one_id)
        {
           return '<button onclick="cancel_sent_request('.$user_two_id.')" id="'.$user_two_id.'" class=" btn btn-primary text-center">Cancel Friend Request</button>';
        }
        elseif($result['status']==0 && $result['action_id']==$user_two_id)
        {
           return '<button onclick="accept_sent_request('.$user_two_id.')" id="'.$user_two_id.'" class=" btn btn-primary text-center">Accept Friend Request</button>';
        }
        else{
            return '<a href="#" class="pull-right text-green">My Friend</a>';
        }
    }
    else{
           return '<button onclick="sent_request('.$user_two_id.')" id="'.$user_two_id.'" class=" btn btn-primary">Add friend</button>';
    }
}


?>


