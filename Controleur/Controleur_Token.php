<?php

use App\Modele\Modele_Jeton;
use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_ChangerMdp_Token;
use App\Vue\Vue_Compte_Administration_Gerer;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_Entete;
use App\Vue\Vue_Utilisateur_Changement_MDP;

$token = Modele_Jeton::Jeton_select_ByValeur(urlencode($_GET["token"]));


if (!$token){
    echo "Se token est non valide, veuillez en regénérer un nouveau";
    exit();
}

//$Vue->setEntete(new Vue_Structure_Entete());

switch ($token["codeAction"]){
   case 0:
       if ($token["utilise"]==1){
           echo "Se token à déjà été utilisé, veuillez en regénérer un nouveau";
           exit();
       }
       if ($token["dateFin"]>new \DateTime()){
           echo "Se token est périmé, veuillez en regénérer un nouveau";
           exit();
       }

       if ($action == "submitModifMDPToken"){
           if ($_REQUEST["NouveauPassword"] == $_REQUEST["ConfirmPassword"]) {
               $Vue->setEntete(new Vue_Structure_Entete());
               Modele_Utilisateur::Utilisateur_Modifier_motDePasse($token["idUtilisateur"], $_REQUEST["NouveauPassword"]);

               $Vue->addToCorps(new Vue_Connexion_Formulaire_client("<label><b>Votre mot de passe a bien été modifié</b></label>"));
              // $Vue->addToCorps(new Vue_Compte_Administration_Gerer("<label><b>Votre mot de passe a bien été modifié</b></label>"));
               // Dans ce cas les mots de passe sont bons, il est donc modifier

           } else {
               $Vue->setEntete(new Vue_Structure_Entete());
               $Vue->addToCorps(new Vue_ChangerMdp_Token($_GET["token"], "<label><b>Les nouveaux mots de passe ne sont pas identiques</b></label>", "Gerer_monCompte"));
           }
           break;
       }else{
           $Vue->setEntete(new Vue_Structure_Entete());
           $Vue->addToCorps(new Vue_ChangerMdp_Token($_GET["token"]));
           break;
       }

}