<?php
function chargerClasse($nom_classe) {
   require_once $nom_classe.'.class.php';
 }
 spl_autoload_register('chargerClasse');
 ?>