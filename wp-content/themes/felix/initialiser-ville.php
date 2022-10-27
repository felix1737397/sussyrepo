<?php
require_once "../../../wp-load.php";
if (isset($_GET['id'])) {

    //GET du ID pour select les bons identifiants
    $id = $_GET['id'];
    global $wpdb;
    //Declare les tables a utiliser
    $table_ville = $wpdb->prefix . "felix_ville";
    $code_html_villes = "";
    $requete = "SELECT id_ville, nom_ville FROM $table_ville WHERE id_province = '%d' ORDER BY nom_ville";
    $requete_ville = $wpdb->prepare( $requete,  $_GET['id']  );
    $resultat = $wpdb->get_results( $requete_ville);
    //array pour stocker les données
    $nom_ville = [];
    $id = [];

    $erreur_sql = $wpdb -> last_error;
    // on vérifie qu'il n'y a aucun erreur SQL
    if ($erreur_sql == "") {
        if ( $wpdb->num_rows > 0 ) {
            foreach ($resultat as $enreg2 ) {
                array_push($id, $enreg2->id_ville);
                array_push($nom_ville, $enreg2->nom_ville);
            }
        }
        else {
                felix_log_debug("Erreur dans la requête");
        }

        $reponse_json = "<option value='$id[0]'>$nom_ville[0]</option>";
    } else {
        // réagir en cas de problème
        felix_log_debug($erreur_sql); // Log l'erreur sql dans mon fichier debug.log (si en mode débogage)
    }
}
else {
    // ici, j'ai choisi de retourner une erreur 500. J'aurais aussi pu retourner une chaîne du genre "Bonjour, inconnu!"
    http_response_code(500);
}
echo json_encode(['villes' => $reponse_json]);