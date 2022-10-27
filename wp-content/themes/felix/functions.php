<?php
/**
 * Fonction de rappel du hook after_setup_theme, exécutée après que le thème ait été initialisé
 *
 * Utilisation : add_action( 'after_setup_theme', 'felix_apres_initialisation_theme' );
 *
 * @author Felix Michaud
 *
 */
function felix_apres_initialisation_theme() {
    // Retirer la balise <meta name="generator">
    remove_action( 'wp_head', 'wp_generator' );

}
function felix_favicon(){
    //ajoute le favicon
    add_action( 'wp_head','wp_generator' );
}


add_action( 'after_setup_theme', 'felix_apres_initialisation_theme' );

/**
 * Change l'attribut ?ver des .css et des .ma-bibliotheque-popup pour utiliser celui de la version de style.css
 *
 * Utilisation : add_filter( 'style_loader_src', 'felix_attribut_version_style', 9999 );
 *               add_filter( 'script_loader_src', 'felix_attribut_version_style', 9999 );
 * Suppositions critiques : dans l'entête du fichier style.css du thème enfant, le numéro de version
 *                          à utiliser est inscrit à la ligne Version (ex : Version: ...)
 *
 * @author Christiane Lagacé
 * @return String Url de la ressource, se terminant par ?ver= suivi du numéro de version lu dans style.css
 *
 */
function felix_attribut_version_style( $src ) {
    $version = felix_version_style();
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
        $src = remove_query_arg( 'ver', $src );
        $src = add_query_arg( 'ver', $version, $src );
    }
    return $src;
}

add_filter( 'style_loader_src', 'felix_attribut_version_style', 9999 );
add_filter( 'script_loader_src', 'felix_attribut_version_style', 9999 );

/**
 * Retrouve le numéro de version de la feuille de style
 *
 * Utilisation : $version = felix_version_style();
 * Suppositions critiques : dans l'entête du fichier style.css du thème enfant, le numéro de version
 *                          à utiliser est inscrit à la ligne Version (ex : Version: ...)
 *
 * @author Felix Michaud
 * @return String Le numéro de version lu dans style.css ou, s'il est absent, le numéro 1.0
 *
 */
function felix_version_style() {
    $default_headers =  array( 'Version' => 'Version' );
    $fichier = get_stylesheet_directory() . '/style.css';
    $data = get_file_data( $fichier, $default_headers );
    if ( empty( $data[ 'Version' ] ) ) {
        return "1.0";
    } else {
        return $data[ 'Version' ];
    }
}

/**
 * Enregistre une information de débogage dans le fichier debug.log, seulement si WP_DEBUG est à true
 *
 * Utilisation : felix_log_debug( 'test' );
 * Inspiré de http://wp.smashingmagazine.com/2011/03/08/ten-things-every-wordpress-plugin-developer-should-know/
 *
 * @author Christiane Lagace <christianelagace.com>
 *
 */
function felix_log_debug( $message ) {
    if ( WP_DEBUG === true ) {
        if ( is_array( $message ) || is_object( $message ) ) {
            error_log( 'Message de débogage: ' . print_r( $message, true ) );
        } else {
            error_log( 'Message de débogage: ' . $message );
        }
    }
}

/**
 * Affiche une information de débogage à l'écran, seulement si WP_DEBUG est à true
 *
 * Utilisation : felix_echo_debug( 'test' );
 * Suppositions critiques : le style .debug doit définir l'apparence du texte
 *
 * @author Christiane Lagacé <christianelagace.com>
 *
 */
function felix_echo_debug( $message ) {
    if ( WP_DEBUG === true ) {
        if ( ! empty( $message ) ) {
            echo '<span class="debug">';
            if ( is_array( $message ) || is_object( $message ) ) {
                echo '<pre>';
                print_r( $message ) ;
                echo '</pre>';
            } else {
                echo $message ;
            }
            echo '</span>';
        }
    }
}

/**
 *importation du favicon
 * retourne le code html
 * * Utilisation :
 * add_action('wp_head', 'felix_ajouter_favicon');
    add_action( 'admin_head', 'felix_ajouter_favicon' );
 * Suppositions critiques : le style .debug doit définir l'apparence du texte
 */
function felix_ajouter_favicon(){

    $code_html_favicon = "";
    if( WP_DEBUG == true ){
        $code_html_favicon = '    
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../../favic0n.ico/noirblanc/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../../favic0n.ico/noirblanc/favicon-16x16.png">
    <link rel="manifest" href="../../../favic0n.ico/noirblanc/site.webmanifest">
    <link rel="mask-icon" href="../../../favic0n.ico/noirblanc/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c"><meta name="theme-color" content="#ffffff">';
    } else{
        $code_html_favicon = '    
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../../favic0n.ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../../favic0n.ico/favicon-16x16.png">
    <link rel="manifest" href="../../../favic0n.ico/site.webmanifest">
    <link rel="mask-icon" href="../../../favic0n.ico/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c"><meta name="theme-color" content="#ffffff">';
    }
    return $code_html_favicon;
}

