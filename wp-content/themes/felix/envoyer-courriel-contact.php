<?php
// chargement des fonctionnalités WordPress nécessaires au traitement
require_once "../../../wp-load.php";


// verifie qu'on vient du bon formulaire
if (isset($_POST['envoyer-courriel'])) {
    felix_ajouter_formulaire_bd();
}

//fonction qui traite les données envoyer
function felix_ajouter_formulaire_bd(){
    if (isset($_POST['envoyer-courriel'])) {
        $_POST = stripslashes_deep($_POST);
        global $wpdb;
        //declaration de la table pour l'insertion avec préfixe
        $table_formulaire = $wpdb->prefix . 'formulaire_contact';

        //traitement des données récupérées dans le POST
        $nom = $_POST['nom_visiteur'];
        $adresse_courriel = $_POST['courriel_visiteur'];
        $message = $_POST['message_visiteur'];
        $date = date("Y-m-d h:i:sa");
        $adresse_ip = $_SERVER['REMOTE_ADDR'];
        $headers[] = "Reply-To: <$adresse_courriel>";
        $sujet = "formulaire de contact";
        $verif = '';
            //validation des donnée avant l'insertion
            if (!empty($nom)  &&  strlen($nom) < 50) {

            }
            if (!empty($adresse_courriel) && strlen($adresse_courriel) < 30) {

            }
            if (!empty($message) || strlen($message) < 1000) {

            }
            else{
                $verif = __('Erreur dans le traitement du formulaire','felix');
            }
            if ($verif == '') {
                $reussite = $wpdb->insert(
                    $table_formulaire,
                    array(
                        "nom" => $nom,
                        "adresse_courriel" => $adresse_courriel,
                        "message" => $message,
                        "date" => $date,
                        "adresse_ip" => $adresse_ip
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                    )
                );
                if (!$reussite) {
                    // réagir en cas de problème
                    felix_log_debug($wpdb->last_error); // Log l'erreur sql dans mon fichier debug.log (si en mode débogage)
                }
                $_SESSION['envoyer-courriel'] = "envoyer-courriel";
                $envoi_reussi = wp_mail("homeassistant@hackezmoipassvp.com", $sujet, $message, $headers);
                $url_retour = get_site_url();
                wp_redirect($url_retour);
            }
    }
}


