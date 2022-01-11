<?php
// include('db_connection.php');

function PushData($conn, $table, $columns, $values)
{
    $column_count = count($columns);
    $overwriteArr = array_fill(0, $column_count, '?');

    for ($i = 0; $i < sizeof($columns); $i++) {
        $columns[$i] = trim($columns[$i]);
        $columns[$i] = '`' . $columns[$i] . '`';
    }

    $query = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES (" . implode(',', $overwriteArr) . ")";

    foreach ($values as $value) {
        $value = trim($value);
        $value = mysqli_real_escape_string($conn, $value);
        $value = htmlentities($value, ENT_QUOTES, $encoding = 'UTF-8');
        $value = '"' . $value . '"';
        $query = preg_replace('/\?/', $value, $query, 1);
    }

      $result = mysqli_query($conn, $query);
      return $result;
    // return $query;
}




function PullData($con, $table, $columns, $condition, $oparator)
{

    if (is_array($columns)) {
        $columns = implode(',', $columns);
    }
    $sql = "SELECT {$columns} FROM {$table}";
    $where_cls = "";
    if (is_array($condition)) {
        $where_cls = "where ";
        $and = "";
        foreach ($condition as $key => $val) {
            $where_cls .= "" . $and . " " . $key . "=" ."'$val'". " ";
            $and = $oparator;
        }
    }
    $sql .= " " . $where_cls;
    $result = $con->query($sql);
    echo $con->error;
    return $result;
    // return $sql;
}





function update_table($con, $table, $colums, $values, $condition,$oparator)
{
    $query = "UPDATE $table SET ";
    $comma = " ";
    // $whitelist = array(
    //     'title',
    //     'rating',
    //     'season',
    //     'brand_id',
    //     'cateogry',
    //     // ...etc
    // );
    foreach ($values as $key => $val) {
        if (!empty($val) && in_array($key, $colums)) {
            $val = trim($val);
            $val = mysqli_real_escape_string($con, $val);
            $val = htmlentities($val, ENT_QUOTES, $encoding = 'UTF-8');
            $query .= $comma . $key . " = '" . $val . "'";
            $comma = ", ";
        }
    }
    $where_cls = "";
    if (is_array($condition)) {
        $where_cls = "where ";
        $and = "";
        foreach ($condition as $key => $val) {
            $where_cls .= "" . $and . " " . $key . "=" . "'$val'" . " ";
            $and = $oparator;
        }
    }
    $query .=" ". $where_cls;
    $result = $con->query($query);
}

function get_join_table_data($con,$table1,$table2,$col1,$col2)
{
$query = "SELECT $table1.*, $table2.* FROM $table1,$table2 where $table1.$col1=$table2.$col2";
$result =$con->query($query);
return $result;
// return $query;
}
function delete_cell($con, $table, $condition, $oparator)
{
    $sql = "DELETE FROM {$table}";
    $where_cls = "";
    if (is_array($condition)) {
        $where_cls = "where ";
        $and = "";
        foreach ($condition as $key => $val) {
            $where_cls .= "" . $and . " " . $key . "=" . "'$val'" . " ";
            $and = $oparator;
        }
    }
    $sql .= " " . $where_cls;
    $result = $con->query($sql);
    // return $con->error;
}


function num_of_rows($con, $table, $condition, $operator)
{
    $result = PullData($con, $table, "*", $condition, $operator);
    $n_rows=mysqli_num_rows($result);
    $con->error;
    return $n_rows;
}

function make_avatar($character,$des_location)
{
    $response = array(
        'error' => false,
        'msg' => 'Everything okk',
        'file_name' => ''
    );
    // echo $character;
    $randomNum = substr(str_shuffle("0123456789"), 0, 5);
    $file_name=$randomNum.".png";
    $path=$des_location.'/'.$file_name;
    $image=imagecreate(200,200);
    $red=rand(0,255);
    $blue=rand(0,255);
    $green=rand(0,255);
    imagecolorallocate($image,$red,$green,$blue);
    $text= imagecolorallocate($image,255,255,255);
    // putenv('GDFONTPATH=' . realpath('.'));
    $font='C:\xampp\htdocs\IMAGE_AVATAR\font\Raleway-Italic-VariableFont_wght.ttf';
    imagettftext($image,100,0,55,150,$text,$font,$character);
    header('Content-type:image/png');
    imagepng($image,$path);
    imagedestroy($image);
    $response['file_name']=$file_name;
    return $response;
}


