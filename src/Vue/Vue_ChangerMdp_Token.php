<?php
namespace App\Vue;
use App\Utilitaire\Vue_Composant;

class Vue_ChangerMdp_Token extends Vue_Composant
{
    private $token;
    private $msg;


    public function __construct($token, $msg="")
    {
        $this->token = $token;
        $this->msg = $msg;
    }

    function donneTexte(): string
    {

        $str="    <form style='display: contents'>
        
<table style='display: inline-block'> 
 
        <input type='hidden' name='token' value='$this->token'>
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
                
                <button type='submit' id='submitModifMDPToken' name='action' value='submitModifMDPToken'>
                 Modifier son mot de passe
                 </button>
                 
            </td>
     </tr>
    </form>$this->msg";
        return $str;
    }
}
