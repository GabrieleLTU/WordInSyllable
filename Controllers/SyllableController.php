<?php
namespace WordInSyllable\Controllers;

use WordInSyllable\Models\SyllableProxy;


class SyllableController implements ControllerInterface
{
    private $urlData = [];
    private $syllable = null;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->syllable = new SyllableProxy();
    }

    public function get(): array
    {
        if (array_key_exists(2, $this->urlData) && !empty($this->urlData[2])) {
            if (is_numeric($this->urlData[2])) {
                return $this->syllable
                    -> getSyllableData("s_id={$this->urlData[2]}");
            } else {
                return $this->syllable
                    -> getSyllableData("syllable='{$this->urlData[2]}'");
            }
        } else {
            return $this->syllable-> getAllSyllablesData();
        }
    }

    public function put():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);

        if (is_numeric($this->urlData[2])) {
            $this->syllable-> updateSyllable(
                $phpInput,
                "s_id={$this->urlData[2]}"
            );
        } else {
            $this->syllable-> updateSyllable(
                $phpInput,
                "syllable='{$this->urlData[2]}'"
            );
        }
    }

    public function post():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);
        $this->syllable-> insertSyllable($phpInput);
    }

    public function delete():void
    {
        if (array_key_exists(2, $this->urlData) && !empty($this->urlData[2])) {
            if (is_numeric($this->urlData[2])) {
                $this->syllable-> deleteSyllable("s_id={$this->urlData[2]}");
            } else {
                $this->syllable-> deleteSyllable("syllable='{$this->urlData[2]}'");
            }
        } else {
            $this->syllable-> deleteAllSyllables();
        }
    }

}