add_action('wp_head', 'felix_ajouter_favicon');
add_action( 'admin_head', 'felix_ajouter_favicon' );

/**
 * permet de print un tableau d'items sur ma page ITEMS
 * @return string le tableau de mes items
 * Utilisation: add_shortcode('felixshortcodeitem', 'felix_shortcode_item');
 */
function felix_shortcode_item(){
    global $wpdb;
    //declaration de la table livre
    $table_livre = $wpdb->prefix . 'felix_livre';
    //on fait la requête SELECT
    $requete = "SELECT nom_livre, auteur, livre_id,genre_id FROM $table_livre ORDER BY auteur";
    $resultat = $wpdb->get_results($requete);
    //declaration des tableaux pour stocker les données du select
    $tab = [];
    $auteurs = [];
    $id_tab = [];
    $affichage ="";
    $erreur_sql = $wpdb->last_error;
    //on traite seulement si aucun erreur SQL
    if ( $erreur_sql == "" ) {
        //on boucle et on stock dans les tableaux pour chaque row de la BD
        if ( $wpdb->num_rows > 0 ) {
            foreach( $resultat as $enreg ){
                array_push($id_tab, $enreg->livre_id);
                array_push($tab, $enreg->nom_livre);
                array_push($auteurs, $enreg->auteur);
            }
        }
        //si erreur on traite
        else {
            echo '<div class="messageavertissement">';
            _e( "Aucune donnée ne correspond aux critères demandés.", "felix" );
            echo '</div>';    }
        } else {
            echo '<div class="messageerreur">';
            _e( "Oups ! Un problème a été rencontré.", "felix" );
            echo '</div>';
            // afficher l'erreur à l'écran seulement si on est en mode débogage
            felix_log_debug( $erreur_sql );
        }
        // on boucle pour afficher les données sur la page
        for ($i = 0; $i < sizeof($tab); $i++){
            $affichage = $affichage . "
        <tr>
        <td class='title column-title has-row-actions column-primary'>
        <strong>$auteurs[$i]</strong>
        <div class='row-actions'>
        <td class='title column-title has-row-actions column-primary'>$tab[$i]</td>
        </div>
        </td>
        </tr>";
        }

        $code_html = "<span class='textespecial'><table>
    <thead>
        <tr>
            <th scope='row' class='check-column'>Livres</th>
        </tr>
    </thead>
    <tbody>
            $affichage
    </tbody>
</table></span><br>";

        return $code_html;
}

add_shortcode('felixshortcodeitem', 'felix_shortcode_item');

/**
 * Crée les tables personnalisées pour mon thème enfant
 *
 * Utilisation : add_action( "after_switch_theme", "felix_creer_tables" );
 *
 * @author Félix Michaud
 *
 */
function felix_creer_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    //declaration des tables
    $table_genre = $wpdb->prefix . 'felix_genre';
    $table_livre = $wpdb->prefix . 'felix_livre';
    // requete pour la table genre
    $requete_sql_genre = "CREATE TABLE $table_genre (
        genre_id bigint(20) unsigned NOT NULL auto_increment,
        nom_genre varchar(50) NOT NULL,
        PRIMARY KEY (genre_id)
    ) $charset_collate;";
    // requete pour la table livre
    $requete_sql_livre = "CREATE TABLE $table_livre (
        livre_id bigint(20) unsigned NOT NULL auto_increment,
        nom_livre varchar(50) NOT NULL, 
        auteur varchar(50) NOT NULL, 
        genre_id bigint(20) NOT NULL, 
        PRIMARY KEY (livre_id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $requete_sql_genre );
    dbDelta( $requete_sql_livre);
}
add_action("after_switch_theme", "felix_creer_tables");


/**
 * Hook pour ajouter mes premières données dans mes tables
 * utilisation: add_action("after_switch_theme", "felix_ajouter_premieres_donnees");
 */
