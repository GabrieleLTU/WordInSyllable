<?php

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
