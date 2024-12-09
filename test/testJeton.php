<?php

include_once "vendor/autoload.php";

use App\Modele\Modele_Jeton;


Modele_Jeton::Jeton_Ajouter("test",0,675);

Modele_Jeton::Jeton_Ajouter("test2",0,680);
Modele_Jeton::Jeton_utilise("test2");

$test = Modele_Jeton::Jeton_select_ByValeur("test");

print_r($test);

$token = Modele_Jeton::Jeton_select_ByValeur("test");
echo gettype($token);
if (!$token){
    echo "Se token est non valide, veuillez en regénérer un nouveau";
}



print_r($token);

echo $token["codeAction"];