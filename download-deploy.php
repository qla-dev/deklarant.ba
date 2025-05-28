<?php
// deploy-download.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain'); // So output is readable even if there's an HTML template applied

// Load from environment or .env
$githubToken = $githubToken = trim(@file_get_contents('.github_token'));

if (!$githubToken) {
    http_response_code(500);
    die("Failed to load GitHub token from file.\n");
}

$artifactId = $_GET['artifact'] ?? null;
$destination = $_GET['dest'] ?? null;

if (!$artifactId || !$destination) {
    http_response_code(400);
    die("Missing artifact or dest parameter.\n");
}

$apiUrl = "https://api.github.com/repos/{OWNER}/{REPO}/actions/artifacts/$artifactId/zip";

// Replace {OWNER} and {REPO} with actual values
$apiUrl = str_replace(['{OWNER}', '{REPO}'], ['qla-dev', 'deklarant.ba'], $apiUrl);

// Download artifact
$tmpFile = tempnam(sys_get_temp_dir(), 'artifact_') . '.zip';

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'GitHub Action Deployer',
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $githubToken",
        "Accept: application/vnd.github+json"
    ],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_FILE => fopen($tmpFile, 'w'),
]);
$success = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$success || $httpCode !== 200) {
    http_response_code(500);
    echo "Failed to download artifact (HTTP $httpCode): $error\n";
    exit;
}

echo "Downloaded artifact to $tmpFile\n";

// Unzip
$escapedDest = escapeshellarg($destination);
$escapedFile = escapeshellarg($tmpFile);

$output = [];
$returnVar = 0;
exec("unzip -o $escapedFile -d $escapedDest", $output, $returnVar);

echo implode("\n", $output) . "\n";

if ($returnVar !== 0) {
    http_response_code(500);
    echo "Extraction failed with code $returnVar\n";
} else {
    echo "Extracted successfully to $destination\n";
}

exec("rm $escapedFile", $output, $returnVar);
$fileWithoutExtension = pathinfo($tmpFile, PATHINFO_DIRNAME) . '/' . pathinfo($tmpFile, PATHINFO_FILENAME);
$escapedFolder = escapeshellarg($fileWithoutExtension);
exec("rm -rf $escapedFolder", $output, $returnVar);