<?php
// Router script for `php -S`: one browser tab, two app copies. Every request goes to both copies, so both
// record the same traffic. The browser gets the answer of the plain copy. It lives outside the apps on purpose:
// anything inside them is read by the agent and would change the run without Mate.
$targets = array_filter([getenv('PLAIN_PORT') ?: '8130', getenv('MATE_PORT') ?: '8131']);
if (str_starts_with($_SERVER['REQUEST_URI'], '/_')) {
    $targets = [reset($targets)];   // toolbar and profiler pages belong to the copy that answered
}

$headers = [];
foreach (getallheaders() as $name => $value) {
    if (!in_array(strtolower($name), ['content-length', 'connection', 'accept-encoding'], true)) {
        $headers[] = "$name: $value";
    }
}
$body = file_get_contents('php://input');

$multi = curl_multi_init();
$handles = [];
foreach ($targets as $port) {
    $ch = curl_init("http://127.0.0.1:$port".$_SERVER['REQUEST_URI']);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $_SERVER['REQUEST_METHOD'],
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_TIMEOUT => 20,
    ]);
    if ('' !== $body) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    curl_multi_add_handle($multi, $ch);
    $handles[] = $ch;
}
do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi, 0.1);
} while ($running > 0);

$ch = $handles[0];
$raw = (string) curl_multi_getcontent($ch);
$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
if (0 === $status) {
    http_response_code(502);
    echo 'Demo app is not running. Start it with ./start.sh';

    return true;
}
$size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
http_response_code($status);
foreach (explode("\r\n", substr($raw, 0, $size)) as $line) {
    if (preg_match('/^(content-type|location|set-cookie|cache-control|x-debug-token|x-debug-token-link):/i', $line)) {
        header($line, false);
    }
}
$html = substr($raw, $size);
// Stage comfort: fill the fields the speaker would type anyway. Done here and not in the app, where the agent
// would read the failing value straight from the code.
$prefill = ['signup_email' => 'johannes@example.org', 'signup_username' => 'Johannes_W'];
if ('GET' === $_SERVER['REQUEST_METHOD'] && str_contains($html, 'id="signup_email"')) {
    $script = '<script>for (const [id, v] of Object.entries('.json_encode($prefill).')) { const f = document.getElementById(id); if (f && !f.value) f.value = v; }</script>';
    $html = str_replace('</main>', '</main>'.$script, $html);
}
echo $html;

return true;
