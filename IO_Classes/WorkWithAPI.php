<?php
    namespace WordInSyllable\IO_Classes;

    use WordInSyllable\Database\WorkWithDB;

    class WorkWithAPI
    {
        private $conn;

        public function __construct()
        {
            $this->conn = new WorkWithDB();
            $method = $_SERVER['REQUEST_METHOD'];
            switch($method) {
                case 'POST':
                    $this->insertWord();
                    break;

                case 'DELETE':
                    //$this->delete_contact($name);
                    break;

                case 'GET':
                    if(!empty($_GET["word_id"]))
                    {
                        $this->getWords(intval($_GET["word_id"]));
                    } else {
                        $this->getWords();
                    }
                    break;
                    break;

                default:
                    header('HTTP/1.1 405 Method Not Allowed');
                    header('Allow: GET, PUT, DELETE');
                    break;
            }
        }

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

        private function insertWord(): void
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
    }