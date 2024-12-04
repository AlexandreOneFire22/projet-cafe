<?php

namespace App\Modele;
use App\Utilitaire\Singleton_ConnexionPDO;
use PDO;

class Modele_Jeton{
    static function Jeton_Ajouter($valeur, $idUtilisateur)
    {
        $nowPlusUnJour = new \DateTime("+1 day");
        $dateFin = $nowPlusUnJour->format("Y-m-d H:i:s");

        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare(' 
        insert into `token` ( id, valeur, codeAction, idUtilisateur, dateFin, utilise) 
        VALUE ( NULL, :valeur, 0, :idUtilisateur, :dateFin, 0)');
        $requetePreparee->bindParam('valeur', $valeur);
        $requetePreparee->bindParam('idUtilisateur', $idUtilisateur);
        $requetePreparee->bindParam('dateFin', $dateFin );
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        return $reponse;
    }


    static function Jeton_utilise($valeur)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        update token
        set utilise = 1 
        where valeur = :valeur');
        $requetePreparee->bindParam('valeur', $valeur);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        return $reponse;
    }


    static function Jeton_select_ByValeur($valeur)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        select * 
        from `token`
        where valeur = :valeur');
        $requetePreparee->bindParam('valeur', $valeur);
        $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        $reponse = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $reponse;
    }




}