<?php
    // Récupération de la géolocalisation à l'aide de l'adresse IP
    // HTTP_X_FORWARDED_FOR si derrière un proxy 
    // REMOTE_ADDR = IP directe sinon
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

    if ($client_ip === '127.0.0.1' || $client_ip === '::1') {
        $geo_data = null;
    } else {
        $geo_api_url = "http://ip-api.com/json/{$client_ip}";
        $api_response = @file_get_contents($geo_api_url);

        if ($api_response) {
            $geo_raw_data = json_decode($api_response, true);
            $geo_data = [
                'city'    => $geo_raw_data['city']    ?? 'Inconnue',
                'region'  => $geo_raw_data['regionName']  ?? '',
                'country' => $geo_raw_data['country'] ?? '',
                'coordinates' => ($geo_raw_data['lat'] ?? '') . ',' . ($geo_raw_data['lon'] ?? ''),
            ];
        } else {
            $geo_data = null;
        }
    }
    // Si aucune ville n'est déjà sélectionnée et la géolocalisation a trouvé une ville valide
    if (empty($town) && !empty($geo_data['city']) && $geo_data['city'] !== 'Inconnue') {
        // On prend la ville détectée    
        $town = $geo_data['city'];
    }
    
    $geo = $geo_data;
    $ip = $client_ip;
?>