function felix_ajouter_premieres_donnees(){
    global $wpdb;
    $table_genre = $wpdb->prefix . 'felix_genre';
    $table_livre = $wpdb->prefix . 'felix_livre';

    $requete = "SELECT COUNT(*) FROM " . $table_genre;
    $requete2= "SELECT COUNT(*) FROM " . $table_livre;

    $presence_donnees_genre = $wpdb->get_var( $requete );
    $presence_donnees_livre = $wpdb->get_var($requete2);

    if ( is_null( $presence_donnees_genre ) ) {
        $id = []; // pour stocker les identifiants au cas où ils devraient être utilisés plus loin

        $donnees = array(

            array( 'donnee test'),    // données du premier enregistrement

            array( 'donnee test'),    // données du deuxième enregistrement
        );

        foreach( $donnees as $donnee ) {
            // à chaque boucle, va chercher les informations pour un enregistrement dans le tableau de données
            $reussite = $wpdb->insert(
                $table_genre,
                array(
                    'nom_genre' => $donnee[0],
                ),
                array(

                    '%s',
                )
            );

            $id[] = $wpdb->insert_id;   // Retient l'identifiant. Pas nécessaire si les identifiants ont été codés en dur.

            if ( ! $reussite ) {
                // réagir en cas de problème
                felix_log_debug( $wpdb->last_error );
            }
        }
    }
    if ( is_null( $presence_donnees_livre ) ) {


        $id2 = []; // pour stocker les identifiants au cas où ils devraient être utilisés plus loin

        $donnees2 = array(

            array( 'donnee test', 'donnee test', '1'),    // données du premier enregistrement

            array( 'donnee test', 'donnee test', '1'),    // données du deuxième enregistrement
        );
        foreach( $donnees2 as $donnee2 ) {
            // à chaque boucle, va chercher les informations pour un enregistrement dans le tableau de données
            $reussite2 = $wpdb->insert(
                $table_livre,
                array(
                    'nom_livre' => $donnee2[0],
                    'auteur' => $donnee2[1],
                    'genre_id' =>$donnee2[2]
                ),
                array(

                    '%s',
                    '%s',
                    '%d',
                )
            );
            $id2[] = $wpdb->insert_id;   // Retient l'identifiant. Pas nécessaire si les identifiants ont été codés en dur.
            if ( ! $reussite2 ) {
                // réagir en cas de problème
                felix_log_debug( $wpdb->last_error );
            }
        }
    }
}
add_action( "after_switch_theme", "felix_ajouter_premieres_donnees" );


//function qui appel les fonctions nécessaires pour creer la page de gestion
function felix_creer_page_gestion() {
    echo afficher_liste_items();


}

/*
 * function permettant d'afficher les items sur la page de gestion
 *
 * retourne le code html
 */
function afficher_liste_items(){
    global $wpdb;
    //declaration des tables
    $table_livre = $wpdb->prefix . 'felix_livre';
    //requetes SQL
    $requete = "SELECT nom_livre, auteur, livre_id,genre_id FROM $table_livre ORDER BY auteur";

    // on va chercher les resultats de la requete
    $resultat = $wpdb->get_results($requete);
    //initialisation des tableaux pour stocker les données du SELECT
    $tab = [];
    $auteurs = [];
    $id_tab = [];
    $affichage ="";
    $erreur_sql = $wpdb->last_error;
    $message_aucune_donnee = __( 'Aucune donnée ne correspond aux critères demandés.','felix' );
    $message_erreur_sql = __( 'Oups ! Un problème a été rencontré.','felix' );
    //si on vient de la modification d'item
    if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
        if ( isset( $_GET['id'] ) ) {
            $id = $_GET['id'];
            echo felix_creer_page_edition($id);
            $afficher_liste = false;
        }
        //on reagit en cas de problème
        else {
            echo '<div class="notice notice-warning"><p>';
            _e( "La provenance du lien d'édition semble poser problème.", "felix" );
            echo '</p></div>';
        }
    }
    else{
        //S'il n'y a aucun erreur SQL on peut construire le tableau pour l'afficher
        if ( $erreur_sql == "" ) {
            if ( $wpdb->num_rows > 0 ) {
                //on boucle pour chaque rangée
                foreach( $resultat as $enreg ){
                    //on pousse les données respectives dans leur tableaux
                    array_push($id_tab, $enreg->livre_id);
                    array_push($tab, $enreg->nom_livre);
                    array_push($auteurs, $enreg->auteur);
                }
            } else {
                echo '<div class="messageavertissement">';
                _e( "Aucune donnée ne correspond aux critères demandés.", "felix" );
                echo '</div>';    }
        } else {
            echo '<div class="messageerreur">';
            _e( "Oups ! Un problème a été rencontré.", "felix" );
            echo '</div>';

            // afficher l'erreur à l'écran seulement si on est en mode débogage
            felix_log_debug( $erreur_sql );}

        // on boucle tant et aussi longtemps qu'on a des donnnées a afficher
        for ($i = 0; $i < sizeof($tab); $i++){
            if (($i % 10) === 0){
                $affichage = $affichage . "</tr><tr>";
            }
            //liens pour l'édition des données et la suppression
            $url_edition = admin_url("admin.php?page=felix_gestion&id=$id_tab[$i]&action=edit");
            $url_suppression = get_stylesheet_directory_uri() . "/supprimer-item.php?id=$id_tab[$i]";
            $url_suppression_protege = wp_nonce_url( $url_suppression, "supprimer_item_$id_tab[$i]" );
            //stockage des tables
            $affichage = $affichage . "
        <tr>
        <td class='title column-title has-row-actions column-primary'>
        <strong>$auteurs[$i]</strong>
        <div class='row-actions'>
        <span class='edit'><a href='$url_edition'>
       " . __( 'Modifier le livre', 'felix' ) . "</a></span> 
        <span class='trash'><a href='$url_suppression_protege' class='submitdelete'> 
        " . __( 'Mettre à la corbeille', 'felix' ) . "  </a></span>
        <td class='title column-title has-row-actions column-primary'>$tab[$i]</td>
        </div>
        </td>
        </tr>";

        }
        //code qu'on retourne qui contient tout le code HTML
        $code_html = "<span class='textespecial'><table>
    <thead>
        <tr>
            <th scope='row' class='check-column'>Items</th>
        </tr>
    </thead>
    <tbody>
        
            $affichage
        
    </tbody>
</table></span><br>";
        //on retourne les tables
        return $code_html;
    }
}

