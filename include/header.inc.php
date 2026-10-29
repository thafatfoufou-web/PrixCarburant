<?php
    // On regarde où on se trouve pour savoir quelle page mettre en avant dans le menu
    $base_path = get_base_path();
    $current_page = get_current_page();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="RAHARIMANANA Tianantenaina BOUKIRAT Thafat" />
    <meta name="description" content="<?= ($lang === 'en') ? 'Find the best fuel prices in France by region, department and city.' : 'Trouvez les meilleurs prix des carburants en France par région, département et ville.' ?>" />
    <link rel="icon" type="image/x-icon" href="<?= $base_path ?>images/logo.ico" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/<?= ($mode == 'jour') ? 'light' : 'dark' ?>.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/base.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/header.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/menu.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/layout.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/components.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/stations.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/stats.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/contact.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/movie_geo.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/footer.css" />
    <link rel="stylesheet" href="<?= $base_path ?>styles/common/responsive.css" />
    <script src="<?= $base_path ?>script/map.js"></script>
    <title><?= $title ?></title>
</head>
<body>
    <header class="app-header">
        <div class="app-container">
            <div class="app-header-row">
                <div class="app-brand" title="Tankyou">
                    <a href="<?= $base_path ?>index.php" class="app-nav-link <?= ($current_page == 'index') ? 'is-active' : '' ?>"<?= ($current_page == 'index') ? ' aria-current="page"' : '' ?>>
                        <span class="app-brand-mark">
                            <img src="<?= $base_path ?>images/gas.png" alt="<?= ($lang === 'en') ? 'PleinMalin logo' : 'Logo PleinMalin' ?>" />
                        </span>
                        <span class="app-brand-name"><?= ($lang === 'en') ? 'Tankyou' : 'PleinMalin' ?></span>
                    </a>
                </div>

                <nav class="app-nav" aria-label="Navigation principale">
                    <a href="<?= $base_path ?>pages/stats.php" class="app-nav-link <?= ($current_page == 'stats') ? 'is-active' : '' ?>"<?= ($current_page == 'stats') ? ' aria-current="page"' : '' ?>>
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <?= ($lang === 'en') ? 'Stats' : 'Statistiques' ?>
                    </a>
                    <a href="<?= $base_path ?>pages/contact.php" class="app-nav-link <?= ($current_page == 'contact') ? 'is-active' : '' ?>"<?= ($current_page == 'contact') ? ' aria-current="page"' : '' ?>>
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <?= ($lang === 'en') ? 'Contact' : 'Contact' ?>
                    </a>
                </nav>

                <div class="app-actions">
                    <?php
                        // Preserve current URL parameters when toggling mode/lang
                        $current_params = $_GET;
                        
                        // Build mode toggle URL
                        $mode_params = $current_params;
                        $mode_params['mode'] = ($mode == 'jour') ? 'nuit' : 'jour';
                        $mode_url = '?' . http_build_query($mode_params);
                        
                        // Build lang toggle URL
                        $lang_params = $current_params;
                        $lang_params['lang'] = ($lang == 'fr') ? 'en' : 'fr';
                        $lang_url = '?' . http_build_query($lang_params);
                    ?>
                    <a class="app-icon-btn" href="<?= $mode_url ?>" title="<?= ($mode == 'jour') ? 'Dark mode' : 'Mode clair' ?>" aria-label="<?= ($mode == 'jour') ? 'Dark mode' : 'Mode clair' ?>">
                        <img src="<?= $base_path ?><?= ($mode == 'jour') ? 'images/dark-mode.png' : 'images/mode-clair.jpg' ?>" alt="<?= ($mode == 'jour') ? ($lang === 'en' ? 'Switch to dark mode' : 'Passer en mode sombre') : ($lang === 'en' ? 'Switch to light mode' : 'Passer en mode clair') ?>" />
                    </a>
                    <a class="app-icon-btn" href="<?= $lang_url ?>" title="<?= ($lang == 'fr') ? 'English' : 'Francais' ?>" aria-label="<?= ($lang == 'fr') ? 'English' : 'Francais' ?>">
                        <img src="<?= $base_path ?><?= ($lang == 'fr') ? 'images/english-flag.svg' : 'images/french-flag.webp' ?>" alt="<?= ($lang == 'fr') ? 'English flag' : 'Drapeau français' ?>" />
                    </a>
                </div>
            </div>
        </div>
    </header>

    <header class="app-header-mobile">
        <div class="app-container">
            <div class="app-header-row">
                <div class="app-brand" title="Fuel Prices FR">
                    <a href="<?= $base_path ?>index.php" class="app-nav-link <?= ($current_page == 'index') ? 'is-active' : '' ?>"<?= ($current_page == 'index') ? ' aria-current="page"' : '' ?>>
                        <span class="app-brand-mark">
                            <img src="<?= $base_path ?>images/gas.png" alt="<?= ($lang === 'en') ? 'PleinMalin logo' : 'Logo PleinMalin' ?>" />
                        </span>
                        <span class="app-brand-name"><?= ($lang === 'en') ? 'Tankyou' : 'PleinMalin' ?></span>
                    </a>
                </div>
                
                <input type="checkbox" id="mobile-menu-toggle" class="menu-toggle-checkbox" />
                <label for="mobile-menu-toggle" class="menu-toggle-label">
                    <span class="hamburger" aria-hidden="true">
                        <span class="bar1"></span>
                        <span class="bar2"></span>
                        <span class="bar3"></span>
                    </span>
                </label>
            </div>
        </div>
    </header>

    <div class="mobile-menu">
        <div class="mobile-menu-container">
            <nav class="mobile-menu-nav">
                <a href="<?= $base_path ?>pages/stats.php" class="mobile-nav-link <?= ($current_page == 'stats') ? 'is-active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mobile-nav-icon">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span><?= ($lang === 'en') ? 'Stats' : 'Statistiques' ?></span>
                </a>
                <a href="<?= $base_path ?>pages/contact.php" class="mobile-nav-link <?= ($current_page == 'contact') ? 'is-active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mobile-nav-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span><?= ($lang === 'en') ? 'Contact' : 'Contact' ?></span>
                </a>
            </nav>
            
            <div class="mobile-menu-footer">
                <div class="mobile-footer-section">
                    <span class="mobile-footer-label"><?= ($lang === 'en') ? 'Appearance' : 'Apparence' ?></span>
                    <div class="mobile-toggle-group">
                        <?php
                            $mode_jour_params = $_GET;
                            $mode_jour_params['mode'] = 'jour';
                            $mode_jour_url = '?' . http_build_query($mode_jour_params);
                            
                            $mode_nuit_params = $_GET;
                            $mode_nuit_params['mode'] = 'nuit';
                            $mode_nuit_url = '?' . http_build_query($mode_nuit_params);
                        ?>
                        <a href="<?= $mode_jour_url ?>" class="mobile-toggle-btn <?= ($mode == 'jour') ? 'is-active' : '' ?>" title="<?= ($lang === 'en') ? 'Light mode' : 'Mode clair' ?>">
                            <img src="<?= $base_path ?>images/mode-clair.jpg" alt="<?= ($lang === 'en') ? 'Light mode' : 'Mode clair' ?>" />
                        </a>
                        <a href="<?= $mode_nuit_url ?>" class="mobile-toggle-btn <?= ($mode == 'nuit') ? 'is-active' : '' ?>" title="<?= ($lang === 'en') ? 'Dark mode' : 'Mode sombre' ?>">
                            <img src="<?= $base_path ?>images/dark-mode.png" alt="<?= ($lang === 'en') ? 'Dark mode' : 'Mode sombre' ?>" />
                        </a>
                    </div>
                </div>
                
                <div class="mobile-footer-section">
                    <span class="mobile-footer-label"><?= ($lang === 'en') ? 'Language' : 'Langue' ?></span>
                    <div class="mobile-toggle-group">
                        <?php
                            $lang_fr_params = $_GET;
                            $lang_fr_params['lang'] = 'fr';
                            $lang_fr_url = '?' . http_build_query($lang_fr_params);
                            
                            $lang_en_params = $_GET;
                            $lang_en_params['lang'] = 'en';
                            $lang_en_url = '?' . http_build_query($lang_en_params);
                        ?>
                        <a href="<?= $lang_fr_url ?>" class="mobile-toggle-btn <?= ($lang == 'fr') ? 'is-active' : '' ?>" title="Français">
                            <img src="<?= $base_path ?>images/french-flag.webp" alt="Drapeau français" />
                        </a>
                        <a href="<?= $lang_en_url ?>" class="mobile-toggle-btn <?= ($lang == 'en') ? 'is-active' : '' ?>" title="English">
                            <img src="<?= $base_path ?>images/english-flag.svg" alt="English flag" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= $base_path ?>script/menu.js" defer="defer"></script>

    <div class="app-shell">
        <main class="app-main">
            <div class="app-container">
