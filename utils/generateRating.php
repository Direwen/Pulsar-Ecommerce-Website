<?php

function generateRating($total_ratings, $rating_count) {
    // Step 1: Calculate the base rating (1-5 scale)
    if ($rating_count === 0) {
        return 0; // Avoid division by zero
    }

    $base_rating = $total_ratings / $rating_count;

    // Step 2: Adjust to create an odd decimal rating
    $odd_decimals = [-0.1, -0.3, -0.5, -0.7, -0.9]; // Adjust downwards
    $random_adjustment = $odd_decimals[array_rand($odd_decimals)];

    // Step 3: Apply adjustment while ensuring the value remains in the 1-5 range
    $adjusted_rating = min(5, max(1, $base_rating + $random_adjustment));

    // Step 4: Round to 1 decimal place
    return round($adjusted_rating, 1);
}