/*
 * function permettant d'afficher la page d'édition
 * prend en parametre l'ID de l'identifiant qu'on veut modifier
 * retourne le code html
 */
function felix_creer_page_edition(int $id) {
    //definit l'URL pour traiter la mise a jour
    $url_action = get_stylesheet_directory_uri() . '/mettre-a-jour-item.php';
    global $wpdb;
    $code_html_select ="";

    //definit les tables
    $table_genre = $wpdb->prefix . "felix_genre";
    $table_livre = $wpdb->prefix . "felix_livre";
    // requete  pour selectionner le nom de genre et son id
    $requete = "SELECT genre_id, nom_genre FROM $table_genre ORDER BY nom_genre";
    $resultat = $wpdb->get_results( $requete );
    $code_html_genre = "";

    $array = [];
    // requete pour selectionner le nom de livre et l'auteur avec le parametre dans le WHERE
    $requete_information_livre= "SELECT nom_livre, auteur FROM $table_livre WHERE livre_id = $id";
    $resultat_information_livre = $wpdb->get_results($requete_information_livre);

    $erreur_sql = $wpdb->last_error;

    //si aucun erreur sql, on procède
    if ( $erreur_sql == "" ) {
        if ( $wpdb->num_rows > 0 ) {
                    foreach( $resultat_information_livre as $enreg ){
                    /* on push dans les tableaux */
                    array_push($array, esc_attr($enreg->nom_livre));
                    array_push($array, esc_attr($enreg->auteur));
                }
                    foreach ($resultat as $enreg2 ) {
                    $code_html_genre .= "<option value='$enreg2->genre_id'>$enreg2->nom_genre</option>";
            }
            $code_html_nom = "<input type='text' id='description' name='titre_livre' maxlength='200' value='$array[0]' required/>";
            $code_html_auteur = "<input type='text' id='description' name='nom_auteur' maxlength='200' value='$array[1]' required/>";

        } else {
            $code_html_select .= '<div class="alert alert-danger">';
            $code_html_select .= __( "Aucune catégorie n'a été trouvée", "felix" );
            $code_html_select .= '</div>';
        }
    } else {
        $code_html_select .= '<div class="alert alert_danger">';
        $code_html_select .= __( "Erreur de connexion au système" );
        $code_html_select .= '</div>';
        // écrit l'erreur dans le journal seulement si on est en mode débogage
        felix_log_debug( $erreur_sql );
    }
    // on valide que le nonce est valide
    $nonce = wp_nonce_field( "editer_item_$id", 'modifier_item', true, true );
    // on crée les tables
    $code_html ="
        <form method='post' action='". get_stylesheet_directory_uri(). "/mettre-a-jour-item.php'>
            $nonce
            <input type='hidden' id='id' name='id' value='$id'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label for='text'>" . __( '* Titre du livre', 'felix' ) . "</label>
                        ".  $code_html_nom ."
                    </div>
                </div>
                <!--  col-md-6   -->
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label for='text'>" . __( '* Nom auteur', 'felix' ) . "</label>
                        ".  $code_html_auteur ."
                    </div>
                </div>
                <div class='col-md-12'>
                    <div class='form-group'>
                   <label for='text'>" . __( '* Genre du livre', 'felix' ) . "</label>
                    <select class='form-control' name='liste_genre' required>
                    ".  $code_html_genre ."
                    </div>
                </div>
                </div>
                <input type='submit' name='modification_item' value='Modifier'/>
        </form>
	";
    // on retourne le code html
    return $code_html;
}

/*
 * hook qui envoie un message a l'utilisateur lorsque l'item est modifier correctement
 *utilisation add_action('admin_notices', 'felix_confirmation_modification_item');
 */
function felix_confirmation_modification_item(){
    $valeur_retour = '';
    if ( isset( $_SESSION['felix_mise_a_jour_item_reussie']) ){
        echo '<div class="notice notice-success is-dismissible"><p>';
        _e( 'La modification d\'item s\'est bien effectué','felix' );
        echo '</p></div>';
        unset($_SESSION['felix_mise_a_jour_item_reussie']);
    }
        if ( isset( $_SESSION['felix_erreur_mise_a_jour_item']) ){
             echo '<div class="notice notice-error is-dismissible"><p>';
             $valeur_retour = $_SESSION['felix_erreur_mise_a_jour_item'];
             _e("$valeur_retour",'felix');
             echo '</p></div>';
             unset($_SESSION['felix_erreur_mise_a_jour_item']);
    }
}
add_action( 'admin_notices', 'felix_confirmation_modification_item' );







