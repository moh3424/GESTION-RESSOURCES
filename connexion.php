<?php

require_once 'inc/init.inc.php';
// 2- Déconnexion  de l'internaute :
if (isset($_GET['action'])   &&  $_GET['action'] == 'deconnexion'){// si l'internaute a cliqué sur "se déconnecter"
    session_destroy(); // on suprime toute la session du membre. Rappel : Cette instruction ne s'exécute qu'en fin des script
}

// 3- On vérifie si internaute est déjà connecté :

if (internauteEstConnecte()){// s'il est connecté, on renvoie vers son profil :
    header('location:profil.php');
    exit();// Pour quitter le script

}

debug($_POST);

// 1- Traitement du formulaire
if (!empty($_POST)){ // Si le formulaire est soumis

    // Validation des champs du formulaire
    if (!isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) $contenu .= '<div class="bg-danger">L\'email est requis.</div>';

    if (!isset($_POST['password']) || empty($_POST['password']) ) $contenu .= '<div class="bg-danger">Le Mot est requis.</div>';

     //-------------------------------
    // Si pas d'erreur sur le formulaire, on vérifie que le pseudo est disponible dans la BDD:

    if (empty($contenu)) { //si $contenu est vide, c'est qu'il n'y a pas d'erreur
        $membre = executeRequete("SELECT * FROM membre WHERE email = :email AND password = :password",array(':email'=> $_POST['email'], ':password'=> $_POST['password']) );

        if ($membre->rowCount() > 0){// si le nombre de ligne est supérieur à 0, alors le login et le mdp existent ensemble en BDD
            // On crée une session avec les informations du membre:
                $informations =$membre->fetch(PDO::FETCH_ASSOC);// On fait in fetch pour transformer l'objet $membre en un array associatif qui contien en indices le nom de toutes les champs de la requête
                debug($informations);
                $_SESSION['membre'] = $informations;// Nous créons une session avec les infos du membre qui proviennent de la BDD
                header('location:profil.php');
                exit();// en redirige l'internaute vers la page de son profil, et on quitte ce script avec la fonction exit()

        }else {// sinon c'est qu'il y a erreur sur les identifiants 
            $contenu .= '<div class="bg-danger">Erreur sur les identifiants</div>';

        }/* fin de if ($membre->rowCount() > 0) */

    }/* fin  if (empty($contenu)) */
}// fin de if (!empty($_POST))



// ----------------------AFFICHAGE----------------------------
require_once 'inc/haut.inc.php';
?>
 <h2 class="mt-4">Connexion</h2>
 <?php echo $contenu; ?>

 <form method="post" action="">

    <div class="form-group">
        <label class="mr-5" for="email">Email</label>
        <input class="ml-2" type="text" name="email" id="email" value=""><br><br>
    </div>
 
    
    <div class="form-group"    
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" value=""><br><br>
    </div>

    
        <input  type="submit" value="se connecter" class="btn btn-success btn-sm ml-5">
   
</form>



<?php

require_once 'inc/bas.inc.php';