<?php
require_once "../../../wp-load.php";


// renvoie le contrôle à la page qui affiche la liste des items dans le tableau de bord

if ( isset($_POST['envoyer-item'] ) ) {
    ajout_formulaire();
}


//formulaire pour enregistrer les items dans la base de donnée
function ajout_formulaire(){
    $_POST = stripslashes_deep($_POST);
    global $wpdb;
    //declaration de la table a utiliser avec le préfixe
    $table_livre = $wpdb->prefix . 'felix_livre';

    //déclaration des variables de session, dans le but de mieux traiter les cas (reussite, echec)
    $_SESSION['felix_ajout_reussi'] = false;
    $_SESSION['felix_nonce_valide'] = false;
    $_SESSION['succesformulaireajoutlivre'] = false;
    $_SESSION['messageajoutlivre'] ="";

    //recupération des données dans le post
    $titre = ($_POST['titre_livre']);
    $auteur = $_POST['nom_auteur'];
    $genre = $_POST['liste_genre'];

    //verifie la validité du Nonce, pour rendre la page plus sécuritaire
    if (wp_verify_nonce( $_POST['ajout_livre'], 'ajout_livre' )){
        //nonce valide, on continue la validité des données
        $_SESSION['felix_nonce_valide'] = true;
        $_SESSION['felix_ajout_reussi'] = true;

        // validation côté serveur des données lues dans le formulaire
        if (empty($titre) || strlen($titre) > 50) {
                $_SESSION['felix_ajout_reussi'] = false;
                $_SESSION['messageajoutlivre'] .= 'Titre de 50 caractère ou moins requis';
        }
        if(empty($auteur) || strlen($auteur) < 30){
            $_SESSION['felix_ajout_reussi'] = false;
            $_SESSION['messageajoutlivre'] .= 'Auteur de 50 caractère ou moins requis';
        }
        if(empty($genre) || $_genre = "aucun"){
            $_SESSION['felix_ajout_reussi'] = false;
            $_SESSION['messageajoutlivre'] .= 'Genre requis';
        }
        // traitement du formulaire
        if($_SESSION['felix_ajout_reussi'] = true){
                $reussite = $wpdb->insert(
                    $table_livre,
                    array(
                        "nom_livre" => $titre,
                        "auteur" => $auteur,
                        "genre_id" => $genre,
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                    )
                );
                if ($reussite) {
                    $_SESSION['messageajoutlivre'] = __('Livre ajouté avec succès.', 'felix');
                }
                else{
                    // réagir en cas de problème
                    felix_log_debug($wpdb->last_error); // Log l'erreur sql dans mon fichier debug.log (si en mode débogage)
                }

        }
        // initialisation de variables de session pour indiquer le résultat de l'opération
        if ($_SESSION['felix_ajout_reussi'] = true) {
            $_SESSION['succesformulaireajoutlivre'] = true;
            $url_retour = admin_url( "admin.php?page=felix_gestion" );
            wp_redirect( $url_retour );
        }
   }
    else{
        $_SESSION['MessageFormulaireAjoutItem'] = __('Une erreur est survenue. Veuillez réessayer.');
    }
}
?>