/**
 * Ajoute l'option principale du menu pour gérer le thème dans le tableau de bord.
 *
 * Utilisation : add_action( "admin_menu", "felix_ajouter_menu_tableau_de_bord" );
 *
 * @author Félix michaud
 *
 */
function felix_ajouter_menu_tableau_de_bord() {

        global $felix_hook_gestion;
        add_menu_page(
            __( "Felix - Gestion", "felix" ),
            __( "Felix - Gestion", "felix" ),
            "manage_options",
            "felix_gestion",
            "felix_liste_item_tableau_bord",
        );
        // seconde option du sous-menu
        $felix_hook_gestion = add_submenu_page(
            "felix_gestion",   // slug du menu parent
            __( "Felix -  gestion", "felix" ),  // texte de la balise <title>, initialisera $title
            __( "Ajouter un livre", "felix" ),   // titre de l'option de sous-menu
            "manage_options",  // droits requis pour voir l'option de menu
            "felix_ajout", // slug
            "felix_creer_page_ajout"  // fonction de rappel pour créer la page
        );
}
add_action( "admin_menu", "felix_ajouter_menu_tableau_de_bord" );


// affiche les items coté tableau de bord
function felix_liste_item_tableau_bord(){
    global $title;   // titre de la page du menu, tel qu'initialisé dans la fonction de rappel de add_menu_page
    echo'';

    // chaîne au format https://mondomaine.com/wp-admin/admin.php?page=monprefixe_ajout
    $url_ajout = admin_url( "admin.php?page=felix_ajout" );
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
        <a href="<?php echo $url_ajout; ?>" class="page-title-action"><?php _e( "Ajouter livre", "felix" ) ?></a>
        <hr class="wp-header-end">
    <?php
    ?>
        <?php
        echo felix_creer_page_gestion();
        ?>
        <hr class="wp-header-end">
    </div>
    <?php
}


function felix_creer_page_ajout(){
    global $title;   // titre de la page du menu, tel qu'initialisé dans la fonction de rappel de add_menu_page
    ?>
    <div class="wrap">
        <h1 class="wp-header-head"><?php echo $title; ?></h1>
        <hr class="wp-header-end">
        <?php
        echo felix_afficher_formulaire_ajout();
        ?>
    </div>
    <?php
}

/* afficher le formulaire d'ajout de données
*
* retourne le code HTML du formulaire
*/
function felix_afficher_formulaire_ajout() {
    global $wpdb;
    $code_html_select = "";
    //declaration de la table
    $table_livre = $wpdb->prefix . "felix_genre";
    //on va cherche l'id du genre et le nom du genre dans la BD
    $requete = "SELECT genre_id, nom_genre FROM $table_livre ORDER BY nom_genre";
    $resultat = $wpdb->get_results( $requete );
    $erreur_sql = $wpdb->last_error;

    //s'il n'y a aucun erreur SQL, on traite
    if ( $erreur_sql == "" ) {
        if ( $wpdb->num_rows > 0 ) {
            $code_html_select .= "<select class='form-control' name='liste_genre' required>";
            // on boucle pour afficher le <option avec les données de la catégorie>
            foreach ( $resultat as $enreg ) {
                $code_html_select .= "
					<option value='$enreg->genre_id'>$enreg->nom_genre</option>
				";
            }
            $code_html_select .= '</select>';
        } else {
            $code_html_select .= '<div class="alert alert-danger">';
            $code_html_select .= __( "Aucune catégorie n'a été trouvée", "felix" );
            $code_html_select .= '</div>';
        }
    } else {
        $code_html_select .= '<div class="alert alert_danger">';
        $code_html_select .= __( "Erreur de connexion au système" );
        $code_html_select .= '</div>';
        // écrit l'erreur dans le journal seulement si on est en mode débogage
        felix_log_debug( $erreur_sql );
    }
    // on initialise le NONCE
    $nonce = wp_nonce_field( 'ajout_livre', 'ajout_livre', true, false );
    // on crée le formulaire d'ajout d'un livre
    $code_html = "
        <form method='post' action='". get_stylesheet_directory_uri(). "/enregistrer-item.php'>
            $nonce
            <div class='row'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label for='titre'>" . __( '* Titre du livre', 'felix' ). "</label>
                        <input type='text' class='form-control' id='titre' name='titre_livre' maxlength='50' required>
                    </div>
                </div>
                <!--  col-md-6   -->
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label for='text'>" . __( '* Nom auteur', 'felix' ) . "</label>
                        <input type='text' class='form-control' id='text' name='nom_auteur' maxlength='50' required>
                    </div>
                </div>
                <div class='col-md-12'>
                    <div class='form-group'>
                    <label for='categorie'>" . __( '* Genre', 'felix' ) . "</label>
                    " . $code_html_select ."
                    </div>
                    </div>
                </div>
            </div>
            <button type='submit' name='envoyer-item'>" . __( 'Envoyer', 'felix' ) . "</button>
        </form>
	";
    // on le retourne
    return $code_html;
}


