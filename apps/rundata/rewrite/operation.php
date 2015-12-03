<?php
Ko_Web_Rewrite::VLoadRules(array (
  'rest' => 
  array (
    '(.*)' => 
    array (
      '*' => '/rest.php?uri=$1',
    ),
  ),
));
