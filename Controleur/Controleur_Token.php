<?php

use App\Modele\Modele_Jeton;
use App\Vue\Vue_ChangerMdp_Token;
use App\Vue\Vue_Structure_Entete;

$token = Modele_Jeton::Jeton_select_ByValeur($_REQUEST["token"]);
if (!isset($token)){
    echo "Se token non valide, veuillez en regénérer un nouveau";
    exit();
}

$Vue->setEntete(new Vue_Structure_Entete());

switch ($token["codeAction"]){
    case 0:
        if ($token["utilise"]==1){
            echo "Se token à déjà été utilisé, veuillez en regénérer un nouveau";
            exit();
        }
        if ($token["dateFin"]<new \DateTime()){
            echo "Se token est périmé, veuillez en regénérer un nouveau";
            exit();
        }



        if ($_SERVER["REQUEST_METHOD"] === "POST"){



        }else{
            $Vue->addToCorps(new Vue_ChangerMdp_Token());
        }


}