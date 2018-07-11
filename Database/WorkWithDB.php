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
            //$this->insertSyllable("as");
            //$this->insertSyllable("as");
            //$this->selectSyllables();
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

        //---WORD TABLE---//
        public function insertWord(string $word, string $syllableWord = NULL)
        {
            try {
                $sql = $this->connection->prepare('INSERT INTO word (word, syllableWord) VALUES (?, ?)');
                $sql->bindParam(1, $word, PDO::PARAM_STR, 255);
                $sql->bindParam(2, $syllableWord, PDO::PARAM_STR, 255);
                $sql->execute();
                //echo "New record created successfully";
            } catch (\Exception $e) {
                $error = "Insert word in database fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        public function updateWordByWord(string $byWord, string $syllableWord = NULL)
        {
            try {
                $sql = $this->connection->prepare('UPDATE word SET syllableword = ? WHERE word = ?');
                $sql->bindParam(2, $word, PDO::PARAM_STR, 255);
                $sql->bindParam(1, $syllableWord, PDO::PARAM_STR, 255);
                $sql->execute();
                //echo "New record updated successfully";
            } catch (\Exception $e) {
                $error = "Update word in database fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        //---SYLLABLE TABLE---//
        public function insertSyllable(string $syllable)
        {
            try {
                $sql = $this->connection->prepare('INSERT INTO syllable (syllable) VALUES (?)');
                $sql->bindParam(1, $syllable, PDO::PARAM_STR, 255);
                $sql->execute();
                //echo "New record created successfully";
            } catch (\Exception $e) {
                $error = "Insert syllable in database fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        public function selectSyllables(): array
        {
            try {
                $sth = $this->connection->prepare("SELECT syllable FROM syllable");
                $sth->execute();
                $result = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
                return $result;

            } catch (\Exception $e) {
                $error = "Insert syllable in database fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }
    }
