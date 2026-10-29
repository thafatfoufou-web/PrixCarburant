<?php
/**
 * Fichier de configuration principal.
 * Il initialise les préférences de l'utilisateur (langue et mode d'affichage) 
 * et s'assure qu'elles sont conservées d'une page à l'autre.
 * @author RAHARIMANANA Tianantenaina BOUKIRAT Thafat
 */

    require_once("helpers.inc.php");

    /**
     * Gestion du thème (Clair ou Sombre).
     * On vérifie si l'utilisateur vient de cliquer sur un bouton de changement de mode,
     * sinon on regarde s'il y a un choix déjà enregistré dans les cookies.
     */
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
        // Valider que le mode est valide
        if (in_array($mode, ['jour', 'nuit'])) {
            // Obtenir le chemin du répertoire de l'application
            $script_path = dirname($_SERVER['SCRIPT_NAME']);
            $path = rtrim($script_path, '/') . '/';
            setcookie('mode', $mode, time() + (86400 * 30), $path);
            
            // Rediriger pour enlever le paramètre de l'URL
            $redirect_params = $_GET;
            unset($redirect_params['mode']);
            $redirect_url = $_SERVER['PHP_SELF'];
            if (!empty($redirect_params)) {
                $redirect_url .= '?' . http_build_query($redirect_params);
            }
            header("Location: $redirect_url");
            exit;
        } else {
            $mode = 'jour'; // Valeur par défaut si invalide
        }
    } elseif (isset($_COOKIE['mode'])) {
        $mode = $_COOKIE['mode'];
        // Valider le cookie existant
        if (!in_array($mode, ['jour', 'nuit'])) {
            $mode = 'jour';
            // Supprimer le cookie invalide
            $script_path = dirname($_SERVER['SCRIPT_NAME']);
            $path = rtrim($script_path, '/') . '/';
            setcookie('mode', '', time() - 3600, $path);
        }
    } else {
        $mode = 'jour'; 
    }

    /**
     * Gestion de la langue (Français ou Anglais).
     * Le choix est mémorisé pour offrir une expérience cohérente durant toute la visite.
     */
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        // Valider que la langue est valide
        if (in_array($lang, ['fr', 'en'])) {
            // Obtenir le chemin du répertoire de l'application
            $script_path = dirname($_SERVER['SCRIPT_NAME']);
            $path = rtrim($script_path, '/') . '/';
            setcookie('lang', $lang, time() + (86400 * 30), $path);
            
            // Rediriger pour enlever le paramètre de l'URL
            $redirect_params = $_GET;
            unset($redirect_params['lang']);
            $redirect_url = $_SERVER['PHP_SELF'];
            if (!empty($redirect_params)) {
                $redirect_url .= '?' . http_build_query($redirect_params);
            }
            header("Location: $redirect_url");
            exit;
        } else {
            $lang = 'fr'; // Valeur par défaut si invalide
        }
    } elseif (isset($_COOKIE['lang'])) {
        $lang = $_COOKIE['lang'];
        // Valider le cookie existant
        if (!in_array($lang, ['fr', 'en'])) {
            $lang = 'fr';
            // Supprimer le cookie invalide
            $script_path = dirname($_SERVER['SCRIPT_NAME']);
            $path = rtrim($script_path, '/') . '/';
            setcookie('lang', '', time() - 3600, $path);
        }
    } else {
        $lang = 'fr'; 
    }

    // Définition des chemins vers les feuilles de style CSS.
    $css_mode_jour = "../styles/styles.css";
    $css_mode_nuit = "../styles/styles_nuit.css";
?>
