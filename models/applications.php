<?php
class applications
{
  // DB stuff
  private $conn;

  // Public Properties
  public $search;

  // Constructor with DB
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Get List
  public function read()
  {
    // Create query
    $query = 'SELECT c.flngID
                  ,c.fstrFirstName
                  ,c.fstrLastName
                  ,c.fstrEmail
                  ,d.fintCreditScore
                  ,a.flngAnnualIncome
                  ,a.flngMonthlyDebt
                FROM tblClients c
                JOIN tblApplications a ON a.flngClientID = c.flngID
                LEFT JOIN tblCredit d ON d.flngClientID = c.flngID
                ORDER BY c.flngID';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function search()
  {
    // Create query
    $query = 'SELECT c.flngID
                  ,c.fstrFirstName
                  ,c.fstrLastName
                  ,c.fstrEmail
                  ,d.fintCreditScore
                  ,a.flngAnnualIncome
                  ,a.flngMonthlyDebt
                FROM tblClients c
                JOIN tblApplications a ON a.flngClientID = c.flngID
                LEFT JOIN tblCredit d ON d.flngClientID = c.flngID
                WHERE c.fstrFirstName LIKE :search
                  OR c.fstrLastName LIKE :search
                  OR c.fstrEmail LIKE :search
                ORDER BY c.flngID';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Set Bind Params
    $bind = "%" . $this->search . "%";
    $stmt->bindParam(':search', $bind);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

}
