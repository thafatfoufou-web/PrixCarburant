
            </div>
        </main>
    </div>

<footer class="app-footer">
    <div class="app-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="footer-title">
                    <?= ($lang === 'en') ? 'Fuel Prices Project' : 'Projet Prix Carburants' ?>
                </h3>
                <p class="footer-description">
                    <?= ($lang === 'en') 
                        ? 'Your trusted companion for finding the best fuel prices across France. We help you save money on every journey by providing real-time price comparisons from thousands of service stations.'
                        : 'Votre compagnon de confiance pour trouver les meilleurs prix des carburants en France. Nous vous aidons à économiser de l\'argent à chaque trajet en fournissant des comparaisons de prix en temps réel de milliers de stations-service.' ?>
                </p>
                <div class="footer-features">
                    <div class="footer-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span><?= ($lang === 'en') ? 'Real-time updates' : 'Mises à jour en temps réel' ?></span>
                    </div>
                    <div class="footer-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span><?= ($lang === 'en') ? 'Nationwide coverage' : 'Couverture nationale' ?></span>
                    </div>
                    <div class="footer-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        <span><?= ($lang === 'en') ? 'Free &amp; open data' : 'Données gratuites et ouvertes' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-subtitle"><?= ($lang === 'en') ? 'Quick Links' : 'Liens Rapides' ?></h4>
                <nav class="footer-nav">
                    <?php if ($current_page !== 'index'): ?>
                    <a href="<?= $base_path ?>index.php" class="footer-nav-link">
                        <?= ($lang === 'en') ? 'Home' : 'Accueil' ?>
                    </a>
                    <?php endif; ?>
                    <?php if ($current_page !== 'stats'): ?>
                    <a href="<?= $base_path ?>pages/stats.php" class="footer-nav-link">
                        <?= ($lang === 'en') ? 'Statistics' : 'Statistiques' ?>
                    </a>
                    <?php endif; ?>
                    <?php if ($current_page !== 'tech'): ?>
                    <a href="<?= $base_path ?>pages/tech.php" class="footer-nav-link">
                        <?= ($lang === 'en') ? 'Tech' : 'Tech' ?>
                    </a>
                    <?php endif; ?>
                    <?php if ($current_page !== 'contact'): ?>
                    <a href="<?= $base_path ?>pages/contact.php" class="footer-nav-link">
                        <?= ($lang === 'en') ? 'Contact' : 'Contact' ?>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-subtitle"><?= ($lang === 'en') ? 'About' : 'À Propos' ?></h4>
                <p class="footer-description-small">
                    <?= ($lang === 'en') 
                        ? 'Developed by students at CY Cergy Paris Université as part of a web development project. This application demonstrates modern web technologies and best practices.'
                        : 'Développé par des étudiants de CY Cergy Paris Université dans le cadre d\'un projet de développement web. Cette application démontre les technologies web modernes et les meilleures pratiques.' ?>
                </p>
                <a href="https://www.cyu.fr/" target="_blank" rel="noopener noreferrer" class="footer-cyu-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    CY Cergy Paris Université
                </a>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-subtitle"><?= ($lang === 'en') ? 'External Sources' : 'Sources Externes' ?></h4>
                <ul class="footer-sources">
                    <li>
                        <a href="https://data.economie.gouv.fr" target="_blank" rel="noopener noreferrer" class="footer-source-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <span>data.economie.gouv.fr</span>
                        </a>
                        <span class="source-desc"><?= ($lang === 'en') ? 'Official fuel prices API' : 'API officielle des prix des carburants' ?></span>
                    </li>
                    <li>
                        <a href="https://ipinfo.io" target="_blank" rel="noopener noreferrer" class="footer-source-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>ipinfo.io</span>
                        </a>
                        <span class="source-desc"><?= ($lang === 'en') ? 'IP geolocation service (JSON)' : 'Service de géolocalisation par IP (JSON)' ?></span>
                    </li>
                    <li>
                        <a href="https://whatsmyip.com" target="_blank" rel="noopener noreferrer" class="footer-source-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>whatsmyip.com</span>
                        </a>
                        <span class="source-desc"><?= ($lang === 'en') ? 'IP geolocation service (XML)' : 'Service de géolocalisation par IP (XML)' ?></span>
                    </li>
                    <li>
                        <a href="https://ghibliapi.vercel.app" target="_blank" rel="noopener noreferrer" class="footer-source-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                            <span>ghibliapi.vercel.app</span>
                        </a>
                        <span class="source-desc"><?= ($lang === 'en') ? 'Studio Ghibli API (demo)' : 'API Studio Ghibli (démo)' ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="footer-copyright">
                &#169; <?= date('Y') ?> RAHARIMANANA Tianantenaina &amp; BOUKIRAT Thafat
            </p>
            <p class="footer-version">
                <?= ($lang === 'en') ? 'Academic Year' : 'Année Universitaire' ?> 2025–2026
            </p>
            <p class="w3c-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <?= ($lang === 'en') ? 'W3C Validated' : 'Validé W3C' ?>
            </p>
        </div>
    </div>
</footer>
</body>
</html>
