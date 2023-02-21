 <?php
 $servername = 'first_php_db';
 $username = 'docker';
 $password = 'secret';
 $dbname = 'php_base';

 $table = 'test_table';
 try {
     //check connection
     $conn = new PDO(
         "mysql:host=$servername;dbname=$dbname",
         $username,
         $password
     );
     // set the PDO error mode to exception
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     //  echo '<br>Connected successfully';
     echo "<script>console.log('Connected successfully' );</script>";
     try {
         //check existing needed tables
         $result = $conn->query("SELECT 1 FROM {$table} LIMIT 1")->fetchAll();
         echo isset($result[0]) ? $result[0] : null;
         //  echo '<br>Table is present in db';
         echo "<script>console.log('Table is present in db' );</script>";
     } catch (Exception $e) {
         // We got an exception (table not found)
         //  echo '<br>No exist table';
         echo "<script>console.log('No exist table' );</script>";
     }
 } catch (PDOException $e) {
     echo '<br>Connection failed: ' . $e->getMessage();
 }


?>
