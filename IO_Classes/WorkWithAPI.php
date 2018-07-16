<?php
    namespace WordInSyllable\IO_Classes;

    use WordInSyllable\Database\WorkWithDB;

    class WorkWithAPI
    {
        private $conn;

        public function __construct()
        {
            $this->conn = new WorkWithDB();
        }
        public function execute()
        {
            $method = $_SERVER['REQUEST_METHOD'];
            //$data = preg_split('/\\/', $_SERVER['REQUEST_URI']);
            switch($method) {
                case 'GET':
                    if(!empty($_GET["word_id"]))
                    {
                        $this->getWords(intval($_GET["word_id"]));
                    } else {
                        $this->getWords();
                    }
                    break;
                case 'POST':
                    $this->insertWord();
                    break;

                case 'PUT':
                    $w_id=intval($_GET["w_id"]);
                    //$id=$_GET["w_id"];
                    $this->updateWord($w_id);
                    break;

                case 'DELETE':
                    $tableName=$_GET["tableName"];
                    $where=(empty($_GET["where"])) ? null :$_GET["where"];
                    $this->delete($tableName, $where);
                    break;

                default:
                    header('HTTP/1.1 405 Method Not Allowed');
                    header('Allow: GET, PUT, DELETE');
                    break;
            }
        }

//        private function get(string $tableName, int $id = NULL): void
//        {
//            $result = null;
//
//            try {
//                if (is_null($id))
//                {
//                    $result = $this->conn->select($tableName);
//                } else {
//                    $result = $this->conn->select(
//                        $tableName,
//                        " * ",
//                        [$tableName[0] . "_id=$id"]
//                    );
//                }
//
//            } catch (\Exception $e) {
//            }
//
//            header('Content-Type: application/json');
//            echo json_encode($result);
//        }

        private function getWords(int $word_id = NULL): void
        {
            $result = null;
            try {
                if (is_null($word_id))
                {
                    $result = $this->conn->select("word");
                } else {
                    $result = $this->conn->select(
                        "word",
                        " * ",
                        ["w_id=$word_id"]
                    );
                }

            } catch (\Exception $e) {
            }

            header('Content-Type: application/json');
            echo json_encode($result);
        }

        private function insertWord(int $id): void
        {
            $word=$_POST["word"];
            $syllableWord=$_POST["syllableWord"];
            try {
                $this->conn->insert(
                    "word",
                    ["word", "syllableWord"],
                    [$word, $syllableWord]
                );

            } catch (\Exception $e) {
            }
        }

        private function delete(string $tableName, $where): void
        {
            try {
                if (is_null($where)) $this->conn->delete($tableName);
                else $this->conn->delete($tableName,[$where]);
            } catch (\Exception $e) {
            }
        }

        private function updateWord(int $w_id): void
        {
            try {
                parse_str(file_get_contents("php://input"),$post_vars);
                //$w_id=$post_vars["w_id"];
                $word=$post_vars["word"];
                $syllableWord=$post_vars["syllableWord"];
                $this->conn->update(
                    "word",
                    ["word", "syllableWord"],
                    [$word, $syllableWord],
                    ["w_id=$w_id"]
                );
            } catch (\Exception $e) {
            }
        }
    }