/**
 * Bannière sur mon site, si je suis en développement
 *
 * Utilisation : add_action( 'loop_start', 'felix_avertir_developpement' );
 *
 * @author Felix Michaud
 *
 */
function felix_avertir_developpement( $array ) {
    if(WP_DEBUG == true){
        echo "<div class='messagegeneral'>" . __( 'Attention: le site est en développement', 'felix' ) . "</div>";
    }
};

add_action( 'loop_start', 'felix_avertir_developpement' );


/**
 * permet de print un formulaire
 * @return html le code HTML pour imprimer les données prises dans la BD
 * utilisation add_shortcode('felixshortcodeform', 'felix_shortcode_ajouter_formulaire_page_contact');
 */
function felix_shortcode_ajouter_formulaire_page_contact(){
    echo'
    <form method="post" action="' . get_stylesheet_directory_uri() . '/envoyer-courriel-contact.php">
  <div class="elem-group">
    <label for="name">Votre nom</label>
    <input type="text" id="name" name="nom_visiteur" placeholder="John Doe" pattern=[A-Z\sa-z]{3,20} required>
  </div>
  <div class="elem-group">
    <label for="email">Votre adresse courriel</label>
    <input type="email" id="email" name="courriel_visiteur" placeholder="john.doe@email.com" required>
  </div>    
    <label for="message">Écrivez votre message</label>
    <textarea maxlength="2000" id="message" name="message_visiteur"  required></textarea>
  </div>
  <button type="submit" name="envoyer-courriel">Send Message</button>
</form>';
}

add_shortcode( 'felixshortcodeform', 'felix_shortcode_ajouter_formulaire_page_contact' );


/**
 * Active les variables de session.
 *
 * Utilisation : add_action( 'init', 'felix_session_start', 1 );
 *
 * @author Felix Michaud
 *
 */
function felix_session_start() {
    if ( ! session_id() ) {
        @session_start();
    }
}
add_action( 'init', 'felix_session_start', 1 );


/**
 * @return string
 * Shortcode qui envoie une confirmation a l'utilisateur lors de l'envoie d'un message dans le formulaire contact
 * Utilisation: add_shortcode('felix_shortcode_email_confirmer', 'felix_confirmation_envoiecourriel');
 */
function felix_confirmation_envoiecourriel(){
    $valeur_retour = '';
    if ( isset( $_SESSION['envoyer-courriel'] ) ){
        $valeur_retour = "<div class='isa_success'><strong>". __('L\'envoie du courriel s\'est bien effectué','felix') ." </strong></div>";
        unset($_SESSION['envoyer-courriel']);
    }
        if ( isset( $_SESSION[''] ) ) {
             $valeur_retour ='';
    }
    return $valeur_retour;
};
add_shortcode( 'felix_shortcode_email_confirmer', 'felix_confirmation_envoiecourriel' );



/**
 * @return string
 * Shortcode qui envoie une confirmation a l'utilisateur lors de l'envoie d'un message dans le formulaire inscription
 * Utilisation: add_shortcode('felix_confirmation_envoieinscription', 'felix_confirmation_envoieinscription');
 */
function felix_confirmation_envoieinscription(){
    $valeur_retour = '';
    if ( isset( $_SESSION['succesformulaireajoutinscription'] ) ){
        $valeur_retour = "<div class='isa_success'><strong>". __('L\'envoie de l\'inscription s\'est bien effectué','felix') ." </strong></div>";
        unset($_SESSION['succesformulaireajoutinscription']);
    }
    if ( isset( $_SESSION['succesformulaireajoutinscription'] ) ) {
        $valeur_retour ='';
    }
    return $valeur_retour;
};
add_shortcode( 'felix_confirmation_envoieinscription', 'felix_confirmation_envoieinscription' );


/**
 * Configurer l'envoi de courriel par SMTP.
 *
 * Utilisation : add_action( 'phpmailer_init', 'monprefixe_configurer_courriel' );
 * L'envoi de courriel ser fera comme suit :
 * wp_mail( "destinataire@sondomaine.com", "Sujet", "Message" );
 *
 * @author Felix Michaud
 *
 */
function felix_configurer_courriel( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host = SMTP_HOST;
    $phpmailer->SMTPAuth = SMTP_AUTH;
    $phpmailer->Port = SMTP_PORT;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->Username = SMTP_USERNAME;
    $phpmailer->Password = SMTP_PASSWORD;
    $phpmailer->From = SMTP_FROM;
    $phpmailer->FromName = SMTP_FROMNAME;
}

add_action( 'phpmailer_init', 'felix_configurer_courriel' );

/**
 * Affiche un message indiquant que l'item a été ajouté avec succès, seulement si la variable de session existe.
 *
 * Utilisation : add_action( 'admin_notices', 'monprefixe_message_ajout_item_reussi' );
 *
 * @author Felix Michaud
 *
 */
