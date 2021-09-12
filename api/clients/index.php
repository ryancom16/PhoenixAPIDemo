<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/clients.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate clients object
$clients = new clients($db);


// Clients query
if (isset($_GET['search'])) {
  $clients->search = $_GET['search'];
  $result = $clients->search();
} else {
  $result = $clients->read();
}

// Get Total
$num = $result->rowCount();

// Results array
$result_arr = array();

// Statistic Declarations
$countAppExists = 0;
$totalCreditAmt = 0;
$totalMissingCredit = 0;

// Process DB results
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
  extract($row);

  $post_item = array(
    'ID' => $flngID,
    'FirstName' => $fstrFirstName,
    'LastName' => $fstrLastName,
    'Email' => $fstrEmail,
    'CreditScore' => $fintCreditScore,
    'AppExists' => $fblnAppExists,
  );

  //Application Stats
  if ($fblnAppExists) {
    $countAppExists++;
  }

  //Credit Stats
  if ($fintCreditScore != null) {
    $totalCreditAmt += $fintCreditScore;
  } else {
    $totalMissingCredit++;
  }

  // Push to "data"
  array_push($result_arr, $post_item);
}

// Create response array
$response_arr = array();

// Total entries
$response_arr['count'] = $num;
// Calculate Application Statistics
$response_arr['countHasApplication'] = $countAppExists;
// Calculate Credit Statistics
$response_arr['countMissingCredit'] = $totalMissingCredit;
if ($num - $totalMissingCredit > 0) {
  $response_arr['averageCredit'] = round($totalCreditAmt / ($num - $totalMissingCredit));
} else {
  $response_arr['averageCredit'] = 0;
}

$response_arr['response'] = $result_arr;

// Turn to JSON & output
echo json_encode($response_arr);
