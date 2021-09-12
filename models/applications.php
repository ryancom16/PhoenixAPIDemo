<?php 
  class applications {
    // DB stuff
    private $conn;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get List
    public function read() {
      // Create query
      $query = 'SELECT c.flngID
                  ,c.fstrFirstName
                  ,c.fstrLastName
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

    //Get Total # Applications
    public function total() {
      //Create query
      $query = 'SELECT count(1) total
                FROM tblApplications a
                ORDER BY
                c.flngID DESC';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      return $row['total'];
    }

    //Get Total # QualifyingApplications
    public function totalQualifying() {
      //Create query
      $query = 'SELECT COUNT(1) total FROM tblClients c 
                JOIN tblCredit d on d.flngClientID = c.flngID  
                WHERE d.fintCreditScore > 520 
                AND EXISTS (SELECT 1 FROM  tblApplications a 
                            WHERE a.flngClientID = c.flngID 
                            AND (a.flngMonthlyDebt / (a.flngAnnualIncome / 12) > 0.5))';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      return $row['total'];
    }

  }