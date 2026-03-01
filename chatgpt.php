<?php
// Pentest Proxy - Authorized Use Only
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD']==='OPTIONS'){exit('OK');}

$input = json_decode(file_get_contents('php://input'), true) ?: [];

$prompt = $input['messages'][0]['content'] ?? '';

// GitHub Pages PHP proxy to xylserver
$url = 'https://api.xylserver.org/ai.php?prompt='.urlencode($prompt).'&model=claude-sonnet-4-5-20250929';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$reply = $data['response'] ?? $response ?: 'Response unavailable';

echo json_encode([
    'id' => uniqid(),
    'choices' => [[
        'message' => ['role'=>'assistant','content'=>$reply]
    ]]
]);
?>
