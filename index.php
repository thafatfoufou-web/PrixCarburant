<?php
    /**
     * @file index.php
     * @brief Page principale de l'application PleinMalin
     * @see include/header.inc.php En-tête HTML commun
     * @see include/footer.inc.php Pied de page commun
     * @see server/fuel_prices.php Module de récupération des prix
     *
     * @author RAHARIMANANA Tianantenaina
     * @author BOUKIRAT Thafat
     */
    // Charger la géolocalisation par IP puis les données des prix
    require_once("server/location_api.php");
    require_once("server/fuel_prices.php");
    // Définir le titre et le sous-titre selon la langue
    $title = ($lang === 'en') ? 'Fuel Prices' : 'Prix des Carburants';
    $h1    = ($lang === 'en') ? 'Fuel Prices in France' : 'Prix des Carburants en France';
    require_once("include/header.inc.php");
?>
    <!-- Carte interactive de la France -->
    <section class="page-head">
        <div class="page-head-content">
            <h1 class="page-title">
                <?= ($lang === 'en') ? 'Find the Best Fuel Prices in France' : 'Trouvez les Meilleurs Prix des Carburants en France' ?>
            </h1>
            <p class="page-subtitle">
                <?= ($lang === 'en')
                    ? 'Save money on every journey. Compare real-time fuel prices across all French regions, from Normandy to Provence. Our interactive map makes it easy to find the cheapest stations near you.'
                    : 'Économisez de l\'argent à chaque trajet. Comparez les prix des carburants en temps réel dans toutes les régions françaises, de la Normandie à la Provence. Notre carte interactive facilite la recherche des stations les moins chères près de chez vous.' ?>
            </p>
            <div class="page-features">
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    <span><?= ($lang === 'en') ? 'All Regions' : 'Toutes les Régions' ?></span>
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span><?= ($lang === 'en') ? 'Real-time Data' : 'Données en Temps Réel' ?></span>
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span><?= ($lang === 'en') ? 'Precise Locations' : 'Localisations Précises' ?></span>
                </div>
            </div>
        </div>
    </section>

    <?php
        $geo_city_real = !empty($geo) && !in_array($geo['city'] ?? '', ['Inconnue', 'localhost', ''], true);
    ?>
    
    <?php if ($geo_city_real): ?>
        <div class="alert alert-info">
            <?= ($lang === 'en') ? 'Detected location: ' : 'Position détectée : ' ?>
            <strong><?= htmlspecialchars($geo['city']) ?></strong>
            (<?= htmlspecialchars($geo['region']) ?>)
            <?php 
                // Only show "Search here" link if the form is not already populated with this location
                $is_searching_detected_location = !empty($town) && strtolower($town) === strtolower($geo['city']);
                if (!$is_searching_detected_location): 
            ?>
                — <a href="?town=<?= urlencode($geo['city']) ?>&amp;search=1">
                    <?= ($lang === 'en') ? 'Search here' : 'Rechercher ici' ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="layout-two">
        <section class="panel panel-left" aria-label="<?= ($lang === 'en') ? 'Selection' : 'Sélection' ?>">
        <div class="panel-block first-child">
            <div class="map-section">
                <div class="map-container">
                    <img src="images/France.png"
                         alt="Carte des régions françaises — cliquez sur une région"
                         class="map-image"
                         usemap="#carte-france"
                         id="map-image" />

                    <svg class="map-overlay" id="map-overlay" viewBox="0 0 650 650" preserveAspectRatio="xMidYMid meet"></svg>

                    <map name="carte-france">
                        <area shape="poly" coords="362,292,353,295,351,297,352,302,351,305,342,306,338,312,339,316,347,321,351,330,350,334,352,341,343,350,347,355,349,361,347,364,349,378,342,376,342,380,334,390,336,392,333,394,333,397,330,399,331,402,328,404,329,410,333,416,331,422,335,427,340,424,346,425,350,420,354,411,358,406,364,411,365,415,368,417,367,420,370,425,370,425,377,409,379,411,387,404,389,406,391,412,397,413,398,410,400,410,410,418,412,428,419,442,427,448,435,443,435,446,437,444,440,444,446,447,446,444,452,444,456,449,465,445,468,446,470,444,471,450,480,451,480,454,486,456,490,451,494,451,494,446,484,440,482,437,485,432,490,434,492,431,489,429,492,422,498,421,498,418,499,415,505,413,506,410,519,406,522,408,522,402,518,398,516,394,518,390,526,394,528,392,535,390,555,382,557,378,556,375,558,370,551,365,549,359,549,356,543,353,541,350,540,347,549,339,550,336,546,331,538,323,541,317,537,311,537,308,534,307,525,308,520,312,516,311,513,317,516,320,509,326,501,327,502,321,508,318,509,310,505,308,500,315,494,318,490,319,490,316,486,314,482,319,479,319,479,316,476,316,472,308,471,308,467,304,461,306,455,304,449,326,443,318,441,320,439,319,436,320,434,319,432,321,431,324,425,328,423,325,415,327,409,324,410,320,413,316,414,310,412,307,404,304,397,290,391,297,386,293,384,295,377,294,371,288,367,288,362,292" href="?region=84" alt="Auvergne-Rhône-Alpes" title="Auvergne-Rhône-Alpes" data-region="84"/>
                        <area shape="poly" coords="370,189,370,195,366,201,373,208,373,214,369,219,370,222,368,225,363,228,366,231,368,238,365,239,364,242,366,248,364,254,367,257,370,264,370,271,373,273,372,279,371,288,377,294,384,295,386,293,391,297,397,290,404,304,412,307,414,310,413,316,410,320,409,324,415,327,423,325,425,328,431,324,432,321,434,319,436,320,439,319,441,320,443,318,449,326,455,304,461,306,467,304,471,308,472,308,472,308,476,316,479,316,479,319,482,319,486,314,490,316,490,319,494,318,500,315,505,308,509,296,521,285,520,275,529,268,541,253,541,250,543,248,539,245,540,241,544,237,548,237,545,231,542,231,542,222,534,217,534,217,533,218,524,211,519,213,514,209,508,211,501,206,493,212,493,214,490,217,487,217,485,219,485,228,482,229,479,227,473,231,472,234,466,236,466,233,463,231,454,229,451,224,454,222,453,219,451,216,448,216,449,213,446,212,445,209,438,208,433,212,427,213,424,215,421,213,408,215,403,204,399,200,397,202,395,193,390,187,386,186,370,189" href="?region=27" alt="Bourgogne-Franche-Comté" title="Bourgogne-Franche-Comté" data-region="27"/>
                        <area shape="poly" coords="175,167,164,167,164,161,161,162,157,166,159,172,157,170,155,165,152,165,152,167,148,168,147,164,144,166,143,163,139,164,128,173,127,169,123,166,123,163,118,155,115,154,116,151,112,152,111,155,112,148,107,151,107,148,97,151,96,149,91,153,91,159,88,159,82,156,80,157,79,160,77,158,75,161,75,154,60,159,58,156,48,162,50,163,42,162,40,165,38,174,40,177,43,175,46,177,58,173,55,175,54,178,60,177,60,180,63,181,60,182,66,184,60,183,59,180,55,181,49,180,47,178,45,181,48,187,50,184,53,184,58,188,59,191,57,193,55,192,42,193,40,196,50,200,54,206,53,212,60,212,64,208,64,208,63,205,65,205,65,201,66,204,65,205,64,208,67,210,72,208,77,214,80,214,80,211,80,213,80,214,83,213,82,215,85,216,89,215,89,211,90,219,93,221,96,220,95,214,96,217,99,217,97,220,98,222,102,224,105,223,102,224,102,227,105,230,104,233,105,233,108,231,111,230,113,232,112,225,115,231,117,228,121,229,123,231,121,234,114,233,119,237,132,236,135,237,132,237,133,240,133,240,149,235,149,229,155,226,168,225,170,222,179,218,186,222,187,220,191,211,196,208,196,188,196,176,190,173,183,178,180,177,175,167" href="?region=53" alt="Bretagne" title="Bretagne" data-region="53"/>
                        <area shape="poly" coords="344,194,343,191,339,190,337,192,335,189,333,192,326,193,323,182,320,182,317,180,316,175,310,169,309,158,305,151,305,151,299,158,299,161,293,162,290,161,285,164,279,165,276,168,276,171,281,176,281,185,274,190,275,198,275,198,277,202,277,204,274,206,276,208,277,214,270,225,267,226,266,230,258,234,255,234,256,238,250,235,249,237,247,247,241,263,241,263,247,266,247,270,250,269,251,276,254,277,265,277,263,274,269,277,269,280,278,293,278,299,282,304,285,304,288,310,291,311,289,314,292,315,298,314,300,316,303,313,315,312,316,310,330,312,333,312,338,312,342,306,351,305,352,302,351,297,353,295,362,292,367,288,371,288,372,279,373,273,370,271,370,264,367,257,364,254,366,248,364,242,365,239,368,238,366,231,363,228,368,225,370,222,369,219,373,214,373,208,366,201,366,201,354,203,345,203,348,201,348,198,344,194" href="?region=24" alt="Centre-Val de Loire" title="Centre-Val de Loire" data-region="24"/>
                        <area shape="poly" coords="558,527,556,529,557,547,548,546,544,551,533,555,532,558,529,558,527,561,527,565,523,570,522,573,525,572,529,576,524,580,525,586,533,590,530,592,527,601,535,598,536,604,533,611,543,613,538,617,537,620,542,624,554,628,555,631,558,632,564,617,561,616,564,614,566,609,565,600,565,594,571,581,567,554,563,548,563,537,561,528,558,527" href="?region=94" alt="Corse" title="Corse" data-region="94"/>
                        <area shape="poly" coords="536,129,533,128,530,129,529,132,527,131,518,122,517,116,513,114,502,111,497,115,494,115,489,112,485,109,478,109,477,112,471,113,468,106,467,106,467,106,464,106,464,102,458,101,451,95,445,95,446,88,443,82,443,78,446,73,443,71,439,76,437,82,428,86,422,84,419,85,419,85,419,91,418,95,419,98,414,106,411,106,412,121,411,124,405,122,397,126,396,129,399,137,393,139,395,142,394,145,397,146,389,156,388,159,386,161,389,170,392,171,389,176,386,177,386,186,390,187,395,193,397,202,399,200,403,204,408,215,421,213,424,215,427,213,433,212,438,208,445,209,446,212,449,213,448,216,451,216,453,219,454,222,451,224,454,229,463,231,466,233,466,236,472,234,473,231,479,227,482,229,485,228,485,219,487,217,490,217,493,214,493,212,501,206,508,211,514,209,519,213,524,211,533,218,534,217,534,217,542,222,542,231,545,231,548,237,548,237,551,237,550,240,558,240,561,239,561,236,564,236,564,233,567,231,564,223,567,207,565,196,571,182,572,165,583,151,587,140,578,137,563,136,563,136,563,136,554,129,548,133,541,132,538,133,536,129" href="?region=44" alt="Grand Est" title="Grand Est" data-region="44"/>
                        <area shape="poly" coords="418,65,415,66,410,61,404,62,399,61,396,63,394,53,388,52,387,49,384,51,381,50,378,41,378,38,374,34,368,34,362,38,359,37,356,32,353,31,352,29,353,23,350,17,347,15,330,19,316,23,310,29,310,47,313,51,310,49,310,55,310,59,312,61,309,62,308,65,311,71,308,70,301,79,301,79,314,90,318,99,314,104,316,113,315,121,318,130,314,132,316,136,321,137,328,135,338,137,340,136,351,142,358,143,360,141,363,143,371,140,374,140,376,143,375,146,379,150,389,156,389,156,397,146,394,145,395,142,393,139,399,137,396,129,397,126,405,122,411,124,412,121,411,106,414,106,419,98,418,95,419,91,419,85,419,85,415,84,419,77,416,72,418,65" href="?region=32" alt="Hauts-de-France" title="Hauts-de-France" data-region="32"/>
                        <area shape="poly" coords="375,146,376,143,374,140,371,140,363,143,360,141,358,143,351,142,340,136,338,137,328,135,321,137,316,136,314,132,310,142,306,142,303,145,305,151,305,151,305,151,309,158,310,169,316,175,317,180,320,182,323,182,326,193,333,192,335,189,337,192,339,190,343,191,344,194,348,198,348,201,345,203,354,203,366,201,366,201,370,195,370,189,386,186,386,177,389,176,392,171,389,170,386,161,388,159,389,156,389,156,379,150,375,146" href="?region=11" alt="Île-de-France" title="Île-de-France" data-region="11"/>
                        <area shape="poly" coords="301,79,301,79,301,79,290,87,267,93,253,101,247,114,248,116,258,118,256,119,249,120,244,125,234,128,225,124,212,123,202,119,197,121,190,111,193,102,192,100,185,99,182,101,175,101,164,96,163,99,167,101,167,106,165,108,168,119,177,128,175,139,177,142,177,148,175,154,179,164,182,165,184,163,183,166,176,167,180,177,183,178,190,173,196,176,196,176,205,176,208,180,211,179,213,180,220,177,230,177,230,174,233,174,235,181,239,182,239,185,242,185,253,179,256,181,258,190,265,195,268,194,271,197,275,198,275,198,274,190,281,185,281,176,276,171,276,168,279,165,285,164,290,161,293,162,299,161,299,158,305,151,305,151,303,145,306,142,310,142,314,132,318,130,315,121,316,113,314,104,318,99,314,90,301,79" href="?region=28" alt="Normandie" title="Normandie" data-region="28"/>
                        <area shape="poly" coords="339,316,338,312,333,312,330,312,316,310,315,312,303,313,300,316,298,314,292,315,289,314,291,311,288,310,285,304,282,304,278,299,278,293,269,280,269,277,263,274,265,277,254,277,251,276,250,269,247,270,247,266,241,263,234,269,232,267,222,267,216,268,212,273,200,274,202,279,206,284,211,302,211,310,213,312,204,317,202,315,195,315,196,312,188,315,188,316,183,326,186,327,190,335,187,335,189,341,186,347,181,350,182,355,191,360,201,371,204,379,204,379,204,379,204,382,202,385,199,377,188,366,187,363,184,369,178,421,182,413,187,419,187,422,180,421,177,428,177,433,166,485,162,492,157,499,150,503,156,506,157,509,159,507,166,509,167,512,165,518,163,520,164,523,168,524,169,520,172,519,172,523,178,526,188,530,194,530,197,534,205,541,208,539,210,541,216,538,216,538,217,529,219,526,222,525,222,522,230,512,229,509,232,508,232,500,229,500,230,497,228,491,221,491,219,488,223,477,223,470,233,467,233,470,236,469,236,466,240,467,242,464,250,465,255,462,264,463,267,461,271,456,274,457,272,454,277,448,274,447,274,444,282,442,279,432,283,429,288,422,294,419,293,415,298,412,300,401,309,400,316,407,321,404,328,404,331,402,330,399,333,397,333,394,336,392,334,390,342,380,342,376,349,378,347,364,349,361,347,355,343,350,352,341,350,334,351,330,347,321,339,316" href="?region=75" alt="Nouvelle-Aquitaine" title="Nouvelle-Aquitaine" data-region="75"/>
                        <area shape="poly" coords="321,404,316,407,309,400,300,401,298,412,293,415,294,419,288,422,283,429,279,432,282,442,274,444,274,447,277,448,272,454,274,457,271,456,267,461,264,463,255,462,250,465,242,464,240,467,236,466,236,469,233,470,233,467,223,470,223,477,219,488,221,491,228,491,230,497,229,500,232,500,232,508,229,509,230,512,222,522,222,525,219,526,217,529,216,538,216,538,228,548,241,546,243,549,260,549,262,546,262,540,264,538,282,543,287,548,293,548,299,555,300,552,304,552,315,557,315,557,315,557,313,562,322,565,324,571,328,571,332,567,336,567,345,569,350,573,357,572,357,569,365,565,372,564,381,566,374,559,374,540,375,530,380,519,383,516,389,512,396,512,403,505,414,496,423,494,426,499,429,500,429,500,432,493,438,492,437,489,442,484,445,485,447,474,456,464,449,458,449,452,446,447,440,444,437,444,435,446,435,443,427,448,419,442,412,428,410,418,400,410,398,410,397,413,391,412,389,406,387,404,379,411,377,409,370,425,370,425,367,420,368,417,365,415,364,411,358,406,354,411,350,420,346,425,340,424,335,427,331,422,333,416,329,410,328,404,321,404" href="?region=76" alt="Occitanie" title="Occitanie" data-region="76"/>
                        <area shape="poly" coords="196,176,196,188,194,190,196,208,191,211,187,220,186,222,179,218,170,222,168,225,155,226,149,229,149,235,133,240,134,240,129,245,131,251,138,252,140,254,145,250,152,250,145,252,145,259,142,261,148,263,153,268,145,277,145,281,156,294,158,301,166,308,171,309,173,313,179,314,184,318,185,315,187,316,188,315,196,312,195,315,202,315,204,317,213,312,211,310,211,302,206,284,202,279,200,274,212,273,216,268,222,267,232,267,234,269,241,263,241,263,247,247,249,237,250,235,256,238,255,234,258,234,266,230,267,226,270,225,277,214,276,208,274,206,277,204,277,202,275,198,271,197,268,194,265,195,258,190,256,181,253,179,242,185,239,185,239,182,235,181,233,174,230,174,230,177,220,177,213,180,211,179,208,180,205,176,196,176" href="?region=52" alt="Pays de la Loire" title="Pays de la Loire" data-region="52"/>
                        <area shape="poly" coords="518,390,516,394,518,398,522,402,522,408,519,406,506,410,505,413,499,415,498,418,498,421,492,422,489,429,492,431,490,434,485,432,482,437,484,440,494,446,494,451,490,451,486,456,480,454,480,451,471,450,470,444,468,446,465,445,456,449,452,444,446,444,446,447,449,452,449,458,456,464,447,474,445,485,442,484,437,489,438,492,432,493,429,500,429,500,444,500,446,503,446,506,449,507,459,505,457,503,458,500,461,501,466,506,480,505,484,514,494,516,497,515,503,519,503,522,512,520,518,521,522,518,529,520,535,515,538,513,542,513,544,508,541,506,547,498,553,497,557,489,563,486,564,482,579,474,580,466,586,457,588,453,584,447,572,451,562,446,556,445,549,437,551,432,547,427,549,425,551,418,553,416,556,416,553,406,546,406,541,403,540,396,537,396,535,390,528,392,526,394,518,390" href="?region=93" alt="Provence-Alpes-Côte d'Azur" title="Provence-Alpes-Côte d'Azur" data-region="93"/>
                    </map>
                </div>
            </div>
        </div>

        <div class="panel-block last-child">
            <div class="card card-form">
                <div class="card-head">
                    <?php if (!empty($region_code)): ?>
                    <div class="card-head-row">
                        <div class="card-head-left">
                            <h2 class="card-title"><?= ($lang === 'en') ? 'Search' : 'Recherche' ?></h2>
                            <p class="card-hint"><?= ($lang === 'en') ? 'Follow the steps to find fuel stations near you.' : 'Suivez les étapes pour trouver des stations près de chez vous.' ?></p>
                        </div>
                        <div class="card-head-right">
                            <a href="./" class="btn-reset" title="<?= ($lang === 'en') ? 'Reset form' : 'Réinitialiser le formulaire' ?>" aria-label="<?= ($lang === 'en') ? 'Reset form' : 'Réinitialiser le formulaire' ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                </svg>
                                <span class="btn-reset-text"><?= ($lang === 'en') ? 'Reset' : 'Réinitialiser' ?></span>
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <h2 class="card-title"><?= ($lang === 'en') ? 'Search' : 'Recherche' ?></h2>
                    <p class="card-hint"><?= ($lang === 'en') ? 'Follow the steps to find fuel stations near you.' : 'Suivez les étapes pour trouver des stations près de chez vous.' ?></p>
                    <?php endif; ?>
                </div>

                <form method="GET" id="search-form">
                    <input type="hidden" name="action" id="form-action" value=""/>
                    <?php if (!empty($region_code)): ?>
                    <input type="hidden" name="region" value="<?= htmlspecialchars($region_code) ?>"/>
                    <?php endif; ?>
                    <?php if (!empty($dept_code)): ?>
                    <input type="hidden" name="dept" value="<?= htmlspecialchars($dept_code) ?>"/>
                    <?php endif; ?>

                    <!-- Step 1: Region Selection -->
                    <div class="form-step <?= empty($region_code) ? 'is-active' : 'is-completed' ?>" id="step-region">
                        <div class="step-header">
                            <div class="step-number" role="img" aria-label="<?= ($lang === 'en') ? 'Step 1' : 'Étape 1' ?>">
                                <?php if (!empty($region_code)): ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                <?php else: ?>
                                    1
                                <?php endif; ?>
                            </div>
                            <h3 class="step-title"><?= ($lang === 'en') ? 'Select a Region' : 'Sélectionnez une Région' ?></h3>
                        </div>
                        
                        <div class="step-content">
                            <?php if (!empty($region_code)): ?>
                                <div class="step-selected-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?= htmlspecialchars($regions[$region_code] ?? '') ?></span>
                                </div>
                                <div class="step-actions">
                                    <a href="?action=change_region" class="btn-change">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <?= ($lang === 'en') ? 'Change' : 'Modifier' ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <label for="region" class="visually-hidden"><?= ($lang === 'en') ? 'Choose your region' : 'Choisissez votre région' ?></label>
                                <select name="region" id="region" class="form-step-select" required="required">
                                    <option value=""><?= ($lang === 'en') ? '-- Choose a region --' : '-- Choisissez une région --' ?></option>
                                    <?php foreach ($regions as $code => $name): ?>
                                        <option value="<?= htmlspecialchars($code) ?>">
                                            <?= htmlspecialchars($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="step-help-text">
                                    <?= ($lang === 'en') ? 'You can also click on the map above to select a region.' : 'Vous pouvez aussi cliquer sur la carte ci-dessus pour sélectionner une région.' ?>
                                </p>
                                <div class="step-actions">
                                    <button type="submit" class="form-apply-btn" onclick="document.getElementById(&apos;form-action&apos;).value=&apos;update_dept&apos;">
                                        <?= ($lang === 'en') ? 'Continue' : 'Continuer' ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 2: Department Selection -->
                    <?php if ($show_dept): ?>
                    <div class="form-step <?= empty($dept_code) ? 'is-active' : 'is-completed' ?>" id="step-department">
                        <div class="step-header">
                            <div class="step-number" role="img" aria-label="<?= ($lang === 'en') ? 'Step 2' : 'Étape 2' ?>">
                                <?php if (!empty($dept_code)): ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                <?php else: ?>
                                    2
                                <?php endif; ?>
                            </div>
                            <h3 class="step-title"><?= ($lang === 'en') ? 'Select a Department' : 'Sélectionnez un Département' ?></h3>
                        </div>
                        
                        <div class="step-content">
                            <?php if (!empty($dept_code)): ?>
                                <div class="step-selected-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span><?= htmlspecialchars($region_depts[$dept_code] ?? '') ?> (<?= htmlspecialchars($dept_code) ?>)</span>
                                </div>
                                <div class="step-actions">
                                    <a href="?region=<?= urlencode($region_code) ?>&amp;action=change_dept" class="btn-change">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <?= ($lang === 'en') ? 'Change' : 'Modifier' ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <label for="dept" class="visually-hidden"><?= ($lang === 'en') ? 'Choose your department' : 'Choisissez votre département' ?></label>
                                <select name="dept" id="dept" class="form-step-select" required="required">
                                    <option value=""><?= ($lang === 'en') ? '-- Choose a department --' : '-- Choisissez un département --' ?></option>
                                    <?php foreach ($region_depts as $code => $name): ?>
                                        <option value="<?= htmlspecialchars($code) ?>">
                                            <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($code) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="step-help-text">
                                    <?= ($lang === 'en') ? 'Select the department where you want to search for stations.' : 'Sélectionnez le département où vous souhaitez rechercher des stations.' ?>
                                </p>
                                <div class="step-actions">
                                    <button type="submit" class="form-apply-btn" onclick="document.getElementById(&apos;form-action&apos;).value=&apos;update_town&apos;">
                                        <?= ($lang === 'en') ? 'Continue' : 'Continuer' ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Step 3: City Selection (Optional) -->
                    <?php if ($show_town): ?>
                    <div class="form-step <?= empty($town) ? 'is-active' : 'is-completed' ?>" id="step-city">
                        <div class="step-header">
                            <div class="step-number" role="img" aria-label="<?= ($lang === 'en') ? 'Step 3' : 'Étape 3' ?>">
                                <?php if (!empty($town)): ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                <?php else: ?>
                                    3
                                <?php endif; ?>
                            </div>
                            <h3 class="step-title"><?= ($lang === 'en') ? 'Select a City (Optional)' : 'Sélectionnez une Ville (Optionnel)' ?></h3>
                        </div>
                        
                        <div class="step-content">
                            <?php if (!empty($town)): ?>
                                <div class="step-selected-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?= htmlspecialchars($town) ?></span>
                                </div>
                                <div class="step-actions">
                                    <a href="?region=<?= urlencode($region_code) ?>&amp;dept=<?= urlencode($dept_code) ?>&amp;action=change_town" class="btn-change">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <?= ($lang === 'en') ? 'Change' : 'Modifier' ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <label for="town" class="visually-hidden"><?= ($lang === 'en') ? 'Choose your city' : 'Choisissez votre ville' ?></label>
                                <select name="town" id="town" class="form-step-select" aria-required="false">
                                    <option value=""><?= ($lang === 'en') ? '-- All cities in department --' : '-- Toutes les villes du département --' ?></option>
                                    <?php foreach ($dept_cities as $city_name => $city_info): ?>
                                        <option value="<?= htmlspecialchars($city_name) ?>">
                                            <?= htmlspecialchars($city_name) ?> (<?= htmlspecialchars($city_info['cp']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="step-help-text">
                                    <?= ($lang === 'en') ? 'Leave empty to search all cities in the department.' : 'Laissez vide pour rechercher dans toutes les villes du département.' ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" name="search" class="form-submit-btn" onclick="document.getElementById(&apos;form-action&apos;).value=&apos;&apos;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 8px;" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <?= ($lang === 'en') ? 'Search Stations' : 'Rechercher des Stations' ?>
                    </button>
                    <?php endif; ?>

                </form>

                <?php if (isset($_COOKIE['last_city'])): ?>
                <p class="last-visit">
                    <?= ($lang === 'en') ? 'Last visited: ' : 'Dernière visite : ' ?>
                    <a href="?region=<?= urlencode($_COOKIE['last_region'] ?? '') ?>&amp;dept=<?= urlencode($_COOKIE['last_dept'] ?? '') ?>&amp;town=<?= urlencode($_COOKIE['last_city']) ?>&amp;search=1">
                        <?= htmlspecialchars($_COOKIE['last_city']) ?>
                    </a>
                </p>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <section class="panel panel-right" aria-label="<?= ($lang === 'en') ? 'Results' : 'Résultats' ?>">
        <div class="card">

    <?php // Afficher les stations trouvées si recherche ou géolocalisation automatique
             if (!empty($stations) && (isset($_GET['search']) || ($geo_suggestion_used ?? false))): 
                // Obtenir le mode d'affichage depuis le paramètre GET (par défaut 'detailed')
                $view_mode = $_GET['view'] ?? 'detailed';
                $is_simple_view = ($view_mode === 'simple');
             ?>
                <div class="results-head">
                    <div class="results-head-left">
                        <h2 class="results-title">
                            <?= ($lang === 'en') ? 'Stations in ' : 'Stations à ' ?>
                            <?= htmlspecialchars($search_info ?: $town) ?>
                        </h2>
                        <div class="results-meta">
                            <?= count($stations) ?> <?= ($lang === 'en') ? 'results' : 'résultats' ?>
                        </div>
                    </div>
                    <div class="view-toggle-group">
                         <?php
                         // Construire l'URL avec tous les paramètres GET actuels sauf 'view', 'mode' et 'lang'
                         $url_params = $_GET;
                         unset($url_params['view'], $url_params['mode'], $url_params['lang']);
                         $base_url = '?' . http_build_query($url_params, '', '&amp;');
                         $separator = empty($url_params) ? '' : '&amp;';

                         // Basculer : déterminer le PROCHAIN mode d'affichage
                         $next_view = $is_simple_view ? 'detailed' : 'simple';
                         $current_label = $is_simple_view ? ($lang === 'en' ? 'Simple' : 'Simple') : ($lang === 'en' ? 'Detailed' : 'Détaillée');
                         ?>
                         <a href="<?= $base_url . $separator ?>view=<?= $next_view ?>"
                            class="view-toggle-btn"
                            title="<?= ($lang === 'en') ? 'Switch to ' . $next_view . ' view' : 'Passer en vue ' . ($next_view === 'simple' ? 'simple' : 'détaillée') ?>"
                            aria-label="<?= ($lang === 'en') ? 'Switch to ' . $next_view . ' view' : 'Passer en vue ' . ($next_view === 'simple' ? 'simple' : 'détaillée') ?>">
                             <?php if ($is_simple_view): ?>
                             <!-- Currently in simple view → show detailed icon -->
                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <rect x="3" y="3" width="7" height="7"></rect>
                                 <rect x="14" y="3" width="7" height="7"></rect>
                                 <rect x="14" y="14" width="7" height="7"></rect>
                                 <rect x="3" y="14" width="7" height="7"></rect>
                             </svg>
                             <?php else: ?>
                             <!-- Currently in detailed view → show simple icon -->
                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <line x1="8" y1="6" x2="21" y2="6"></line>
                                 <line x1="8" y1="12" x2="21" y2="12"></line>
                                 <line x1="8" y1="18" x2="21" y2="18"></line>
                                 <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                 <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                 <line x1="3" y1="18" x2="3.01" y2="18"></line>
                             </svg>
                             <?php endif; ?>
                             <span class="toggle-text"><?= $current_label ?></span>
                         </a>
                     </div>
                </div>
                <?php if ($geo_suggestion_used ?? false): ?>
                <div class="alert alert-info" style="margin-bottom: 16px;">
                    <?= ($lang === 'en')
                        ? 'Stations automatically displayed based on your detected location.'
                        : 'Stations affichées automatiquement selon votre position détectée.' ?>
                </div>
                <?php endif; ?>

                <ul class="results-list">
                    <?php foreach ($stations as $station): 
                        // Utiliser les horaires d'ouverture réels de l'API
                        $is_open = $station['is_open'] ?? false;
                        $hours_text = $station['hours_text'] ?? (($lang === 'en') ? 'Hours unavailable' : 'Horaires non disponibles');
                        $hours_full = $station['hours_full'] ?? $hours_text;
                        
                        // Déterminer si nous devons afficher l'horaire complet (différent des horaires d'aujourd'hui)
                        $has_full_schedule = strlen($hours_full) > 50 && $hours_full !== $hours_text;
                    ?>
                    <li class="station-card <?= $is_simple_view ? 'simple-view' : 'detailed-view' ?>">
                        <?php if (!$is_simple_view): ?>
                        <div class="station-header">
                            <div class="station-info">
                                <h3 class="station-name">
                                    <?= htmlspecialchars($station['nom'] ?? 'N/A') ?>
                                </h3>
                                
                                <div class="station-status <?= $is_open ? 'status-open' : 'status-closed' ?>">
                                    <svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span><?= htmlspecialchars($hours_text) ?></span>
                                </div>
                                <?php if ($has_full_schedule): ?>
                                <details class="station-hours-details">
                                    <summary class="station-hours-summary"><?= ($lang === 'en') ? 'Full schedule' : 'Horaires complets' ?></summary>
                                    <div class="station-hours-full">
                                        <?php
                                        // Diviser par virgule et afficher chaque jour sur une nouvelle ligne
                                        $days = explode(', ', $hours_full);
                                        foreach ($days as $day) {
                                            echo '<div>' . htmlspecialchars($day) . '</div>';
                                        }
                                        ?>
                                    </div>
                                </details>
                                <?php endif; ?>
                            </div>
                            <div class="station-address-block">
                                <div class="station-address-content">
                                    <svg class="address-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <address class="station-address">
                                        <?= htmlspecialchars($station['adresse'] ?? '') ?>,
                                        <?= htmlspecialchars($station['commune'] ?? '') ?>
                                    </address>
                                </div>
                                <?php if (!empty($station['lat']) && !empty($station['lng'])): ?>
                                    <a href="https://maps.google.com/?q=<?= $station['lat'] ?>,<?= $station['lng'] ?>"
                                    target="_blank" rel="noopener noreferrer" class="maps-link" title="<?= ($lang === 'en') ? 'View on Google Maps' : 'Voir sur Google Maps' ?>">
                                        <?= ($lang === 'en') ? 'View on Google Maps' : 'Voir sur Google maps' ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($is_simple_view): ?>
                        <!-- Simple view: compact layout with address and prices -->
                        <div class="station-simple-content">
                            <div class="station-simple-address">
                                <svg class="address-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <address class="station-address">
                                    <?= htmlspecialchars($station['adresse'] ?? '') ?>,
                                    <?= htmlspecialchars($station['commune'] ?? '') ?>
                                </address>
                            </div>
                            <div class="station-simple-prices">
                                <?php
                                $fuels = [
                                    'Gazole' => ['prix' => $station['prix_gazole'] ?? null, 'class' => 'fuel-gazole'],
                                    'SP95'   => ['prix' => $station['prix_sp95']   ?? null, 'class' => 'fuel-sp95'],
                                    'SP98'   => ['prix' => $station['prix_sp98']   ?? null, 'class' => 'fuel-sp98'],
                                    'E10'    => ['prix' => $station['prix_e10']    ?? null, 'class' => 'fuel-e10'],
                                    'GPL'    => ['prix' => $station['prix_gplc']   ?? null, 'class' => 'fuel-gpl'],
                                ];
                                $has_prices = false;
                                foreach ($fuels as $label => $data):
                                    if ($data['prix'] !== null):
                                        $has_prices = true;
                                ?>
                                <div class="price-badge <?= $data['class'] ?>">
                                    <span class="price-badge-label"><?= $label ?></span>
                                    <span class="price-badge-value"><?= number_format($data['prix'], 3) ?> €</span>
                                </div>
                                <?php
                                    endif;
                                endforeach;
                                
                                if (!$has_prices):
                                ?>
                                <div class="no-prices">
                                    <?= ($lang === 'en') ? 'No price data available' : 'Aucun prix disponible' ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Detailed view: original layout -->
                        <div class="prices-grid">
                            <?php
                            $fuels = [
                                'Gazole' => ['prix' => $station['prix_gazole'] ?? null, 'class' => 'fuel-gazole'],
                                'SP95'   => ['prix' => $station['prix_sp95']   ?? null, 'class' => 'fuel-sp95'],
                                'SP98'   => ['prix' => $station['prix_sp98']   ?? null, 'class' => 'fuel-sp98'],
                                'E10'    => ['prix' => $station['prix_e10']    ?? null, 'class' => 'fuel-e10'],
                                'GPL'    => ['prix' => $station['prix_gplc']   ?? null, 'class' => 'fuel-gpl'],
                            ];
                            $has_prices = false;
                            foreach ($fuels as $label => $data):
                                if ($data['prix'] !== null):
                                    $has_prices = true;
                            ?>
                            <div class="price-item <?= $data['class'] ?>">
                                <span class="price-label"><?= $label ?></span>
                                <span class="price-value"><?= number_format($data['prix'], 3) ?> €</span>
                            </div>
                            <?php
                                endif;
                            endforeach;
                            
                            if (!$has_prices):
                            ?>
                            <div class="no-prices">
                                <?= ($lang === 'en') ? 'No price data available' : 'Aucun prix disponible' ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (isset($station['maj']) && $station['maj']): ?>
                        <div class="station-update">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span><?= ($lang === 'en') ? 'Updated: ' : 'Mis à jour : ' ?><?= date('d/m/Y H:i', strtotime($station['maj'])) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                 </ul>

            <?php // Message si aucune station trouvée (recherche ou géolocalisation)
             elseif ((isset($_GET['search']) || ($geo_suggestion_used ?? false)) && empty($stations)): ?>
                <div class="empty-state">
                    <h2 class="empty-title"><?= ($lang === 'en') ? 'No stations found' : 'Aucune station trouvée' ?></h2>
                    <p class="empty-text"><?= ($lang === 'en') ? 'Try another city.' : 'Essayez une autre ville.' ?></p>
                </div>
            <?php // Message d'invite si aucune recherche n'est lancée
             else: ?>
                <div class="empty-state">
                    <h2 class="empty-title"><?= ($lang === 'en') ? 'Start with a region' : 'Commencez par une région' ?></h2>
                    <p class="empty-text"><?= ($lang === 'en') ? 'Click a region on the map.' : 'Cliquez sur une région de la carte.' ?></p>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<?php
    require_once("include/footer.inc.php");
?>