<?php
/**
 * Fonctions utilitaires
 *
 * @author RAHARIMANANA Tianantenaina
 * @author BOUKIRAT Thafat
 * @version 1.0
 */
/**
 * Lecture de fichier csv
 * @param $file_path Chemin vers le fichier
 * @param $skip_header Header
 * @param $delimiter Delimiter (, par défaut)
 * @return $data données recceuillies
 */
function read_csv($file_path, $skip_header = true, $delimiter = ',') {
    $data = [];
    if (($handle = fopen($file_path, "r")) !== false) {
        if ($skip_header) {
            fgetcsv($handle, null, $delimiter, '"', '\\');
        }
        while (($row = fgetcsv($handle, null, $delimiter, '"', '\\')) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

/**
 * Ecriture dans un fichier csv
 * @param $file_path Chemin vers le fichier
 * @param $row Colonne à insérer
 * @param $header entête
 * @return bool succès ou pas
 *
 * @author RAHARIMANANA Tianantenaina
 */
function append_to_csv($file_path, $row, $header = null, $delimiter = ',') {
    $is_new = !file_exists($file_path);
    if (($handle = fopen($file_path, "a")) !== false) {
        if ($is_new && $header !== null) {
            fputcsv($handle, $header, $delimiter, '"', '\\');
        }
        fputcsv($handle, $row, $delimiter, '"', '\\');
        fclose($handle);
        return true;
    }
    return false;
}

/**
 * Ajout de cookie
 */
function set_persistent_cookie($name, $value, $days = 30, $path = "/") {
    setcookie($name, $value, time() + (86400 * $days), $path);
}

/**
 * Traduction
 */
function t($lang, $fr, $en = '') {
    return ($lang === 'en') ? ($en ?: $fr) : $fr;
}
/**
 * Chemin de base
 */
function get_base_path() {
    $current_file = $_SERVER['PHP_SELF'];
    return (strpos($current_file, '/pages/') !== false) ? '../' : '';
}

/**
 * Chemin sur laquelle on se trouve
 */
function get_current_page() {
    $current_file = $_SERVER['PHP_SELF'];
    return basename($current_file, '.php');
}

/**
 * Extraction des régions
 */
function load_regions($whitelist = null) {
    $regions = [];
    $rows = read_csv("data/regions.csv", true);
    foreach ($rows as $data) {
        $regions[$data[0]] = $data[1];
    }
    
    if ($whitelist) {
        $regions = array_intersect_key($regions, array_flip($whitelist));
    }
    
    return $regions;
}

/**
 * Extraction des départements
 */
function load_departments() {
    $departments = [];
    $rows = read_csv("data/departments.csv", true);
    foreach ($rows as $data) {
        $departments[$data[0]] = [
            'region' => $data[1],
            'name'   => $data[2]
        ];
    }
    return $departments;
}

/**
 * Charge les codes postaux et coordonnées GPS par département
 * */
function load_postcodes() {
    $postcodes_by_dept = [];
    $rows = read_csv("data/clean_postcodes.csv", true);
    
    foreach ($rows as $data) {
        $insee = $data[0];
        $nom   = $data[1];
        $cp    = $data[2];
        $lat   = $data[3];
        $lng   = $data[4];
        
        if (preg_match('/^(2[AB])/i', $insee, $m)) {
            $dept = strtoupper($m[1]); 
        } else {
            $dept = substr($insee, 0, 2);
        }
        
        if (!isset($postcodes_by_dept[$dept])) {
            $postcodes_by_dept[$dept] = [];
        }
        
        if (!isset($postcodes_by_dept[$dept][$nom])) {
            $postcodes_by_dept[$dept][$nom] = [
                'cp'  => $cp,
                'lat' => (float)$lat,
                'lng' => (float)$lng,
            ];
        }
    }
    
    return $postcodes_by_dept;
}

/**
 *  Trouve la ville la plus proche avec des stations dans un rayon donné
 * */
function find_closest_city_with_stations($target_city, $postcodes_by_dept, $max_search_radius = 50) {
    if (city_has_stations($target_city)) {
        return ['city' => $target_city, 'dept' => null, 'distance' => 0];
    }
    
    $target_coords = null;
    $target_dept = null;
    
    foreach ($postcodes_by_dept as $dept_code => $cities) {
        foreach ($cities as $city_name => $city_info) {
            if (strtolower($city_name) === strtolower($target_city)) {
                $target_coords = [
                    'lat' => $city_info['lat'],
                    'lng' => $city_info['lng']
                ];
                $target_dept = $dept_code;
                break 2;
            }
        }
    }
    
    if (!$target_coords) {
        return null;
    }
    
    $closest_city = null;
    $min_distance = $max_search_radius;
    
    foreach ($postcodes_by_dept as $dept_code => $cities) {
        foreach ($cities as $city_name => $city_info) {
            if (strtolower($city_name) === strtolower($target_city)) {
                continue;
            }
            
            $distance = calculate_distance(
                $target_coords['lat'], $target_coords['lng'],
                $city_info['lat'], $city_info['lng']
            );
            
            if ($distance <= $max_search_radius && $distance < $min_distance) {
                if (city_has_stations($city_name)) {
                    $closest_city = [
                        'city' => $city_name,
                        'dept' => $dept_code,
                        'distance' => $distance
                    ];
                    $min_distance = $distance;
                }
            }
        }
    }
    
    return $closest_city;
}

/**
 *  Vérifie si la ville a des stations
 * */
function city_has_stations($city_name) {
    $city_upper = strtoupper($city_name);
    $api_url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records"
             . "?where=ville%20LIKE%20%22" . urlencode($city_upper) . "%22"
             . "&limit=1";
    
    $response = @file_get_contents($api_url);
    if ($response) {
        $data = json_decode($response, true);
        return isset($data['results']) && count($data['results']) > 0;
    }
    
    return false;
}

// Calcule la distance en km entre deux points GPS avec la formule de Haversine
function calculate_distance($lat1, $lng1, $lat2, $lng2) {
    $earth_radius = 6371; 
    
    $lat_diff = deg2rad($lat2 - $lat1); // Convertit les degrés en radians
    $lng_diff = deg2rad($lng2 - $lng1);
    
    $a = sin($lat_diff / 2) * sin($lat_diff / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lng_diff / 2) * sin($lng_diff / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    return $earth_radius * $c;
}
?>
