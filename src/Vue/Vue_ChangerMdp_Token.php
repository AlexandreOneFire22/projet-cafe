<?php
namespace App\Vue;
use App\Utilitaire\Vue_Composant;

class Vue_ChangerMdp_Token extends Vue_Composant
{

    private string $action;
    private string $msg;

    function donneTexte(): string
    {

        $str="    <form style='display: contents'>
        
<table style='display: inline-block'> 

        <input type='hidden' name='case' value='$this->case'>
        <h1>Changement Mot de passe</h1>
        <tr>
        
            <td>
                <label>Veuillez saisir votre nouveau mot de passe : </label>
            </td>
            <td>
    
                <input type='password' placeholder='mot de passe' name='NouveauPassword' required>
            </td>
        </tr>
        <tr>
            <td>
                <label>Veuillez confirmer votre nouveau mot de passe : </label>
            </td>
            <td><input type='password' placeholder='mot de passe' name='ConfirmPassword' required>
            </td>
        </tr>
        <tr>
            <td>
                
                <button type='submit' id='submitModifMDP' name='action' value='submitModifMDP'>
                 Modifier son mot de passe
                 </button>
            </td>
     </tr>
    </form>$this->msg";
        return $str;
    }
}
