<?php
$inscription ='';
require_once 'inc/init.inc.php';



//--------------------------------AFFICHAGE--------------------------------------
require_once 'inc/haut.inc.php'; // doctype, header, nav
// Traitement du formulaire 
if (!empty($_POST)){ // Si le formulaire est soumis

    // Validation des champs du formulaire
   

    if (!isset($_POST['nom_salarie']) || strlen($_POST['nom_salarie']) < 4 || strlen($_POST['nom_salarie']) > 45) $contenu .= '<div class="bg-danger">Le nom doit contenir entre 4 et 20 caractères.</div>';


    if (!isset($_POST['poste']) || strlen($_POST['poste']) < 5 || strlen($_POST['poste']) > 45  ) $contenu .= '<div class="bg-danger">Le poste doit contenir entre 5 et 45 caractères.</div>';


    if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f') ) $contenu .= '<div class="bg-danger">La civilité est incorecte</div>';

    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)  ) $contenu .= '<div class="bg-danger">Email est incorrect.</div>';// filter_var() avec l'argument FILTER_VALIDATE_EMAIL valide que $_POST['email] est bien de format d'un email. Notez que cela marche aussi  pour valider les URL avec FILTER_VALIDATE_URL

    if (!isset($_POST['date_naissance']) || !validateDate($_POST['date_naissance'], 'd-m-Y')) $contenu .= '<div class="bg-danger">La date de naissance est incorrect.</div>';

    if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 5 || strlen($_POST['mdp']) > 45  ) $contenu .= '<div class="bg-danger">Le Mot de passe doit contenir entre 4 et 45 caractères.</div>';

    if (!isset($_POST['statut']) || ($_POST['statut'] != '1' && $_POST['statut'] != '2') ) $contenu .= '<div class="bg-danger">Le statut est incorecte</div>';

    if (!isset($_POST['nom_service']) || ($_POST['nom_service'] != 'informatique' && $_POST['nom_service'] != 'comptabilite' && $_POST['nom_service'] != 'informatique' && $_POST['nom_service'] != 'comptabilite') ) $contenu .= '<div class="bg-danger">Le service est incorecte</div>';

    if (!isset($_POST['date_debut']) || !validateDate($_POST['date_debut'], 'Y-m-d')) $contenu .= '<div class="bg-danger">La date est incorrect.</div>';

    if (!isset($_POST['date_fin']) || !validateDate($_POST['date_fin'], 'Y-m-d')) $contenu .= '<div class="bg-danger">La date est incorrect.</div>';

    if (!isset($_POST['nom_projet']) || strlen($_POST['nom_projet']) < 5 || strlen($_POST['nom_projet']) > 20  ) $contenu .= '<div class="bg-danger">Le nom du projet doit contenir entre 5 et 45 caractères.</div>';

    
    

    //-------------------------------
    // Si pas d'erreur sur le formulaire, on vérifie que le nom et l'email sont disponible dans la BDD:

    if (empty($contenu)) { //si $contenu est vide, c'est qu'il n'y a pas d'erreur

        // Vérification du pseudo :
            $membre = executeRequete("SELECT * FROM membre  WHERE email = :email", array(':email' => $_POST['email'])); // on sélectionne en base les éventuels membres dont le pseudo correspond au pseudo donné par l'internaute lors de l'inscription

        if ($membre->rowCount() >0){//si la requête retourn 1 ou plusieurs resultats c'est que le pseudo existe en BDD
            $contenu .= '<div class="bg-danger">Le salarié existe déjà.</div>';
        } else {
            // sinon le étant disponible, on enregitr le membre en BDD :
            executeRequete("INSERT INTO salarie (nom_salarie, civilite, date_naissance, poste, statut) VALUES(:nom_salarie, :civilite, :date_naissance, :poste, :statut, 0)", array(
                                                                                                                                                            ':nom_salarie' => $_POST['nom_salarie'],
                                                                                                                                                            ':civilite' => $_POST['civilite'],
                                                                                                                                                            ':date_naissance' => $_POST['date_naissance'],
                                                                                                                                                            ':poste' => $_POST['poste'],
                                                                                                                                                            ':statut' => $_POST['statut']
                                                                                                                                                            ));
            executeRequete("INSERT INTO membre (email, password) VALUES(:email, :password)", array(':email' => $_POST['email'],
                                                                                         ':password' => $_POST['password'],  
                                                                                        )); 
            executeRequete("INSERT INTO service (nom_service) VALUES(:nom_service)", array(':nom_service' => $_POST['nom_service'])); 

            executeRequete("INSERT INTO projets (nom_projet, date_debut, date_fin) VALUES(:nom_projet, :date_debut, :date_fin)", array(':nom_projet' => $_POST['nom_projet'],
                                                                                                                         ':date_debut' => $_POST['date_debut'],
                                                                                                                         ':date_fin' => $_POST['date_fin'],
                                                                                                                        )); 
            
                                               
            
          $contenu .= '<div class="bg-success">Le salarié est bien enregistré . <a href="connexion.php">Cliquez ici pour vous connecter.</a></div>';    
         $inscription = true; // pour ne plus afficher le formulaire sur cette page                                                              
        }// fin du else


    }/* (empty($contenu)) */





}/* fin if (!empty($_POST)) */



?>
  





    
<?php
 // pour afficher les messages à l'internaute
 echo $contenu;

  
if (!$inscription) : // (!$inscription) équivaut à ($inscription == false), c'est à dire que nous entrons dans la condition si $inscription vaut false. Syntaxe en if (codition) : .... endif;
    
?>
    <h1 class = "mt-4">Vueillez renseigner le formulaire pour inscrire un salarie. </h1>

    <form method="post" action="">

       
            <label for="nom">Nom</label><br>
            <input type="text" name="nom" id="nom" value=""><br><br>
        

      
            <label for="email">Email</label><br>
            <input type="text" name="email" id="email" value=""><br>

             <label for="password">Mot de passe</label><br>
            <input type="password" name="password" id="password" value=""><br>
       

        
            <label>Civilité</label><br>
            <input type="radio" name="civilite"  value="f" checked>Femme
            <input type="radio" name="civilite"  value="m" checked>Homme <br><br>
      

     
            <label for="date_naissance">Date de naissance</label><br>
            <input type="text" name="date_naissance" id="date_naissance" value=""><br><br>
      

     
            <label for="poste">Poste</label><br>
            <input type="text" name="poste" id="poste" value=""><br><br>
        

       
            <label for="statut">Statut</label><br>
            <select name="statut" id="statut">
                <option value="">****</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select><br><br>
            
            <label for="service">Service</label><br>
            <select name="service" id="service">
                <option value="">****</option>
                <option value="informatique">informatique</option>
                <option value="comptabilité">comptabilité</option>
                <option value="maintenance">maintenance</option>
                <option value="marketing">marketing</option>
            </select><br><br>

            <label for="nom_projet">Projet</label><br>
            <input type="text" name="nom_projet" id="nom_projet" value=""><br><br>

            <label for="date_debut">Date début de projet</label><br>
            <input type="text" name="date_debut" id="date_debut" value=""><br><br>

             <label for="date_fin">Date fin de projet</label><br>
            <input type="text" name="date_fin" id="date_fin" value=""><br><br>

            
      

        <input type="submit" value="enregistrer" class="btn"><br>


    
    </form>



<?php 
endif;
require_once 'inc/bas.inc.php'; // footer et fermetures des balise