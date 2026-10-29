<?php
/**
 * Gestion du prix d'essence
 *
 * @author RAHARIMANANA Tianantenaina
 * @author BOUKIRAT Thafat
 * @version 1.0
 */
    require("server/location_api.php");
    require("include/config.inc.php");

    function parse_station_data($s) {
        global $lang;
        
        $is_open = false;
        $hours_text = '';
        $hours_full = '';
        $horaires_raw = $s['horaires'] ?? '';
        $automate_24_24 = $s['horaires_automate_24_24'] ?? 'Non';
        
        if ($automate_24_24 === 'Oui') {
            $is_open = true;
            $hours_text = 'Automate 24/24';
            $hours_full = 'Automate 24/24';
        } elseif (!empty($horaires_raw)) {
            $horaires_data = json_decode($horaires_raw, true);
            if ($horaires_data && isset($horaires_data['jour'])) {
                $current_day = (int)date('N');
                
                foreach ($horaires_data['jour'] as $jour) {
                    if (isset($jour['@id']) && (int)$jour['@id'] === $current_day) {
                        if (empty($jour['@ferme'])) {
                            $is_open = true;
                            if (isset($jour['horaire'])) {
                                if (isset($jour['horaire']['@ouverture'])) {
                                    $hours_text = $jour['horaire']['@ouverture'] . ' - ' . $jour['horaire']['@fermeture'];
                                } elseif (is_array($jour['horaire'])) {
                                    $slots = [];
                                    foreach ($jour['horaire'] as $slot) {
                                        if (isset($slot['@ouverture']) && isset($slot['@fermeture'])) {
                                            $slots[] = $slot['@ouverture'] . '-' . $slot['@fermeture'];
                                        }
                                    }
                                    $hours_text = implode(', ', $slots);
                                }
                            }
                        } else {
                            $is_open = false;
                            $hours_text = ($lang === 'en') ? 'Closed today' : 'Fermé aujourd\'hui';
                        }
                        break;
                    }
                }
            }
        }
        if (empty($hours_text) && !empty($s['horaires_jour'])) {
            $horaires_jour = $s['horaires_jour'];
            $hours_full = str_replace(', ', "\n", $horaires_jour);
            
            $days_fr = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            $current_day_index = (int)date('N') - 1;
            $today_name = $days_fr[$current_day_index];
            
            if (preg_match('/' . $today_name . '\s*([0-9:.]+(?:\s*-\s*[0-9:.]+)?(?:\s*et\s*[0-9:.]+\s*-\s*[0-9:.]+)*)/', $horaires_jour, $matches)) {
                $hours_text = $matches[1];
                $is_open = true;
            } else {
                $hours_text = ($lang === 'en') ? 'See full schedule' : 'Voir horaires complets';
                $is_open = true;
            }
        }
        
        if (empty($hours_full)) {
            $hours_full = $hours_text;
        }
        
        if (empty($hours_text)) {
            $hours_text = ($lang === 'en') ? 'Hours unavailable' : 'Horaires non disponibles';
            $hours_full = $hours_text;
            $is_open = false;
        }
        
        return [
            'nom'     => $s['id']       ?? 'N/A',
            'adresse' => $s['adresse']  ?? '',
            'commune' => $s['ville']    ?? '',
            'prix_gazole' => $s['gazole_prix'] ?? null,
            'prix_sp95'   => $s['sp95_prix']   ?? null,
            'prix_sp98'   => $s['sp98_prix']   ?? null,
            'prix_e10'    => $s['e10_prix']    ?? null,
            'prix_gplc'   => $s['gplc_prix']   ?? null,
            'lat'     => $s['geom']['lat'] ?? null,
            'lng'     => $s['geom']['lon'] ?? null,
            'is_open' => $is_open,
            'hours_text' => $hours_text,
            'hours_full' => $hours_full,
            'maj'     => $s['gazole_maj'] ?? $s['sp95_maj'] ?? $s['sp98_maj'] ?? $s['e10_maj'] ?? null,
        ];
    }

    $region_code = $_GET['region'] ?? '';
    $dept_code   = $_GET['dept']   ?? '';
    $town        = $_GET['town']   ?? '';
    $action      = $_GET['action'] ?? '';

    // Vérifier s'il s'agit d'une réinitialisation manuelle (l'utilisateur a cliqué sur le bouton de réinitialisation)
    $is_manual_reset = empty($_GET) && isset($_SERVER['HTTP_REFERER']) && 
                       strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false;

    // Logique d'affichage progressif du formulaire
    $show_dept = false;
    $show_town = false;

    if ($action === 'change_region') {
        // L'utilisateur a cliqué sur "Modifier" pour la région - rediriger vers une URL propre (sans paramètres)
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    } elseif ($action === 'update_dept') {
        // L'utilisateur a cliqué sur "Continuer" pour la région - afficher la sélection du département
        $show_dept = true;
        $show_town = false;
        $dept_code = '';
        $town = '';
    } elseif ($action === 'change_dept') {
        // L'utilisateur a cliqué sur "Modifier" pour le département - afficher la sélection du département
        $show_dept = true;
        $show_town = false;
        $dept_code = '';
        $town = '';
    } elseif ($action === 'update_town' || $action === 'change_town') {
        // L'utilisateur a cliqué sur "Continuer" pour le département ou "Modifier" pour la ville - afficher la sélection de la ville
        $show_dept = true;
        $show_town = true;
        $town = '';
    } elseif (!empty($region_code)) {
        // La région est sélectionnée - afficher le département
        $show_dept = true;
        if (!empty($dept_code)) {
            // Le département est sélectionné - afficher la ville
            $show_town = true;
        }
    }

    $search_clicked = isset($_GET['search']);
    $geo_suggestion_used = false;
    $auto_detected = false;
    $manual_interaction = !empty($region_code) || !empty($dept_code) || !empty($_GET['action']);

    $map_region_codes = ['84', '27', '53', '24', '94', '44', '32', '11', '28', '75', '76', '52', '93'];
    $regions = load_regions($map_region_codes);
    $departments = load_departments();
    $postcodes_by_dept = load_postcodes();

    $geo_city_valid = !empty($geo['city']) && !in_array($geo['city'], ['Inconnue', 'localhost'], true);
    $detected_city = '';
    $fallback_to_nearby = false;
    $fallback_to_dept = false;
    
    // Géolocalisation automatique si aucune interaction manuelle
    if (
        empty($town) &&
        !$manual_interaction &&
        !$search_clicked &&
        !$is_manual_reset &&
        !empty($geo) &&
        $geo_city_valid
    ) {
        $detected_city = ucwords(strtolower($geo['city'])); //Met en majuscule la première lettre
        $geo_suggestion_used = true;
        $auto_detected = true;

        foreach ($postcodes_by_dept as $dept_code_candidate => $cities) {
            foreach ($cities as $city_name => $city_info) {
                if (strtolower($city_name) === strtolower($detected_city)) {
                    $dept_code = $dept_code_candidate;

                    if (isset($departments[$dept_code])) {
                        $region_code = $departments[$dept_code]['region'];
                    }
                    break 2;
                }
            }
        }
        
        if (!empty($dept_code)) {
            $city_upper = strtoupper($detected_city);
            $check_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
                       . "?where=ville%20LIKE%20%22" . urlencode($city_upper) . "%22"
                       . "&limit=1";
            $check_response = @file_get_contents($check_url);
            
            if ($check_response) {
                $check_data = json_decode($check_response, true);
                if (isset($check_data['results']) && count($check_data['results']) > 0) {
                    $town = $detected_city;
                } else {
                    //Retrouver les stations les plus proches
                    $closest = find_closest_city_with_stations($detected_city, $postcodes_by_dept, 50);
                    if ($closest && $closest['city']) {
                        $town = $closest['city'];
                        $fallback_to_nearby = true;
                    } else {
                        $town = '';
                        $fallback_to_dept = true;
                    }
                }
            }
        }
    }

    // Remplir région et département si ville fournie
    if (!empty($town) && (empty($region_code) || empty($dept_code))) {
        $town_normalized = ucwords(strtolower($town));
        //Trouver le département et région à partir de la ville
        foreach ($postcodes_by_dept as $dept_code_candidate => $cities) {
            foreach ($cities as $city_name => $city_info) {
                if (strtolower($city_name) === strtolower($town_normalized)) {
                    if (empty($dept_code)) {
                        $dept_code = $dept_code_candidate;
                    }
                    
                    if (empty($region_code) && isset($departments[$dept_code_candidate])) {
                        $region_code = $departments[$dept_code_candidate]['region'];
                    }
                    
                    $town = $city_name;
                    break 2;
                }
            }
        }
    }

    $explicit_selection = !empty($region_code) || !empty($dept_code);
    $fresh_start = empty($_GET); // Aucun paramètre URL du tout
    
    // Charger depuis les cookies uniquement s'il n'y a pas de sélection explicite, pas de détection automatique, pas de nouveau départ et pas de réinitialisation manuelle
    if (empty($town) && !$auto_detected && !$explicit_selection && !$fresh_start && !$is_manual_reset) {
        $town = ucwords(strtolower($_COOKIE['last_city'] ?? ''));
        if (!empty($town)) {
            $dept_code = $_COOKIE['last_dept'] ?? '';
            $region_code = $_COOKIE['last_region'] ?? '';
        }
    }

    // Mettre à jour les indicateurs d'affichage après la détection automatique et le chargement des cookies
    if (!empty($region_code) && empty($action)) {
        $show_dept = true;
        if (!empty($dept_code)) {
            $show_town = true;
        }
    }

    $stations = [];
    $error = '';
    $search_info = '';

    $region_depts = [];
    if ($region_code) {
        foreach ($departments as $code => $info) {
            if ($info['region'] === $region_code) {
                $region_depts[$code] = $info['name'];
            }
        }
    }

    $dept_cities = [];
    if ($dept_code && isset($postcodes_by_dept[$dept_code])) {
        foreach ($postcodes_by_dept[$dept_code] as $nom => $info) {
            $nom_formatted = ucwords(strtolower($nom));
            $dept_cities[$nom_formatted] = [
                'lat' => (float)$info['lat'],
                'lng' => (float)$info['lng'],
                'cp'  => $info['cp'],
            ];
        }
        ksort($dept_cities);
    }

    // Recherche par département uniquement
    if (empty($town) && !empty($dept_code) && $search_clicked) {
        $api_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
                 . "?where=code_departement%3D%22" . urlencode($dept_code) . "%22"
                 . "&limit=100";
        
        $response = @file_get_contents($api_url);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['results']) && count($data['results']) > 0) {
                foreach ($data['results'] as $s) {
                    $stations[] = parse_station_data($s);
                }
                $dept_name = $region_depts[$dept_code] ?? $dept_code;
                $search_info = ($lang === 'en' ? 'Department ' : 'Département ') . $dept_name;
            }
        }
        
        if (empty($stations)) {
            $error = ($lang === 'en')
                ? 'No station found in this department'
                : 'Aucune station trouvée dans ce département';
        }
    }
    // Recherche par ville avec repli sur villes voisines et département
    elseif (!empty($town)) {
        $town_upper = strtoupper($town);
        
        $api_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
                 . "?where=ville%20LIKE%20%22" . urlencode($town_upper) . "%22"
                 . "&limit=100";
        
        $response = @file_get_contents($api_url);
        
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['results']) && count($data['results']) > 0) {
                foreach ($data['results'] as $s) {
                    $stations[] = parse_station_data($s);
                }
                $search_info = $town;
            }
        }
        
        if (empty($stations) && !empty($dept_code)) {
            $closest = find_closest_city_with_stations($town, $postcodes_by_dept, 50);
            
            if ($closest && $closest['city']) {
                $nearby_city_upper = strtoupper($closest['city']);
                $api_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
                         . "?where=ville%20LIKE%20%22" . urlencode($nearby_city_upper) . "%22"
                         . "&limit=100";
                
                $response = @file_get_contents($api_url);
                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['results']) && count($data['results']) > 0) {
                        foreach ($data['results'] as $s) {
                            $stations[] = parse_station_data($s);
                        }
                        $search_info = $closest['city'] . ' (' . ($lang === 'en' ? 'nearest city, ' : 'ville la plus proche, ') 
                                     . round($closest['distance'], 1) . ' km)';
                    }
                }
            }
        }
        
        if (empty($stations) && !empty($dept_code)) {
            $api_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
                     . "?where=code_departement%3D%22" . urlencode($dept_code) . "%22"
                     . "&limit=100";
            
            $response = @file_get_contents($api_url);
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['results']) && count($data['results']) > 0) {
                    foreach ($data['results'] as $s) {
                        $stations[] = parse_station_data($s);
                    }
                    $dept_name = $region_depts[$dept_code] ?? $dept_code;
                    $search_info = ($lang === 'en' ? 'Department ' : 'Département ') . $dept_name 
                                 . ' (' . ($lang === 'en' ? 'no stations in ' : 'aucune station à ') . $town . ')';
                }
            }
        }

        if (empty($stations)) {
            $error = ($lang === 'en')
                ? 'No station found in this city, nearby cities, or department'
                : 'Aucune station trouvée dans cette ville, les villes voisines ou le département';
        }
    }

    if (!empty($town) || !empty($dept_code)) {
        $log_file = "data/city_log.csv";
        append_to_csv($log_file, [date('Y-m-d H:i:s'), $town, $dept_code], ['datetime', 'city', 'department']);

        set_persistent_cookie('last_city',   $town);
        set_persistent_cookie('last_dept',   $dept_code);
        set_persistent_cookie('last_region', $region_code);
    }
?>