function upload_file($file, $extention, $des_folder)
{

    $response = array(
        'error' => false,
        'msg' => 'Everything okk',
        'file_name' => ''
    );
    // echo var_dump($_FILES[$file]);
    $filename = $_FILES[$file]['name'];
    $randomNum = substr(str_shuffle("0123456789"), 0, 5);
    $source_extension = pathinfo($filename, PATHINFO_EXTENSION);
    $file_name = $randomNum . "." . $source_extension;
    $destination = $des_folder . '/' . $file_name;
    // echo $file_name."<br>";
    $response['file_name'] = $file_name;
    // echo  $response['file_name'];
    // the physical file on a temporary uploads directory on the server
    $source_file = $_FILES[$file]['tmp_name'];
    $sorce_file_size = $_FILES[$file]['size'];




    if (!in_array($source_extension, $extention)) {
        $response['error'] = true;
        $response['msg'] = "You file extension must be .zip, .pdf or .docx";
    } elseif ($_FILES[$file]['size'] > 3000000) { // file shouldn't be larger than 3Megabyte
        $response['error'] = true;
        $response['msg'] = "You file is too large";
    } else {
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($source_file, $destination)) {
            $response['file_name'] = $file_name;
        } else {
            $response['error'] = true;
            $response['msg'] = "Failed to upload file.";
        }
    }

    return $response;
}



function generate_table($con, $table, $columns, $condition, $oparator, $action)
{
     $result = PullData($con, $table, '*', $condition, $oparator);
?>

    <table id="table" class="table table-bordered">
        <thead class="thead-inverse">
            <tr>

                <?php
                foreach ($columns as $key=>$val) {
                ?>
                    <th> <?php echo $val ?></th>
                <?php
                   
                }
                ?>
            </tr>
        </thead>
        <tbody>
                <?php 
              while ($row = mysqli_fetch_array($result)) {
                $counter = 1;
                ?>
                <tr>
                <?php 
               foreach($columns as $key=>$val)
               {
                if($counter == count($columns)) continue; 
                  $counter++;
                ?>
                <td>
                <?php  echo $row[$val] ?>
                 
                </td>
                <?php
               }
                ?>
                
                </tr>
                <?php
               }
                ?>
        </tbody>
        <!-- <button type="button" onclick="" class="btn text-primary ml-2"></button> -->

      

    </table>


<?php
}




//return current date time
function getCurrentDateTime(){
    //date_default_timezone_set("Asia/Calcutta");
    return date("Y-m-d H:i:s");
}
function getDateString($date){
    $dateArray = date_parse_from_format('Y/m/d', $date);
    $monthName = DateTime::createFromFormat('!m', $dateArray['month'])->format('F');
    return $dateArray['day'] . " " . $monthName  . " " . $dateArray['year'];
}

function getDateTimeDifferenceString($datetime){
    $currentDateTime = new DateTime(getCurrentDateTime());
    $passedDateTime = new DateTime($datetime);
    $interval = $currentDateTime->diff($passedDateTime);
    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
    $day = $interval->format('%a');
    $hour = $interval->format('%h');
    $min = $interval->format('%i');
    $seconds = $interval->format('%s');

    if($day > 7)
        return getDateString($datetime);
    else if($day >= 1 && $day <= 7 ){
        if($day == 1) return $day . " day ago";
        return $day . " days ago";
    }else if($hour >= 1 && $hour <= 24){
        if($hour == 1) return $hour . " hour ago";
        return $hour . " hours ago";
    }else if($min >= 1 && $min <= 60){
        if($min == 1) return $min . " minute ago";
        return $min . " minutes ago";
    }else if($seconds >= 1 && $seconds <= 60){
        if($seconds == 1) return $seconds . " second ago";
        return $seconds . " seconds ago";
    }
}
function is_liked($con,$post_id,$user_id)
{
   $condition=array(
      'post_id'=>$post_id,
      'liked_by'=>$user_id
   );
   $n_rows=num_of_rows($con,'likes',$condition,'and');
   if($n_rows>0)
   {
       return true;
   }
   else{
       return false;
   }
}
function num_of_likes($con,$post_id)
{
    $condition=array(
        'post_id'=>$post_id,
     );
     $n_rows=num_of_rows($con,'likes',$condition,'');
     return $n_rows;
}
function num_of_comments($con,$post_id)
{
    $condition=array(
        'post_id'=>$post_id,
     );
     $n_rows=num_of_rows($con,'comments',$condition,'');
     return $n_rows;
}

// $condition=array(
//     'bk_id'=>4
//   );
// echo delete_cell($con,'book_catalog',$condition,'and');
// $columns=['bk','ck','dk'];
// $values=['dk','pk','rrrr'];
// echo PushData($con,'book_catagory',$columns,$values);
// $columns = array(
//     'title', 'rating', 'season'
// );
// $values = array(
//     'title' => 'hello',
//     'rating' => "var value = $('#' + data).val();",
//     'season' => 'fuck'
// );
//  $colums = array('boook_name'=>'bk_name','catagory'=>'bk_catagory','action'=>'Edit');
//  generate_table($con,'books_catalog',$colums,'','','');
// $condition = array(
//     'id' => '3','title' => 'hello'
// );
// echo num_of_rows($con, 'user',$condition,'or');
// echo update_table($con, 'cat', $columns, $values,'id', 'id');
