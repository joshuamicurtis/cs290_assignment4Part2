<?php
//ini_set('display_errors', 'On');

//Login information deleted for security purposes

$host = 
$user = 
$pw = 
$db = 
$table = 

$mysqli = new mysqli($host, $user, $pw, $db);

if($mysqli->connect_error) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}

function init($filterCatagory) {
  global $mysqli, $table;
  if($filterCatagory == 'allMovies') {
    $all = $mysqli->prepare("SELECT * FROM $table");
    $all->execute();
    $res = $all->get_result();
    buildTable($res);
    $all->close();
  }
  else {
    $all = $mysqli->prepare("SELECT * FROM $table  WHERE category = ?");
    $all->bind_param('s', $filterCatagory);
    $all->execute();
    $res = $all->get_result();
    buildTable($res);
    $all->close();
  }
}

function buildTable($res) {
  global  $tableBody;
  echo '<table><tr>
		<td>Name</td>
		<td class="category">Category</td>
		<td>Length</td>
		<td>Rented</td>
		</tr>';

  $tableBody = '';  
  while($row = $res->fetch_assoc())
  {
    echo '<tr id="'.$row['id'].'">';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['category'].'</td>';
    echo '<td>'.$row['length'].'</td>';
	if($row['rented'] == 0)
      echo '<td>Available</td><td class="checkout">Checkout</td>';
	if($row['rented'] == 1)
      echo '<td>Checked Out</td><td class="checkIn">Check In</td>';
    echo '<td class="remove">Remove</td></tr>';
  }
  echo '</table>';
}

function deleteAll() {
  global $mysqli, $table;
  $delete = $mysqli->prepare("DELETE FROM $table");
  $delete->execute();
  $delete->close();
}

function addVideo($name, $category, $length) {
  global $mysqli, $table;
  $add = $mysqli->prepare("INSERT INTO $table (name, category, length) VALUES (?,?,?)");
  $add->bind_param('sss', $name, $category, $length);
  $add->execute();
  $add->close();
}

function removeVideo($id) {
  global $mysqli, $table;
  $remove = $mysqli->prepare("DELETE from $table WHERE id = ?");
  $remove->bind_param('i', $id);
  $remove->execute();
  $remove->close();
}

function checkOutVideo($id) {
  global $mysqli, $table;
  $checkOutVideo = $mysqli->prepare("UPDATE $table SET rented = 1 WHERE id = ?");
  $checkOutVideo->bind_param('s', $id);
  $checkOutVideo->execute();
  $checkOutVideo->close();
}

function checkInVideo($id) {
  global $mysqli, $table;
  $checkOutVideo = $mysqli->prepare("UPDATE $table SET rented = 0 WHERE id = ?");
  $checkOutVideo->bind_param('s', $id);
  $checkOutVideo->execute();
  $checkOutVideo->close();
}

function filter($category) {
  global $mysqli, $table;
  if($category == 'allMovies') {
    $filter = $mysqli->prepare("SELECT * FROM $table");
    $filter->execute();
    $res = $filter->get_result();
    buildTable($res);
    $filter->close();
  }
  else {
    $filter = $mysqli->prepare("SELECT * FROM $table WHERE category = ?");
    $filter->bind_param('s', $category);
    $filter->execute();
    $res = $filter->get_result();
    buildTable($res);
    $filter->close();
  }
}

if(isset($_REQUEST['action'])) {
  $action = $_REQUEST['action'];
  $filterCatagory = $_REQUEST{'filterCatagory'};
  
  if($action == 'add') {
    $name = $_REQUEST['name'];
    $category = $_REQUEST['category'];
    $length = $_REQUEST['length'];
    addVideo($name, $category, $length);
	init($filterCatagory);
  } 
  elseif($action == 'remove') {
    $id = $_REQUEST['id'];
    removeVideo($id);
	init($filterCatagory);
  } 
  elseif($action == 'checkout') {
    $id = $_REQUEST['id'];
    checkOutVideo($id);
	init($filterCatagory);
  }
  elseif($action == 'checkIn') {
    $id = $_REQUEST['id'];
    checkInVideo($id);
	init($filterCatagory);
  }
   elseif($action == 'init') {
    init($filterCatagory);
  }
  elseif($action == 'deleteAll') {
    deleteAll();
  }
   elseif($action == 'filter') {
    $category = $_REQUEST['category'];
	filter($category);
  }    
}
?>
