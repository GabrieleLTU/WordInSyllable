<?php

namespace WordInSyllable\Controllers;

use WordInSyllable\Models\SyllableProxy;


class SyllableController //implements ControllerInterface
{
    const SYLLABLE_IDENTIFIER = 2; //concrete syllable id or syllable (word);
    private $urlData = [];
    private $syllable = null;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->syllable = new SyllableProxy();
    }

    public function get(): array
    {
        if (!empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            if (is_numeric($this->urlData[self::SYLLABLE_IDENTIFIER])) {
                return $this->syllable
                    ->getSyllableData(
                        "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
                    );
            } else {
                return $this->syllable
                    ->getSyllableData(
                        "syllable='{$this->urlData[self::SYLLABLE_IDENTIFIER]}'"
                    );
            }
        } else {
            return $this->syllable->getAllSyllablesData();
        }
    }

    public function put(): void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);

        if (is_numeric($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllable->updateSyllable(
                $phpInput,
                "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
            );
        } else {
            $this->syllable->updateSyllable(
                $phpInput,
                "syllable='{$this->urlData[self::SYLLABLE_IDENTIFIER]}'"
            );
        }
    }

    public function post(): void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);
        $this->syllable->insertSyllable($phpInput);
    }

    public function delete(): void
    {
        if (array_key_exists(self::SYLLABLE_IDENTIFIER, $this->urlData) &&
            !empty($this->urlData[self::SYLLABLE_IDENTIFIER])
        ) {
            if (is_numeric($this->urlData[self::SYLLABLE_IDENTIFIER])) {
                $this->syllable->deleteSyllable(
                    "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
                );
            } else {
                $this->syllable->deleteSyllable(
                    "syllable='{$this->urlData[self::SYLLABLE_IDENTIFIER]}'"
                );
            }
        } else {
            $this->syllable->deleteAllSyllables();
        }
    }
}
