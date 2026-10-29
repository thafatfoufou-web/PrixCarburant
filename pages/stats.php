<?php

    require_once("../include/config.inc.php");

    $title = ($lang === 'en') ? 'Statistics' : 'Statistiques';
    $h1 = ($lang === 'en') ? 'Statistics' : 'Statistiques';

    require_once("../include/header.inc.php");

    $log_file = "../data/city_log.csv";
    $departments_file = "../data/departments.csv";
    $city_counts = []; 
    $total_visits = 0;
    
    $departments = [];
    if (file_exists($departments_file)) {
        $dept_rows = read_csv($departments_file, true);
        foreach ($dept_rows as $dept) {
            if (isset($dept[0]) && isset($dept[2])) {
                $departments[$dept[0]] = $dept[2];
            }
        }
    }

    if (file_exists($log_file)) {
        $rows = read_csv($log_file, true);
        foreach ($rows as $data) {
            if (isset($data[1]) || isset($data[2])) {
                if (!empty($data[1])) {
                    $city = $data[1];
                } else {
                    $dept_code = $data[2];
                    $dept_name = isset($departments[$dept_code]) ? $departments[$dept_code] : $dept_code;
                    $city = $dept_name . " (" . $dept_code . ")";
                }
                $city_counts[$city] = ($city_counts[$city] ?? 0) + 1;
                $total_visits++;
            }
        }
    }

    arsort($city_counts); //Tri décroissant
    $top_cities = array_slice($city_counts, 0, 10, true); //Récuperation d'un extrait
    $max_count = !empty($top_cities) ? max($top_cities) : 1;
?>

<section class="page-head">
    <div class="page-head-content">
        <h1 class="page-title">
            <?= ($lang === 'en') ? 'Site Statistics' : 'Statistiques du Site' ?>
        </h1>
        <p class="page-subtitle">
            <?= ($lang === 'en') 
                ? 'Discover how users explore fuel prices across France. Track popular cities and usage trends in real-time.'
                : 'Découvrez comment les utilisateurs explorent les prix des carburants en France. Suivez les villes populaires et les tendances d\'utilisation en temps réel.' ?>
        </p>
    </div>
</section>

<?php if ($total_visits > 0): ?>
<section class="stats-section">
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $total_visits ?></div>
                <div class="stat-label"><?= ($lang === 'en') ? 'Total Visits' : 'Total de Visites' ?></div>
                <p class="stat-description">
                    <?= ($lang === 'en') 
                        ? 'Complete number of searches since launch' 
                        : 'Nombre total de recherches depuis le lancement' ?>
                </p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($city_counts) ?></div>
                <div class="stat-label"><?= ($lang === 'en') ? 'Cities Explored' : 'Villes Explorées' ?></div>
                <p class="stat-description">
                    <?= ($lang === 'en') 
                        ? 'Different cities searched by users' 
                        : 'Villes différentes recherchées par les utilisateurs' ?>
                </p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $max_count ?></div>
                <div class="stat-label"><?= ($lang === 'en') ? 'Peak City Visits' : 'Maximum de Visites' ?></div>
                <p class="stat-description">
                    <?= ($lang === 'en') 
                        ? 'Most visits to a single city' 
                        : 'Plus grand nombre de visites pour une ville' ?>
                </p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($top_cities)): ?>
    <section class="card stats-card">
        <div class="card-header">
            <h2 class="card-title">
                <?= ($lang === 'en') ? 'Top 10 Most Visited Cities' : 'Top 10 des Villes les Plus Consultées' ?>
            </h2>
            <p class="card-subtitle">
                <?= ($lang === 'en') 
                    ? 'Ranking of the most popular destinations based on user searches' 
                    : 'Classement des destinations les plus populaires selon les recherches des utilisateurs' ?>
            </p>
        </div>
        <div class="histogram-section">
            <?php 
            $rank = 1;
            foreach ($top_cities as $city => $count): 
                $percentage = ($count / $max_count) * 100;
            ?>
                <div class="histogram-item">
                    <span class="rank-badge">#<?= $rank ?></span>
                    <div class="histogram-body">
                        <div class="histogram-row">
                            <span class="histogram-label-left"><?= htmlspecialchars($city) ?></span>
                            <span class="histogram-count"><span class="histogram-number"><?= $count ?></span> <?= ($lang === 'en') ? 'visits' : 'visites' ?></span>
                        </div>
                        <div class="histogram-bar-container">
                            <span class="histogram-bar-fill" style="width: <?= $percentage ?>%"></span>
                        </div>
                    </div>
                </div>
            <?php 
                $rank++;
            endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <div class="empty-state-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h2 class="empty-title"><?= ($lang === 'en') ? 'No data yet' : 'Pas encore de données' ?></h2>
        <p class="empty-text"><?= ($lang === 'en') ? 'Start searching for cities to see statistics appear.' : 'Commencez à rechercher des villes pour voir les statistiques apparaître.' ?></p>
        <a href="<?= $base_path ?>index.php" class="empty-state-cta">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="10 8 16 12 10 16 10 8"></polygon>
            </svg>
            <?= ($lang === 'en') ? 'Start Exploring' : 'Commencer l\'Exploration' ?>
        </a>
    </section>
<?php endif; ?>

<?php
    require_once("../include/footer.inc.php");
?>