function felix_message_ajout_item_reussi() {
    if ( isset( $_SESSION['felix_ajout_reussi'] ) && $_SESSION['felix_ajout_reussi'] == true ) {

        echo '<div class="notice notice-success is-dismissable"><p>';
        _e( "L'item a été ajouté avec succès !", "felix" );
        echo '</p></div>';

        // supprime la variable de session pour ne pas que le message soit affiché à nouveau
        $_SESSION['felix_ajout_reussi'] = null;
    }
}

add_action( 'admin_notices', 'felix_message_ajout_item_reussi' );


/**
* Charge des scripts et des feuilles de style dans toutes les pages du tableau de bord.
*
* Utilisation : add_action('admin_enqueue_scripts', 'felix_charger_css_js_admin');
*
* @author Felix Michaud
*
*/
function felix_charger_css_js_admin( $hook ) {

    global $felix_hook_gestion; // variable initialisée lors de l'ajout de l'option de menu
    felix_log_debug($hook);
    // fichiers à charger seulement pour la page de gestion de mon thème

        felix_log_debug( "test" );
        wp_enqueue_style( 'ma-bibliotheque-popup', get_stylesheet_directory_uri() . '/ma-bibliotheque-popup/style.css' );
        wp_enqueue_script( 'ma-bibliotheque-popup', get_stylesheet_directory_uri() . '/ma-bibliotheque-popup/script.js', null, null, true );
        wp_enqueue_script( 'confirmation-suppression', get_stylesheet_directory_uri() . '/ma-bibliotheque-popup/confirmation-suppression.js', array( 'ma-bibliotheque-popup' ), null, true );

}

add_action( 'admin_enqueue_scripts', 'felix_charger_css_js_admin' );


/**
 * @return string
 * shortcode pour ajouter mon formulaire d'inscription
 * add_shortcode('felixshortcodeinscription', 'felix_shortcode_ajouter_formulaire_inscription');
 */
function felix_shortcode_ajouter_formulaire_inscription(){

    global $wpdb;
    //initialisation des tables
    $table_province = $wpdb->prefix . "felix_province";

    //on va cherche l'ID province et son nom dans la table province
    $requete2= "SELECT id_province, nom_province FROM $table_province ORDER BY nom_province";

    //traitement de ma requete
    $resultat2 = $wpdb->get_results( $requete2 );

    $code_html_province="";
    //pour chaque resultat on crée un option
    foreach ($resultat2 as $enreg1 ) {
                    $code_html_province .= "<option value='$enreg1->id_province'>$enreg1->nom_province</option>";
    }

    $code_html = "
    <form method='post' action='". get_stylesheet_directory_uri(). "/ajouter-inscription.php'>
    <div class='elem-group'>
    <label for='name'>Nom de famille</label>
    <input type='text' id='name' name='nom_famille' placeholder='Doe'required>
    </div>
    <div class='elem-grou'>
    <label for='name'>Prenom</label>
    <input type='text' id='name' name='prenom' placeholder='John' required>
    </div>
    <div class='elem-group'>
    <label for='text'>" . __( 'Province', 'felix' ) . "</label>
    <select class='form-control' id='boutonAppelAjax' name='liste_province' required>
    ".  $code_html_province ."
    </div>
    <div class='elem-group'>
    <label for='name'>Adresse</label>
    <input type='text' id='name' name='adresse' placeholder='125 morency' required>
    </div>

    <div class='elem-group'>
    <label for='text'>" . __( 'Ville', 'felix' ) . "</label>
    <select class='form-control' id='reponseAjax' name='liste_ville' required>
    </div>
    <div class='elem-group'>
    <label for='email'>Votre adresse courriel</label>
    <input type='email' id='email' name='courriel' placeholder='john.doe@email.com' required>
    </div> 
    <div class='elem-group'>
     <label for='name'>Téléphone</label>
    <input type='text' id='name' name='telephone' placeholder='' required>
    </div>
    <button class='elem-group' type=
    submit' name='envoyer-inscription'>Envoyer l'inscription</button>
    </form>
  ";
    //on retourne l'html qui crée les tables
return $code_html;
}

add_shortcode( 'felixshortcodeinscription', 'felix_shortcode_ajouter_formulaire_inscription' );


/**

 * Charge les scripts et feuilles de style propres au thème.

 *

 * Utilisation : add_action('wp_enqueue_scripts', 'felix_charger_css_js_web');

 *

 * @author Felix Michaud

 *

 */

