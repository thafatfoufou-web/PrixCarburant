<?php

    require_once("../include/config.inc.php");

    $title = ($lang === 'en') ? 'Contact' : 'Contact';
    $h1 = ($lang === 'en') ? 'Contact Us' : 'Contactez-nous';

    $form_data = [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => ''
    ];
    
    $form_errors = [];
    $form_success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form_data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? '')
        ];
        // Vérification du nom
        if (empty($form_data['name'])) {
            $form_errors['name'] = ($lang === 'en') ? 'Name is required' : 'Le nom est requis';
        } elseif (strlen($form_data['name']) < 2) {
            $form_errors['name'] = ($lang === 'en') ? 'Name must be at least 2 characters' : 'Le nom doit contenir au moins 2 caractères';
        }

        //Vérification de l'email
        if (empty($form_data['email'])) {
            $form_errors['email'] = ($lang === 'en') ? 'Email is required' : 'L\'email est requis';
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $form_errors['email'] = ($lang === 'en') ? 'Please enter a valid email address' : 'Veuillez entrer une adresse email valide';
        }

        //Vérification du sujet
        if (empty($form_data['subject'])) {
            $form_errors['subject'] = ($lang === 'en') ? 'Subject is required' : 'Le sujet est requis';
        } elseif (strlen($form_data['subject']) < 3) {
            $form_errors['subject'] = ($lang === 'en') ? 'Subject must be at least 3 characters' : 'Le sujet doit contenir au moins 3 caractères';
        }

        //Vérification du message
        if (empty($form_data['message'])) {
            $form_errors['message'] = ($lang === 'en') ? 'Message is required' : 'Le message est requis';
        } elseif (strlen($form_data['message']) < 10) {
            $form_errors['message'] = ($lang === 'en') ? 'Message must be at least 10 characters' : 'Le message doit contenir au moins 10 caractères';
        } elseif (strlen($form_data['message']) > 2000) {
            $form_errors['message'] = ($lang === 'en') ? 'Message must not exceed 2000 characters' : 'Le message ne doit pas dépasser 2000 caractères';
        }

        //Si il y a aucune erreur
        if (empty($form_errors)) {            
            $contact_file = "../data/contact_messages.csv";
            $contact_headers = ['datetime', 'name', 'email', 'subject', 'message', 'ip_address'];
            $timestamp = date('Y-m-d H:i:s');
            $contact_data = [
                $timestamp,
                $form_data['name'],
                $form_data['email'],
                $form_data['subject'],
                $form_data['message'],
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            if (append_to_csv( $contact_file, $contact_data, $contact_headers)) {
                $form_success = true;
                $form_data = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
            } else {
                $form_errors['general'] = ($lang === 'en') 
                    ? 'An error occurred while sending your message. Please try again.' 
                    : 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
            }
        }
    }

    require_once("../include/header.inc.php");
?>

<section class="page-head">
    <div class="page-head-content">
        <h1 class="page-title">
            <?= ($lang === 'en') ? 'Get in Touch' : 'Contactez-nous' ?>
        </h1>
        <p class="page-subtitle">
            <?= ($lang === 'en') 
                ? 'We value your feedback and suggestions. Send us a message about fuel prices, site features, or any questions you may have.'
                : 'Nous apprécions vos commentaires et suggestions. Envoyez-nous un message concernant les prix des carburants, les fonctionnalités du site ou toute question que vous pourriez avoir.' ?>
        </p>
    </div>
</section>

