<?php
class Database
{
    private $db_host = 'first_php_db'; // Change as required
    private $db_user = 'docker'; // Change as required
    private $db_pass = 'secret'; // Change as required
    private $db_name = 'php_base'; // Change as required

    private $flag_is_connect = false; // Check to see if the connection is active
    private $conn; //connection itself
    private $result = []; // Any results or id of a feched  query will be stored here
    private $myQuery = ''; // used for debugging process with SQL return
    private $numResults = 0; // used for returning the number of rows

    function __construct()
    {
        // echo "<script>console.log('This is constructor' );</script>";

        $table_users = 'users';
        $table_posts = 'posts';

        $create_users_table_query = "CREATE TABLE $table_users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(50) NOT NULL,
            passwd VARCHAR(30) NOT NULL,
            -- CONSTRAINT UC_Person UNIQUE (email,passwd)
            UNIQUE (email)
        );";

        $create_posts_table_query = "CREATE TABLE $table_posts (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50),
            content VARCHAR(250) NOT NULL,
            img BLOB
        );";

        try {
            //check connection
            $this->conn = new PDO(
                "mysql:host=$this->db_host;dbname=$this->db_name",
                $this->db_user,
                $this->db_pass
            );

            // set the PDO error mode to exception
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            //  echo '<br>Connected successfully';
            echo "<script>console.log('Connected successfully' );</script>";
            if (
                $this->tableExists($table_users) and
                $this->tableExists($table_posts)
            ) {
                echo "<script>console.log('All tables is present in db' );</script>";
            } else {
                if (!$this->tableExists($table_users)) {
                    echo "<script>console.log('No exist table users' );</script>";
                    echo "<script>console.log('Creating table users...' );</script>";
                    $this->conn->query($create_users_table_query)->fetchAll();
                }
                if (!$this->tableExists($table_posts)) {
                    echo "<script>console.log('No exist table posts' );</script>";
                    echo "<script>console.log('Creating table posts...' );</script>";
                    $this->conn->query($create_posts_table_query)->fetchAll();
                }
            }

            $this->flag_is_connect = true;
            return true; // Connection has been made return TRUE
        } catch (PDOException $e) {
            echo "<script>console.log('Connection failed' );</script>";
        }
    }

    // Function to disconnect from the database
    function __destruct()
    {
        $this->connection = null;
    }

    public function sql($sql)
    {
        $this->myQuery = $sql; // Pass back the SQL
        try {
            $sth = $this->conn->prepare($sql);
            // $sth->setFetchMode(PDO::FETCH_ASSOC);
            $sth->execute();
            $query_result = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->result = [];
            return false; // No rows where returned
        }
        $this->numResults = count($query_result);
        $this->result = $query_result;
        return true; // Query was successful
    }

    // Function to SELECT from the database
    public function select(
        $table,
        $rows = '*',
        $join = null,
        $where = null,
        $order = null,
        $limit = null
    ) {
        // Create query from the variables passed to the function
        $params = [];
        $sql = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($join != null) {
            $q .= ' JOIN ?';
            array_push($params, $join);
        }
        if ($where != null) {
            $q .= ' WHERE ?';
            array_push($params, $where);
        }
        if ($order != null) {
            $q .= ' ORDER BY ?';
            array_push($params, $order);
        }
        if ($limit != null) {
            $q .= ' LIMIT ?';
            array_push($params, $limit);
        }
        $this->myQuery = $sql; // Pass back the SQL
        // Check to see if the table exists
        if ($this->tableExists($table)) {
            // The table exists, run the query
            try {
                $sth = $this->conn->prepare($sql);
                $sth->execute($params);
                $query_result = $sth->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $this->result = [];
                $this->numResults = 0;
                return false; // No rows where returned
            }

            $this->numResults = count($query_result);
            $this->result = $query_result;
            return true; // Query was successful
        } else {
            $this->numResults = 0;
            return false; // Table does not exist
        }
    }

    // Function to insert into the database
    public function insert($table, $params = [])
    {
        // Check to see if the table exists
        if ($this->tableExists($table)) {
            $sql =
                "INSERT INTO $table (" .
                implode(', ', array_keys($params)) .
                ') VALUES (';
            $arr_q = [];
            for ($i = 0; $i < count($params); $i++) {
                array_push($arr_q, '?');
            }
            $sql .= implode(', ', $arr_q) . ')';
            $this->myQuery = $sql; // Pass back the SQL
            try {
                $sth = $this->conn->prepare($sql);
                $sth->execute(array_values($params));
                // $query_result = $sth->fetch(PDO::FETCH_ASSOC);
                $sth = $this->conn->prepare('SELECT LAST_INSERT_ID();');
                $sth->execute();
                // i don't now how did this better, write id of result
                $query_result = $sth->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $this->result = [];
                $this->numResults = 0;
                return false; // No rows where returned
            }
            $this->numResults = count($query_result);
            $this->result = $query_result;
            return true;
        } else {
            return false; // Table does not exist
        }
    }

    //Function to delete table or row(s) from database
    public function delete($table, $where = null)
    {
        // Check to see if table exists
        if ($this->tableExists($table)) {
            // The table exists check to see if we are deleting rows or table
            if ($where == null) {
                // $delete = 'DROP TABLE ' . $table; // Create query to delete table
            } else {
                $delete = 'DELETE FROM ' . $table . ' WHERE ' . $where . ';'; // Create query to delete rows
            }

            try {
                // i don't now how did this better, write id of result
                $sth = $this->conn->prepare(
                    'SELECT id FROM ' . $table . ' WHERE ' . $where . ';'
                );
                $sth->execute();
                $query_result = $sth->fetchAll(PDO::FETCH_ASSOC);
                $arr_result = [];
                for ($i = 0; $i < count($query_result); $i++) {
                    array_push($arr_result, $query_result[$i]['id']);
                }
                $sth = $this->conn->prepare($delete);
                $sth->execute();
                // $query_result = $sth->fetchAll(PDO::FETCH_ASSOC);
                $this->myQuery = $delete; // Pass back the SQL
            } catch (Exception $e) {
                $this->result = [];
                $this->numResults = 0;
                return false; // No rows where returned
            }
            $this->numResults = count($query_result);
            // $this->result = $query_result;
            $this->result = $arr_result;
            return true; // The query exectued correctly
        } else {
            return false; // The table does not exist
        }
    }

    // Function to update row in database
    public function update($table, $params = [], $where = '')
    {
        // Check to see if table exists
        if (
            $this->tableExists($table) and
            count($params) > 0 and
            strlen($where) > 0
        ) {
            // Create Array to hold all the columns to update
            $args = [];
            foreach ($params as $field => $value) {
                // Seperate each column out with it's corresponding value
                $args[] = $field . '=?';
            }
            // Create the query
            $sql =
                'UPDATE ' .
                $table .
                ' SET ' .
                implode(',', $args) .
                ' WHERE ' .
                $where;
            // Make query to database
            $this->myQuery = $sql; // Pass back the SQL

            try {

                // i don't now how did this better, write id of result
                $sth = $this->conn->prepare(
                    'SELECT id FROM ' . $table . ' WHERE ' . $where . ';'
                );
                $sth->execute();
                $query_result = $sth->fetchAll(PDO::FETCH_ASSOC);
                $arr_result = [];
                for ($i = 0; $i < count($query_result); $i++) {
                    array_push($arr_result, $query_result[$i]['id']);
                }
                $sth = $this->conn->prepare($sql);
                $sth->execute(array_values($params));
                $this->myQuery = $sql; // Pass back the SQL
            } catch (Exception $e) {
                $this->result = [];
                $this->numResults = 0;
                return false; // No rows where returned
            }
            $this->numResults = count($arr_result);
            $this->result = $arr_result;
            return true;
        } else {
            return false; // The table does not exist
        }
    }
    // Private function to check if table exists for use with queries
    private function tableExists($table)
    {
        $sql_query =
            'SHOW TABLES FROM ' . $this->db_name . ' LIKE "' . $table . '"';

        $tablesInDb = $this->conn->query($sql_query)->fetchAll();

        if ($tablesInDb) {
            if (count($tablesInDb) == 1) {
                return true; // The table exists
            } else {
                $this->result = [];
                return false; // The table does not exist
            }
        }
    }
    // Public function to return the data to the user
    public function getResult()
    {
        // $val = $this->result;
        // $this->result = [];
        // return $val;
        return $this->result;
    }

    //Pass the SQL back for debugging
    public function getSql()
    {
        // $val = $this->myQuery;
        // $this->myQuery = '';
        // return $val;
        return $this->myQuery;
    }

    //Pass the number of rows back
    public function numRows()
    {
        // $val = $this->numResults;
        // $this->numResults = 0;
        // return $val;
        return $this->numResults;
    }
}
?>