function felix_charger_css_js_web() {
    global $post;

    // charge les fichiers seulement si le shortcode est utilisé sur la page
    //$post != null && has_shortcode --> permet d'éviter la notice:  Trying to get property of non-object
    if ( $post != null && has_shortcode( $post->post_content, 'felixshortcodeinscription' ) ) {

        wp_enqueue_script( 'felix_shortcode_ajouter_formulaire_inscription', get_stylesheet_directory_uri() . '/js/appel-ajax.js', null, null, true );
        wp_localize_script( 'felix_shortcode_ajouter_formulaire_inscription', 'variablesPHP', [
            'urlThemeEnfant' => get_stylesheet_directory_uri()
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'felix_charger_css_js_web' );


/**
 * @return string
 * Shortcode pour afficher la photo d'un chat VIA l'API: 'https://api.thecatapi.com
 * UTILISATION: add_shortcode('felix_shortcode_chat', 'felix_shortcode_chat');
 */
function felix_shortcode_chat() {
    $code_html = '';
    $url = 'https://api.thecatapi.com/v1/images/search?api_key=73415969-d905-412a-994a-5d1aa7436c90';

    // appel du service Web
    $response = wp_remote_get( $url );

    // réagit en cas d'erreur
    if ( is_wp_error( $response ) ) {
        $message = __( 'Un problème nous empêche d\'afficher l\'image.', 'felix' );
        $code_html .= "<div class='messageerreur'>$message</div>";
        monprefixe_log_debug( $response->get_error_message() );
    } else {
        // traite la réponse
        $body = json_decode( wp_remote_retrieve_body( $response ) );
        //monprefixe_log_debug( $body );

        if ( $response['response']['code'] != 200 ) {
            $code_html .= "<div class='messageerreur'>" . __( "Un problème nous empêche d'afficher l'image.", "felix" ) . "</div>";
            monprefixe_log_debug( $body->message );   // ce service Web est conçu pour retourner directement un objet avec le message d'erreur lorsqu'il ne peut pas retrouver l'image
        } else {
            $url_image = $body[0]->url;
            $code_html .= "<img class='imagearticle' src='$url_image' alt='chat'>";
        }
    }
    // retourne le chat :)
    return $code_html;
}

add_shortcode( 'felix_shortcode_chat', 'felix_shortcode_chat' );


/**
 * @return string
 * Shortcode pour afficher des blagues de chuck norris VIA l'API : http://api.icndb.com
 * Utilisation: add_shortcode('felix_shortcode_chuck', 'felix_shortcode_chuck'
 */
function felix_shortcode_chuck() {
    $code_html = '';
    $url = 'http://api.icndb.com/jokes/random';

    // appel du service Web
    $response = wp_remote_get( $url );

    // réagit en cas d'erreur
    if ( is_wp_error( $response ) ) {
        $message = __( 'Un problème nous empêche d\'afficher l\'image.', 'felix' );
        $code_html .= "<div class='messageerreur'>$message</div>";
        monprefixe_log_debug( $response->get_error_message() );
    } else {
        // traite la réponse
        $body = json_decode( wp_remote_retrieve_body( $response ) );
        //felix_log_debug( $body );

        if ( $response['response']['code'] != 200 ) {
            $code_html .= "<div class='messageerreur'>" . __( "Un problème nous empêche d'afficher l'image.", "Fekux" ) . "</div>";
            monprefixe_log_debug( $body->message );   // ce service Web est conçu pour retourner directement un objet avec le message d'erreur
        } else {
            $joke = $body->value->joke;
            $code_html .= "<p> $joke </p>";
        }
    }
    // on retourne les blagues de chuck HA HA
    return $code_html;
}

add_shortcode( 'felix_shortcode_chuck', 'felix_shortcode_chuck' );


/**
 * Définit les options du thème, les sections de la page de personnalisation et les contrôles de saisie.
 *
 * Utilisation : add_action('customize_register', 'monprefixe_customize');s
 *
 * @author Felix Michaud
 *
 */
/**
 * Définit les options du thème, les sections de la page de personnalisation et les contrôles de saisie.
 *
 * Utilisation : add_action('customize_register', 'felix_customize');s
 *
 * @author felix Bélisle
 *
 */
/**
 * Définit les options du thème, les sections de la page de personnalisation et les contrôles de saisie.
 *
 * Utilisation : add_action('customize_register', 'felix_customize');
 *
 * @author Felix michaud
 *
 */
function felix_customize( $wp_customize ) {
    // crée une nouvelle section dans la page de personnalisation
    $wp_customize->add_section(
        'felix_options', array(
        'title'    => __( 'Options du thème enfant', 'felix' ),
        'priority' => 15,  // indique son emplacement parmi les sections existantes
    ) );

    // définit une option
    $wp_customize->add_setting(
        'banniere', array(
        'default' => '',
    ) );

    // dans la section, ajoute un contrôle pour saisir la valeur de l'option
    $wp_customize->add_control(
        'banniere', array(
        'label'   => __( 'Libellé', 'felix' ),
        'section' => 'felix_options',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'felix_customize' );

/**
 * Avertir l'usager qu'une maintenance du site est prévue prochainement.
 *
 * Utilisation : add_action( 'loop_start', 'felix_avertir_maintenance' );
 *
 * @author Felix Michaud
 *
 */
function felix_avertir_maintenance( $array ) {

    $valeur = get_theme_mod( 'banniere', '' );


    if ( $valeur != "" ) {
        // on pourrait aussi travailler avec la base de données pour savoir quand un message doit être affiché ou non et pour retrouver le message à afficher.
        echo '<div class="alert alert-success" role="alert"><p><strong>' . __( $valeur , 'felix' ) . '</strong></p></div>';
    }
}


add_action( 'wp_body_open', 'felix_avertir_maintenance' );