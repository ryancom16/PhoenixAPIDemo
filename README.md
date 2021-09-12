# Phoenix Outcomes API Demo
 ## Purpose
 This is a simple RESTful API built with PHP to demo retrieval of customer information from a MySQL DB and output to JSON
 ## Specifications
 Enpoint Outputs:
  * /clients
    * Total number of clients
    * Number of clients with an application
    * Number of clients missing credit data
    * Average credit score
  * /applications
    * Total number of applications
    * Number of applications that qualify
      * Qualification is defined as:
        * Debt to income ratio LESS than 50%
          * Debt to income ratio is defined as (monthly debt / monthly income)
          * If income or debt is missing then the application does not qualify
        * Credit score above 520
          * If the credit score is missing for the client then the application does not qualify
## Testing
### POSTMAN

