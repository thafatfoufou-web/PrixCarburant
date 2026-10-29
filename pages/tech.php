<?php

    require_once("../include/config.inc.php");
    require("../server/movie_api.php");
    require("../server/location_api.php");
    
    $title = ($lang === 'en') ? 'API Showcase - Fuel Prices FR' : 'Démonstration API - Prix Carburants';
    
    require_once("../include/header.inc.php");
?>

<section class="page-head">
    <h1 class="page-title"><?= ($lang === 'en') ? 'API Showcase' : 'Démonstration des API' ?></h1>
    <p class="page-subtitle"><?= ($lang === 'en') ? 'Two live examples: Studio Ghibli + IP geolocation.' : 'Deux exemples en direct : Studio Ghibli + géolocalisation IP.' ?></p>
</section>

    <div class="tech-section">
        <h2 class="tech-section-title"><?= ($lang === 'en') ? 'Random Studio Ghibli Film' : 'Film Studio Ghibli Aléatoire' ?></h2>

        <div class="movie-layout">
             <div class="movie-poster-col">
                 <?php if (!empty($movie_poster_url)): ?>
                     <img src="<?= $movie_poster_url ?>" alt="Affiche du film: <?= htmlspecialchars($movie_title) ?>" class="movie-poster"/>
                     <p class="movie-poster-caption"><?= ($lang === 'en') ? 'Official movie poster' : 'Affiche officielle du film' ?></p>
                 <?php endif; ?>
             </div>
             <div class="movie-info-col">
                <h3 class="movie-title"><?= htmlspecialchars($movie_title) ?></h3>
                <p class="movie-original">(<?= $movie_title_original ?>)</p>

                <div class="movie-year">
                    <p class="movie-year-label"><?= ($lang === 'en') ? 'Release Year' : 'Année de Sortie' ?></p>
                    <p class="movie-year-value"><?= $movie_year ?></p>
                </div>

                <p class="movie-description"><?= htmlspecialchars($movie_description) ?></p>
            </div>
        </div>

        <?php if (!empty($movie_banner_url)): ?>
            <img src="<?= $movie_banner_url ?>" alt="Bandeau du film: <?= htmlspecialchars($movie_title) ?>" class="movie-banner"/>
            <p class="movie-poster-caption"><?= ($lang === 'en') ? 'Movie banner' : 'Bandeau du film' ?></p>
        <?php endif; ?>
    </div>

    <div class="tech-section">
        <h2 class="tech-section-title"><?= ($lang === 'en') ? 'Your Location (via IP)' : 'Votre Position (via IP)' ?></h2>

        <?php if (!empty($geo)): ?>
            <div class="geo-info">
                <?php
                $geo_items = [];
                $geo_items[] = ['label' => 'IP Address', 'value' => htmlspecialchars($client_ip)];
                
                if (isset($geo['city'])) {
                    $geo_items[] = ['label' => ($lang === 'en') ? 'City' : 'Ville', 'value' => htmlspecialchars($geo['city'])];
                }
                
                if (isset($geo['region'])) {
                    $geo_items[] = ['label' => ($lang === 'en') ? 'Region' : 'Région', 'value' => htmlspecialchars($geo['region'])];
                }
                
                if (isset($geo['country'])) {
                    $geo_items[] = ['label' => ($lang === 'en') ? 'Country' : 'Pays', 'value' => htmlspecialchars($geo['country'])];
                }
                
                if (isset($geo['coordinates'])) {
                    $geo_items[] = ['label' => 'Coordinates', 'value' => htmlspecialchars($geo['coordinates'])];
                }
                
                $last_index = count($geo_items) - 1;
                
                foreach ($geo_items as $index => $item):
                    $class = ($index === $last_index) ? 'geo-row last-child' : 'geo-row';
                ?>
                <div class="<?= $class ?>">
                    <span class="geo-label"><?= $item['label'] ?></span>
                    <span class="geo-value"><?= $item['value'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info"><?= ($lang === 'en') ? 'Unable to retrieve location information.' : 'Impossible de récupérer la position géographique.' ?></div>
        <?php endif; ?>
    </div>

<?php
    require_once("../include/footer.inc.php");
?>
