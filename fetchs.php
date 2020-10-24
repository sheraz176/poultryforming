<?php  
  
 session_start();  
 if(!isset($_SESSION["username"]))  
 {  
      header("location:login.php?action=login");  
 }  
 ?>  

<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "study");
$columns = array('order_id', 'order_customer_name', 'order_item', 'order_value', 'order_date', 'wight', 'exp', 'sellary');

$query = "SELECT * FROM expensis WHERE ";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'order_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (order_id LIKE "%'.$_POST["search"]["value"].'%" 
  OR order_customer_name LIKE "%'.$_POST["search"]["value"].'%" 
  OR order_item LIKE "%'.$_POST["search"]["value"].'%" 
  OR order_value LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY order_id DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["order_id"];

 $sub_array[] = $row["order_customer_name"];
 $sub_array[] = $row["wight"];
 $sub_array[] = $row["order_item"];
 $sub_array[] = $row["order_value"];
 $sub_array[] = $row["order_date"];
 
  
 $sub_array[] = $row["sellary"];
  $sub_array[] = $row["exp"];
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM expensis";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>