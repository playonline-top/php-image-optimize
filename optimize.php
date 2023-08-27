<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

// Only allow GET requests to be sent
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    die('This endpoint only allows for GET requests!');
}

// Polyfill str_contains for PHP versions < 8
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

// Headers: search engines, block cross origin
header("X-Robots-Tag: noindex");
header("Cross-Origin-Resource-Policy: same-site");

// Require an image
if ($_GET['url'] == null) {
    http_response_code(400);
    die("An image URL must be provided");
}

// Limit to GameMonetize URLs
if (!str_contains($_GET['url'], 'https://img.gamemonetize.com/')) {
    http_response_code(400);
    die("You are only allowed to provide GameMonetize URLs!");
}

// Wrap everything in a try-catch
try {
    // Construct image object
    $gdimage = imagecreatefromjpeg($_GET['url']);
    
    if (!$gdimage) {
        $gdimage = imagecreatefromstring(file_get_contents($_GET['url']));
    }

    // Catch if object doesn't construct
    if ($gdimage == false) {
        http_response_code(500);
        die("An error occurred while optimising the image.");
    }

    // Scale image down to 256x192
    $output = imagescale($gdimage, 256);

    // Headers: image type, caching
    header("Content-Type: image/webp");
    header("Cache-Control: public; must-revalidate; max-age=86400; stale-while-revalidate=604800; stale-if-error=604800");

    // Serve the image with 60% quality
    imagewebp($output, null, 60);
} catch (Exception $e) {
    // Catch any errors with generic 500 response
    http_response_code(500);
    die("An error occurred while optimising the image.");
}

?>
