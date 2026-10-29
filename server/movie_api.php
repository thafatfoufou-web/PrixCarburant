<?php
    /**
     * Récupération d'un film aléatoire du Studio Ghibli via API
     * */
    $api_url = "https://ghibliapi.vercel.app/films";
    $api_response = file_get_contents($api_url);
    $api_data = json_decode($api_response, true);
    $selected_movie = $api_data[array_rand($api_data)];

    $movie_title = $selected_movie['title'];
    $movie_title_original = $selected_movie['original_title'];
    $movie_description = $selected_movie['description'];
    $release_date = $selected_movie['release_date'];
    $movie_year = substr($release_date, 0, 4);
    $movie_poster_url = $selected_movie['image'];
    $movie_banner_url = $selected_movie['movie_banner'];
?>
