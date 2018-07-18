<?php
namespace WordInSyllable\Controllers;


interface ControllerInterface
{
    public function __construct(array $urlData);
    public function get();
    public function put();
    public function post();
    public function delete();
}