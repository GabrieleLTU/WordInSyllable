<?php
function CheckWord ($word, $syllable, $position)
{
  $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
  $syllableNoNumber = str_replace('.','',$syllableNoNumber);

//echo $word.": ".$syllable." -> ".$syllableNoNumber."\n";

  if ($syllable[0]==='.')//at the start of the word
  {
    //echo "At the start.";
    if (strpos ( $word, $syllableNoNumber, 0)===0)//$word[0]===$syllableNoNumber[0])
    {
      //change $position array
      $position = ChangePosition($syllable, 0, $position);
      //echo "\n syl.: ".$syllable." -> "; print_r($position);
      //echo "Change.";
    }
  }
  else if ($syllable[strlen ($syllable)-1]==='.') //at the end of the word
  {
    //echo "At the end.";
    $sylStart = strlen($word)-strlen($syllableNoNumber);
    $temp = strpos ($word, $syllableNoNumber, $sylStart);
    if ($temp===$sylStart)// strpos ($word, $syllableNoNumber, $sylStart)!=false
    {
      //change $position array
      $position = ChangePosition($syllable, $sylStart, $position);
      //echo "\n syl.: ".$syllable." -> ";// print_r($position);
      //echo "Change.";
    }
  }
  else //somewere in the word
  {
    //echo "Else.";
    $position = CheckAnywereInWord ($word, $syllable, $position, 0);

  }
  return $position;
}

function CheckAnywereInWord ($word, $syllable, $position, $searchStart)
{
  $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
  $syllableNoNumber = str_replace('.','',$syllableNoNumber);
  $sylStart = strpos ( $word, $syllableNoNumber, $searchStart);
  while ($sylStart!==FALSE)
  {
    //change $position array
    $position = ChangePosition($syllable, $sylStart, $position);
    //echo "\n syl.: ".$syllable." -> "; //print_r($position);
    $sylStart = strpos ( $word, $syllableNoNumber, $sylStart+strlen($syllableNoNumber));
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


function printWordBySillable ($word, $position)
{
  $splitWord = str_split($word,1);
  for ($i=0; $i < strlen($word); $i++)
  {
    echo $splitWord[$i];
    //echo $word[$i];

    if ($position[$i]%2>0)
    {
      echo "-";
    }
  }
}
?>
