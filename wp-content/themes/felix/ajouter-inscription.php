<?php
// chargement des fonctionnalités WordPress nécessaires au traitement
require_once "../../../wp-load.php";


//verifie si on vient du formulaire, si oui appel le formulaire de traitement
if (isset($_POST['envoyer-inscription'])) {
    felix_ajouter_formulaire_inscription_bd();
}


// fonction permettant d'Ajouter des inscription du formulaire web
function felix_ajouter_formulaire_inscription_bd(){
        if (isset($_POST['envoyer-inscription'])) {
            $_POST = stripslashes_deep($_POST);
            global $wpdb;
            //definit le nom de la table
            $table_inscription = $wpdb->prefix . 'felix_inscription_ajax';
            //variable de session pour valider la réussite de l'insertion des données
            $_SESSION['felix_ajout_reussi_inscription'] = false;
            $_SESSION['succesformulaireajoutinscription'] = false;
            $_SESSION['messageajoutinscription'] = "";

            //variable recupérée dans le post
            $nom_famille = $_POST['nom_famille'];
            $prenom = $_POST['prenom'];
            $adresse= $_POST['adresse'];
            $adresse_courriel = $_POST['courriel'];
            $telephone = $_POST['telephone'];
            $id_ville = $_POST['liste_ville'];

            //validation des donnée avant l'insertion
            if (!empty($prenom)  &&  strlen($prenom) < 50) {
                $_SESSION['felix_ajout_reussi_inscription'] = true;
            }
            if (!empty($nomfamille)  &  strlen($nom_famille) < 30) {
                $_SESSION['felix_ajout_reussi_inscription'] = true;
            }
            if (!empty($telephone)  &&  strlen($telephone) <= 12) {
                $_SESSION['felix_ajout_reussi_inscription'] = true;
            }
            if (!empty($adresse_courriel) && strlen($adresse_courriel) < 30) {
                $_SESSION['felix_ajout_reussi_inscription'] = true;
            }
            if (!empty($adresse) || strlen($adresse) < 50) {
                $_SESSION['felix_ajout_reussi_inscription'] = true;
            }
            else{
                $_SESSION['felix_ajout_reussi_inscription'] = false;
            }
            //insert des données récupérées dans le post
            $reussite = $wpdb->insert(
                $table_inscription,
                array(
                    "nom_famille" => $nom_famille,
                    "prenom" => $prenom,
                    "adresse" => $adresse,
                    "telephone" => $telephone,
                    "courriel" => $adresse_courriel,
                    "ville" => $id_ville,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
            //instancie la variable de session pour passer un message de succes à l'utilisateur
            if ($reussite) {
                $_SESSION['messageajoutinscription'] = __('Livre ajouté avec succès.', 'felix');
                $_SESSION['succesformulaireajoutinscription'] = true;
            }
            else{
                // réagir en cas de problème
                felix_log_debug($wpdb->last_error); // Log l'erreur sql dans mon fichier debug.log (si en mode débogage)
            }
        }
    $url_retour = get_site_url();
    wp_redirect($url_retour);
}


