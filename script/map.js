/**
 * Script permettant de créer une superposition SVG sur la carte image
 */
document.addEventListener('DOMContentLoaded', function() {

    // Récupère le SVG pour afficher les formes
    const overlay = document.getElementById('map-overlay');

    // Récupère toutes les zones cliquables de la carte (balises <area>)
    const areas = document.querySelectorAll('map[name="carte-france"] area');
    
    // Pour chaque région (area)
    areas.forEach(function(area) {

        // Récupère les coordonnées (x,y) du polygone sous forme de tableau de nombres
        const coords = area.getAttribute('coords').split(',').map(Number);
        
        // Tableau pour stocker les points au format "x,y"
        const points = [];

        // Variables pour calculer le centre de la région
        let sumX = 0, sumY = 0;

        // Parcourt les coordonnées 2 par 2 (x puis y)
        for (let i = 0; i < coords.length; i += 2) {

            // Ajoute le point sous forme x,y
            points.push(coords[i] + ',' + coords[i + 1]);

            // Additionne les coordonnées pour calculer le centre
            sumX += coords[i];
            sumY += coords[i + 1];
        }

        // Nombre total de points
        const nbPoints = coords.length / 2;

        // Calcul du centre (moyenne des points)
        const centerX = sumX / nbPoints;
        const centerY = sumY / nbPoints;

        // Création du polygone SVG correspondant à la région
        const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');

        // Définition des points du polygone
        polygon.setAttribute('points', points.join(' '));

        // Ajoute un attribut personnalisé pour identifier la région
        polygon.setAttribute('data-region', area.getAttribute('data-region'));

        // Ajoute le polygone dans le SVG
        overlay.appendChild(polygon);

        // Nom de la région)
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        // Personnalisation du texte
        text.setAttribute('x', centerX);
        text.setAttribute('y', centerY);

        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('dominant-baseline', 'middle');

        text.setAttribute('class', 'region-label');

        text.setAttribute('pointer-events', 'none');

        // Affiche le nom de la région 
        text.textContent = area.getAttribute('title');

        // Ajoute le texte dans le SVG
        overlay.appendChild(text);

        // Ajout de classe quand on surligne
        area.addEventListener('mouseenter', function() {
            polygon.classList.add('active');
        });
        
        // Retrait sinon
        area.addEventListener('mouseleave', function() {
            polygon.classList.remove('active');
        });
    });
});