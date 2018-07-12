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
            var_dump($this->select("syllable"));//, , [""]));
            // string $tableName,
            // $atributresName = " * ",
            // array $where = []
        }

        private function connect(): void
        {
            $servername = "localhost";
            $username = "root";
            $password = "";

            try {
            $conn = new PDO("mysql:host=$servername;dbname=syllablewords",
                $username,
                $password);
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

        public function delete(string $tableName, array $where = []): void
        {
            try {
                $query = "DELETE FROM {$tableName} ";
                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(", ", $where);
                }

                $sql = $this->connection->prepare($query);
                $sql->execute();

            } catch (\Exception $e) {
                $error = "Delete table '" . $tableName .
                "' data fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        public function insert(
            string $tableName,
            array $atributeName,
            array $values,
            array $where = []
        ): void {
            try {
                $query = "INSERT INTO $tableName ";
                $query .= "( " . implode(", ", $atributeName) . ") ";
                $query .= "VALUES (:" .  implode(", :", $atributeName) . ") ";
                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(", ", $where);
                }

                $sql = $this->connection->prepare($query);
                for ($i=0; $i < count($atributeName); $i++) {
                    $sql->bindParam(
                        ":" . $atributeName[$i] . "",
                        $values[$i]);
                }
                $sql->execute();

            } catch (\Exception $e) {
                $error = "Insert query fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }

        }

        public function update(
            string $tableName,
            array $atributeName,
            array $values,
            array $where = []
            ): void {
            try {
                $query = "UPDATE " . $tableName . " SET ";
                for ($i=0; $i < count($atributeName); $i++) {
                    $query = $query . $atributeName[$i] . "= :" . $atributeName[$i] . ", ";
                }
                $query = substr($query, 0, -2);

                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(", ", $where);
                }

                $sql = $this->connection->prepare($query);
                for ($i=0; $i < count($atributeName); $i++) {
                    $sql->bindParam(
                        ":" . $atributeName[$i] . "",
                        $values[$i]);
                }
                $sql->execute();
            } catch (\Exception $e) {
                $error = "Update query fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        public function select(
            string $tableName,
            $atributresName = " * ",
            array $where = []
            ): array
        {
            try {
                $retVal = (is_array($atributresName)) ? implode(", ", $atributesName) : $atributresName ;
                $query = "SELECT " . $retVal . " FROM " . $tableName;


                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(", ", $where);
                }
                $sql = $this->connection->prepare($query);
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_COLUMN, ["s_id"]);
                return $result;

            } catch (\Exception $e) {
                $error = "Select query fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        protected function beginTransaction()
        {
            $connection->beginTransaction();
        }

        protected function endTransaction()
        {
            $connection->commit();
            // if ( $all == 'good' ) {
            //     DB::commit();
            // } else {
            //     DB::rollBack();
            // }
        }
        /**
        *@return array/null
        */
        public function insertIfNotExist(
            string $tableName,
            array $atributesName,
            array $values,
            array $returnAtributeName = null
        ) {
            $this->beginTransaction();
            // $result = $this->select($tableName,
            //     array $atributresName,
            //     array $where = []
            //     )



            $this->endTransaction();
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
        /**
        *@return string/null
        */
        public function findWordWithSyllable()
        {

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
                $error = "Select query fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }
        }

        //---WORD, SYLLABLE, SYLLABLEBYWORD TABLES---//
        public function insertSyllableByWord(string $word, string $syllable)
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


    }
