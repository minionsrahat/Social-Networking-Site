<?php 
include('db_connection.php');
include('php_query_functions.php');
session_start();
$user_id=$_SESSION["user_id"];
function wrap_tag($argument)
{
    return '<b>' . $argument . '</b>';
}
if(isset($_POST["search_value"]))
{
	$search_query = preg_replace('#[^a-z 0-9?!]#i', '', $_POST["search_value"]);
	$search_array = explode(" ", $search_query);

	$replace_array = array_map('wrap_tag', $search_array);
	$condition = '';

	foreach($search_array as $search)
	{
		if(trim($search) != '')
		{
			$condition .= "fname  LIKE '%".$search."%' OR lname LIKE '%".$search."%' OR ";
		}
	}
	$condition = substr($condition, 0, -4);
	$query = "SELECT fname,lname,users.user_id as user_id FROM users
    WHERE ".$condition." AND users.user_id!='$user_id'  limit 10";
	$result=$con->query($query);
	$output = '<div class="list-group">';

	if(mysqli_num_rows($result)>0)
	{
		while($row=mysqli_fetch_array($result))
		{
			$temp_text = $row["fname"]." ". $row["lname"];
			$temp_text = str_ireplace($search_array, $replace_array, $temp_text);
            $page_link=($row['user_id']==$_SESSION['user_id'])?'timeline.php':'user-timeline.php?user_id='.$row['user_id'];
			$output .= '<a href="'.$page_link.'" class="list-group-item">
				' . $temp_text . '
			</a>
			';
		}
	}
	else
	{
		$output .= '<a href="#" class="list-group-item">No Result Found</a>';
	}
	$output .= '</div>';
    echo $con->error;

	echo $output;
}

?>