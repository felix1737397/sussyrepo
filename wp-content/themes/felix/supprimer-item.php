<?php
require_once "../../../wp-load.php";
// les variables de session seront initialisées à true seulement si tous les tests sont réussis
$_SESSION['felix_suppression_item_reussie'] = false;
$_SESSION['felix_nonce_lien_valide'] = false;

global $wpdb;
$table_livre = $wpdb->prefix . "felix_livre";

// verifie l'ID dans le GET
if ( isset($_GET['id']) ){
    $id = $_GET['id'];
    //On verifie la validité du Nounce
    if (wp_verify_nonce( $_GET['_wpnonce'], "supprimer_item_$id" )){
        $felix_nonce_lien_valide = true;
    }
}
//Si le nounce est bon, on peut delete
if($felix_nonce_lien_valide){
    if ($wpdb->delete($table_livre, ['livre_id' => $id])) {
        $_SESSION['felix_suppression_item_reussie'] = true;
    } else {
        felix_log_debug($wpdb->last_error);
    }
    //redirection
    $url_retour = admin_url( "admin.php?page=felix_gestion" );
    wp_redirect( $url_retour );
}
