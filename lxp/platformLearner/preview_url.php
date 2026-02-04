<?php
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_POST['url']) || !filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing URL'
    ]);
    exit;
}

$url = $_POST['url'];

// Fetch page safely
$context = stream_context_create([
    'http' => [
        'timeout' => 8,
        'user_agent' => 'AstraalLXP/1.0'
    ]
]);

$html = @file_get_contents($url, false, $context);

if (!$html) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unable to fetch the profile page'
    ]);
    exit;
}

function getMeta($html, $property) {
    if (preg_match('/property=["\']'.$property.'["\'] content=["\']([^"\']+)["\']/i', $html, $m)) {
        return trim($m[1]);
    }
    return '';
}

$preview = [
    'title'       => getMeta($html, 'og:title'),
    'description' => getMeta($html, 'og:description'),
    'image'       => getMeta($html, 'og:image')
];

echo json_encode([
    'status'  => 'success',
    'preview' => $preview
]);
exit;
