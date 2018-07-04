<?php
function CheckWord ($word, $syllable, $position)
{
  $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
  $syllableNoNumber = str_replace('.','',$syllableNoNumber);

//echo $word.": ".$syllable." -> ".$syllableNoNumber."\n";

  if ($syllable[0]==='.')//at the start of the word
  {
    if (stripos ( $word, $syllableNoNumber, 0)===0)//$word[0]===$syllableNoNumber[0])
    //if(substr_compare($word, $syllableNoNumber, 0, strlen($syllableNoNumber)))
    {
      //change $position array
      $position = ChangePosition($syllable, 0, $position);
      //echo "\n syl.: ".$syllable." -> "; print_r($position);
    }
  }
  else if ($syllable[strlen ($syllable)-1]==='.') //at the end of the word
  {
    $sylStart = strlen($word)-strlen($syllableNoNumber);
    $temp = stripos ($word, $syllableNoNumber, $sylStart);
    if ($temp===$sylStart)// stripos ($word, $syllableNoNumber, $sylStart)!=false
    {
      //change $position array
      $position = ChangePosition($syllable, $sylStart, $position);
      //echo "\n syl.: ".$syllable." -> ";// print_r($position);
      //echo "Change.";
    }
  }
  else //somewere in the word
  {
    $position = CheckAnywereInWord ($word, $syllable, $position, 0);

  }
  return $position;
}

function CheckAnywereInWord ($word, $syllable, $position, $searchStart)
{
  $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
  $syllableNoNumber = str_replace('.','',$syllableNoNumber);
  $sylStart = stripos ( $word, $syllableNoNumber, $searchStart);
  while ($sylStart!==FALSE)
  {
    //change $position array
    $position = ChangePosition($syllable, $sylStart, $position);
    //echo "\n syl.: ".$syllable." -> "; //print_r($position);
    $sylStart = stripos ( $word, $syllableNoNumber, $sylStart+strlen($syllableNoNumber));
  }
  return $position;
}

function ChangePosition($syllable, $sylStart, $position)
{
  $syllable = str_replace('.','',$syllable);
  if (($sylStart-1)<0) {
    $letterNumber=0;
  }
  else {
    $letterNumber=$sylStart-1;
  }
  for ($i = (($sylStart-1)<0) ? 1 : 0 ; $i < strlen($syllable); $i++) {
    if(is_numeric($syllable[$i]))
    {
      if ($position[$letterNumber]<$syllable[$i]) {
        $position[$letterNumber]=$syllable[$i];
      }
    }
    else {
      $letterNumber++;
    }
  }
 return $position;
}
?>
