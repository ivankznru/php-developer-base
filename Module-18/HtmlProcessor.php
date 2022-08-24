
<?php

$json = file_get_contents('php://input');
if ($data = json_decode($json)) {
    $data = $data->raw_text;
} else {
    http_response_code(500);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pattern = '/\<a\s.*?\>(.*?)\<\/a\>/iums';
    $formatted_text = preg_replace($pattern, '$1', $data);
    $json = [
        'formatted_text' => $formatted_text,
    ];
    header('Content-Type: application/json');
    echo json_encode($json);
}
