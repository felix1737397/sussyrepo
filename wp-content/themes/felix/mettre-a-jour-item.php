<?php
require_once "../../../wp-load.php";








// les variables de session seront initialisées à true seulement si tous les tests sont réussis
$_SESSION['sa'] = false;
$_SESSION['felix_erreur_mise_a_jour_item'] = '';
$_SESSION['felix_nonce_formulaire_valide'] = false;
$_POST = stripslashes_deep($_POST);

if (isset($_POST['modification_item'])) {
    modification_item();
    $url_retour = admin_url( "admin.php?page=felix_gestion" );
    wp_redirect( $url_retour );
}


function modification_item()
{
    //on va chercher l'ID dans le POST
    $id = $_POST['id'];
    //on valide le nounce
    if (wp_verify_nonce($_POST['modifier_item'], "editer_item_$id")) {
        global $wpdb;
        $table_livre = $wpdb->prefix . "felix_livre";

        //update dans la table livre
        $reussite = $wpdb->update(
            $table_livre,
            array(
                'nom_livre' => $_POST['titre_livre'],
                'auteur' => $_POST['nom_auteur'],
                'genre_id' => $_POST['liste_genre'],
            ),
            array('livre_id' => $id),
            array(
                '%s',
                '%s',
                '%s',
            ),
            array('%d')
        );
        // triple égalité car si aucune mise à jour quand données identiques à la bd, retourne 0
        if ($reussite === false) {
            // réagir en cas de problème
            felix_log_debug($wpdb->last_error);
            $_SESSION['felix_erreur_mise_a_jour_item'] = __('une erreur s\'est produite lors de la modification des données','felix');
        } else {
            $_SESSION['felix_mise_a_jour_item_reussie'] = true;
        }
    }
}

