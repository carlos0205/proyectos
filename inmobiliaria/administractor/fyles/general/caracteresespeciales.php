<?php
function caracteresespeciales($texto){
      $texto = htmlentities($texto, ENT_NOQUOTES, 'ISO8859-1'); // Convertir caracteres especiales a entidades
      //$texto = html_entity_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban
      return $texto;
  }


?>