<section class="contact-section">
    <div class="contact-container">
        <div class="contact-form-wrapper">
            <?php if ($form_success): ?>
                <div class="alert alert-success">
                    <div class="alert-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="alert-content">
                        <h3 class="alert-title">
                            <?= ($lang === 'en') ? 'Message Sent Successfully!' : 'Message Envoyé avec Succès!' ?>
                        </h3>
                        <p class="alert-message">
                            <?= ($lang === 'en') 
                                ? 'Thank you for contacting us. We will get back to you as soon as possible.' 
                                : 'Merci de nous avoir contactés. Nous vous répondrons dans les plus brefs délais.' ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($form_errors['general'])): ?>
                <div class="alert alert-error">
                    <div class="alert-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="alert-content">
                        <p class="alert-message"><?= htmlspecialchars($form_errors['general']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="contact-form" enctype="multipart/form-data" novalidate="novalidate">
                <div class="form-group">
                    <label for="name" class="form-label">
                        <?= ($lang === 'en') ? 'Name' : 'Nom' ?> *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input <?= !empty($form_errors['name']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($form_data['name']) ?>"
                        required="required"
                        maxlength="100"
                        placeholder="<?= ($lang === 'en') ? 'Enter your full name' : 'Entrez votre nom complet' ?>"
                        <?= !empty($form_errors['name']) ? 'aria-describedby="name-error" aria-invalid="true"' : 'aria-invalid="false"' ?>
                    />
                    <?php if (!empty($form_errors['name'])): ?>
                        <div id="name-error" class="form-error" role="alert">
                            <?= htmlspecialchars($form_errors['name']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <?= ($lang === 'en') ? 'Email' : 'Email' ?> *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input <?= !empty($form_errors['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($form_data['email']) ?>"
                        required="required"
                        maxlength="255"
                        placeholder="<?= ($lang === 'en') ? 'your.email@example.com' : 'votre.email@exemple.com' ?>"
                        <?= !empty($form_errors['email']) ? 'aria-describedby="email-error" aria-invalid="true"' : 'aria-invalid="false"' ?>
                    />
                    <?php if (!empty($form_errors['email'])): ?>
                        <div id="email-error" class="form-error" role="alert">
                            <?= htmlspecialchars($form_errors['email']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">
                        <?= ($lang === 'en') ? 'Subject' : 'Sujet' ?> *
                    </label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        class="form-input <?= !empty($form_errors['subject']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($form_data['subject']) ?>"
                        required="required"
                        maxlength="200"
                        placeholder="<?= ($lang === 'en') ? 'What is your message about?' : 'De quoi traite votre message ?' ?>"
                        <?= !empty($form_errors['subject']) ? 'aria-describedby="subject-error" aria-invalid="true"' : 'aria-invalid="false"' ?>
                    />
                    <?php if (!empty($form_errors['subject'])): ?>
                        <div id="subject-error" class="form-error" role="alert">
                            <?= htmlspecialchars($form_errors['subject']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">
                        <?= ($lang === 'en') ? 'Message' : 'Message' ?> *
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        class="form-textarea <?= !empty($form_errors['message']) ? 'is-invalid' : '' ?>"
                        required="required"
                        maxlength="2000"
                        rows="6"
                        placeholder="<?= ($lang === 'en') ? 'Tell us more about your inquiry, feedback, or suggestion...' : 'Dites-nous en plus sur votre demande, commentaire ou suggestion...' ?>"
                        <?= !empty($form_errors['message']) ? 'aria-describedby="message-error" aria-invalid="true"' : 'aria-invalid="false"' ?>
                    ><?= htmlspecialchars($form_data['message']) ?></textarea>
                    <div id="message-help" class="form-help" aria-live="polite">
                        <span class="character-count">
                            <span id="char-count"><?= strlen($form_data['message']) ?></span>/2000
                        </span>
                    </div>
                    <?php if (!empty($form_errors['message'])): ?>
                        <div id="message-error" class="form-error" role="alert">
                            <?= htmlspecialchars($form_errors['message']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="form-submit-btn">
                        <?= ($lang === 'en') ? 'Send Message' : 'Envoyer le Message' ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="contact-info">
            <div class="contact-info-card">
                <h2 class="contact-info-title">
                    <?= ($lang === 'en') ? 'Other Ways to Reach Us' : 'Autres Moyens de Nous Contacter' ?>
                </h2>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <h3 class="contact-info-label">
                            <?= ($lang === 'en') ? 'Email' : 'Email' ?>
                        </h3>
                        <p class="contact-info-value">
                            contact@prixcarburants.fr
                        </p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <h3 class="contact-info-label">
                            <?= ($lang === 'en') ? 'Location' : 'Localisation' ?>
                        </h3>
                        <p class="contact-info-value">
                            <?= ($lang === 'en') ? 'France' : 'France' ?>
                        </p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <h3 class="contact-info-label">
                            <?= ($lang === 'en') ? 'Response Time' : 'Temps de Réponse' ?>
                        </h3>
                        <p class="contact-info-value">
                            <?= ($lang === 'en') ? 'Usually within 24-48 hours' : 'Généralement dans les 24-48 heures' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= $base_path ?>script/contact.js" defer="defer"></script>
<?php
    require_once("../include/footer.inc.php");
?>
