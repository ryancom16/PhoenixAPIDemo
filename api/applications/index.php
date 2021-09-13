<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/applications.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate applications object
$applications = new applications($db);

// Applications query
if (isset($_GET['search'])) {
  $applications->search = $_GET['search'];
  $result = $applications->search();
} else {
  $result = $applications->read();
}

// Get Total
$num = $result->rowCount();

// Results array
$result_arr = array();

// Statistic Declarations
$countQualifying = 0;

// Process DB results
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
  extract($row);

  $post_item = array(
    'ID' => $flngID,
    'FirstName' => $fstrFirstName,
    'LastName' => $fstrLastName,
    'Email' => $fstrEmail,
    'CreditScore' => $fintCreditScore,
    'AnnualIncome' => $flngAnnualIncome,
    'MonthlyDebt' => $flngMonthlyDebt,
  );


  //Qualification Stats
  if (
    $flngAnnualIncome > 0 && $flngMonthlyDebt > 0 &&
    $flngMonthlyDebt / ($flngAnnualIncome / 12) > 0.5 &&
    $fintCreditScore != null && $fintCreditScore > 520
  ) {
    $post_item['Qualify'] = 1;
    $countQualifying++;
  } else {
    $post_item['Qualify'] = 0;
  }


  // Push to "data"
  array_push($result_arr, $post_item);
}

// Create response array
$response_arr = array();

// Total entries
$response_arr['count'] = $num;
// Total Qualifying
$response_arr['countQualifying'] = $countQualifying;



$response_arr['response'] = $result_arr;

// Turn to JSON & output
echo json_encode($response_arr);
