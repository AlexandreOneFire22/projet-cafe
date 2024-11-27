<?php

use App\Modele\Modele_Entreprise;
use App\Modele\Modele_Salarie;
use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_Mail_Confirme;
use App\Vue\Vue_Mail_ReinitMdp;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;


use PHPMailer\PHPMailer\PHPMailer;
//Ce contrôleur gère le formulaire de connexion pour les visiteurs

$Vue->setEntete(new Vue_Structure_Entete());

switch ($action) {
    case "reinitmdpconfirm":

        //comme un qqc qui manque... je dis ça ! je dis rien !

        //Génération mot de passe provisoire :

        $chaine = "ABCDEFGHIJKLMONOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime() * 1000000);
        $pass = "";
        for ($i = 0; $i < 12; $i++) {
            $pass .= $chaine[rand() % strlen($chaine)];
        }

        //rédaction et envoie email :


        include "vendor/autoload.php";
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = '127.0.0.1';
        $mail->Port = 1025; //Port non crypté
        $mail->SMTPAuth = false; //Pas d’authentification
        $mail->SMTPAutoTLS = false; //Pas de certificat TLS
        $mail->setFrom('test@cafe.fr', 'admin');
        $mail->addAddress($_REQUEST["email"] ?? "emailPasTrouvé@gmail.com", 'Mon client');
        if ($mail->addReplyTo('test@cafe', 'admin')) {
            $mail->Subject = 'Objet : Bonjour !';
            $mail->isHTML(false);
            $mail->Body = "Votre mot de passe temporaire est : $pass . Vous devrez modifier votre mot de passe une fois connecte.";

            if (!$mail->send()) {
                $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
            } else {
                $msg = 'Message envoyé ! Merci de nous avoir contactés.';
                $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["email"]);
                echo    "idUtilisateur". $utilisateur["idUtilisateur"];
                Modele_Utilisateur::Utilisateur_Modifier_motDePasse($utilisateur["idUtilisateur"],$pass);
            }
        } else {
            $msg = 'Je sais pas pourquoi le programme à été ici mais..... oh well ';

            $mail->Subject = 'Objet : Bonjour !';
            $mail->isHTML(false);
            $mail->Body = "Votre mot de passe temporaire est : $pass . Vous devrez modifier votre mot de passe une fois connecte.";

            if (!$mail->send()) {
                $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
            } else {
                $msg = 'Message envoyé ! Merci de nous avoir contactés.';
                $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["email"]);
                echo    "idUtilisateur". $utilisateur["idUtilisateur"];
                Modele_Utilisateur::Utilisateur_Modifier_motDePasse($utilisateur["idUtilisateur"],$pass);
            }
        }
        echo $msg;



        $Vue->addToCorps(new Vue_Mail_Confirme());

        break;

    case "reinitmdp":


        $Vue->addToCorps(new Vue_Mail_ReinitMdp());

        break;
    case "Se connecter" :
        if (isset($_REQUEST["compte"]) and isset($_REQUEST["password"])) {
            //Si tous les paramètres du formulaire sont bons

            $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["compte"]);

            if ($utilisateur != null) {
                //error_log("utilisateur : " . $utilisateur["idUtilisateur"]);
                if ($utilisateur["desactiver"] == 0) {
                    if ($_REQUEST["password"] == $utilisateur["motDePasse"]) {
                        $_SESSION["idUtilisateur"] = $utilisateur["idUtilisateur"];
                        //error_log("idUtilisateur : " . $_SESSION["idUtilisateur"]);
                        $_SESSION["idCategorie_utilisateur"] = $utilisateur["idCategorie_utilisateur"];
                        //error_log("idCategorie_utilisateur : " . $_SESSION["idCategorie_utilisateur"]);
                        switch ($utilisateur["idCategorie_utilisateur"]) {
                            case 1:
                                $_SESSION["typeConnexionBack"] = "administrateurLogiciel"; //Champ inutile, mais bien pour voir ce qu'il se passe avec des étudiants !
                                $Vue->setMenu(new Vue_Menu_Administration());
                                break;
                            case 2:
                                $_SESSION["typeConnexionBack"] = "utilisateurCafe";
                                $Vue->setMenu(new Vue_Menu_Administration());
                                break;
                            case 3:
                                $_SESSION["typeConnexionBack"] = "entrepriseCliente";
                                //error_log("idUtilisateur : " . $_SESSION["idUtilisateur"]);
                                $_SESSION["idEntreprise"] = Modele_Entreprise::Entreprise_Select_Par_IdUtilisateur($_SESSION["idUtilisateur"])["idEntreprise"];
                                include "./Controleur/Controleur_Gerer_Entreprise.php";
                                break;
                            case 4:
                                $_SESSION["typeConnexionBack"] = "salarieEntrepriseCliente";
                                $_SESSION["idSalarie"] = $utilisateur["idUtilisateur"];
                                $_SESSION["idEntreprise"] = Modele_Salarie::Salarie_Select_byId($_SESSION["idUtilisateur"])["idEntreprise"];
                                include "./Controleur/Controleur_Catalogue_client.php";
                                break;
                        }

                    } else {//mot de passe pas bon
                        $msgError = "Mot de passe erroné";

                        $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                    }
                } else {
                    $msgError = "Compte désactivé";

                    $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                }
            } else {
                $msgError = "Identification invalide";

                $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));
            }
        } else {
            $msgError = "Identification incomplete";

            $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));
        }
    break;
    default:

        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());

        break;
}


$Vue->setBasDePage(new Vue_Structure_BasDePage());