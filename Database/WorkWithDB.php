<?php
    namespace WordInSyllable\Database;

    use PDO;
    /**
     *
     */
    class WorkWithDB
    {
        private $connection;

        function __construct()
        {
            $this->connect();
            //$this->inserctWord("labas", null);
        }

        public function connect()
        {
            $servername = "localhost";
            $username = "root";
            $password = "";

            try {
            $conn = new PDO("mysql:host=$servername;dbname=syllablewords", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $conn;
            //echo "Connected successfully";
            }
            catch(PDOException $e)
            {
                $error = "Connection failed: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        public function inserctWord(string $word, string $syllableWord = NULL)
        {
            $sql = "INSERT INTO word (word, syllableWord) VALUES (?, ?)";
            $sql->execute([$word, $syllableWord]);
            // use exec() because no results are returned
            $this->connection->exec($sql);
            echo "New record created successfully";
        }
    }
