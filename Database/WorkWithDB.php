<?php
    namespace WordInSyllable\Database;

    use Exception;
    use PDO;
    use PDOException;

    /**
     *
     */
    class WorkWithDB
    {
        /* @var PDO */
        private $connection;

        /**
         * WorkWithDB constructor.
         * @throws Exception
         */
        function __construct()
        {
            $this->connect();
        }

        /**
         * @throws Exception
         */
        private function connect(): void
        {
            $servername = "localhost";
            $username = "root";
            $password = "labaslabas";

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
                throw new Exception($error);
            }
        }
        public function runQuery (SqlQueryBuilder $query): array
        {
            $sql = $this->connection->prepare($query);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * @param string $tableName
         * @param string $atributesName
         * @param array $where
         * @return array
         * @throws Exception
         */
        public function select(
            string $tableName,
            $atributesName = " * ",
            array $where = []
            ): array
        {
            try {
                $selectAtributes = (is_array($atributesName)) ? implode(", ", $atributesName) : $atributesName;
                $query = "SELECT {$selectAtributes} FROM {$tableName} ";


                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(" AND ", $where);
                }
                $sql = $this->connection->prepare($query);
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                return $result;

            } catch (Exception $e) {
                $error = "Select query fail: " . $e->getMessage() . "\n";
                throw new Exception($error);
            }
        }

        /**
         * @param array $tablesName
         * @param string $atributesName
         * @param array $onList
         * @param array $where
         * @return array
         * @throws Exception
         */
        public function selectInnerJoin(
            array $tablesName,
            $atributesName = " * ",
            array $onList = [],
            array $where = []
            ): array
        {
            try {
                $selectAtributes = (is_array($atributesName)) ? implode(", ", $atributesName) : $atributesName;
                $from = " $tablesName[0]";
                for ($i=1; $i < count($tablesName); $i++) {
                    $from .= " INNER JOIN $tablesName[$i] ON " . $onList[$i-1];
                }

                $query = "SELECT {$selectAtributes} FROM {$from} ";


                if (!empty($where)) {
                    $query = $query . " WHERE " . implode(" AND ", $where);
                }
                $sql = $this->connection->prepare($query);
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                return $result;

            } catch (Exception $e) {
                $error = "Select query fail: " . $e->getMessage() . "\n";
                throw new Exception($error);
            }
        }

        /**
         * @param string $tableName
         * @param array $atributeName
         * @param array $values
         * @param array $where
         * @throws Exception
         */
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
                    $query = $query . " WHERE " . implode(" AND ", $where);
                }

                $sql = $this->connection->prepare($query);
                for ($i=0; $i < count($atributeName); $i++) {
                    $sql->bindParam(
                        ":{$atributeName[$i]}",
                        $values[$i]);
                }
                $sql->execute();

            } catch (Exception $e) {
                $error = "Insert query fail: " . $e->getMessage() . "\n";
                throw new Exception($error);
            }
        }

        /**
         * @param string $tableName
         * @param array $atributeName
         * @param array $values
         * @param array $where
         * @throws Exception
         */
        public function update(
            string $tableName,
            array $atributeName,
            array $values,
            array $where = []
            ): void {
            try {
                $query = "UPDATE {$tableName} SET ";
                foreach ($atributeName as $key => $value) {
                    $query .=  " {$value}=:{$value}, ";
                    // if ($key != count($atributeName)) {
                    //     $query .=  ", ";
                    // }
                }
                $query = substr($query, 0, -2);
                if (!empty($where)) {
                    $query .= " WHERE " . implode(" AND ", $where);
                }
                $sql = $this->connection->prepare($query);
                for ($i=0; $i < count($atributeName); $i++) {
                    $sql->bindParam(
                        ":{$atributeName[$i]}",
                        $values[$i]);
                }
                $sql->execute();
            } catch (Exception $e) {
                $error = "Update query fail: " . $e->getMessage() . "\n";
                throw new Exception($error);
            }
        }

        /**
         * @param string $tableName
         * @param array $where
         * @throws Exception
         */
        public function delete(string $tableName, array $where = []): void
        {
            try {
                $query = "DELETE FROM {$tableName} ";
                if (!empty($where)) {
                    $query .= " WHERE " . implode(", ", $where);
                }

                $sql = $this->connection->prepare($query);
                $sql->execute();

            } catch (Exception $e) {
                $error = "Delete table '" . $tableName .
                "' data fail: " . $e->getMessage() . "\n";
                throw new Exception($error);
            }
        }

        public function beginTransaction()
        {
            $this->connection->beginTransaction();
        }

        public function endTransaction()
        {
            $this->connection->commit();
            // if ( $all == 'good' ) {
            //     DB::commit();
            // } else {
            //     DB::rollBack();
            // }
        }

        /**
         *insert if not exist and return array of $returnAtributeName of element
         * @param string $tableName
         * @param array $atributesName
         * @param array $values
         * @param string $returnAtributeName
         * @return array/null
         * @throws Exception
         */
        public function insertIfNotExist(
            string $tableName,
            array $atributesName,
            array $values,
            $returnAtributeName = "*"
        ) {
            $this->beginTransaction();

            $where = [];
            for ($i=0; $i < count($values); $i++) {
                $where[] = "{$atributesName[$i]} = '{$values[$i]}'";
            }
            $selectResult = $this->select(
                $tableName,
                $returnAtributeName,
                $where
            );

            if (empty($selectResult)) {
                $this->insert($tableName, $atributesName, $values);
                $selectResult = $this->select($tableName, $returnAtributeName, $where);

            }
            $this->endTransaction();

            return $selectResult;
        }


    }
