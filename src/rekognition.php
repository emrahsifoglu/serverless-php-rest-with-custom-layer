<?php

require_once __DIR__ . '/shared.php';
require_once __DIR__ . '/functions.php';
require '/opt' . '/vendor/autoload.php';

use Aws\Rekognition\RekognitionClient;
use Aws\Credentials\CredentialProvider;

function detectLabels($data)
{
  $rekognitionClient = getRekognitionClient();

  $result = $rekognitionClient->detectLabels([
    'Image' => [
        'Bytes' => extractFile($data)
    ],
    'MinConfidence' => 50
  ])['Labels'];

  return APIResponse(json_encode($result));
}

function detectModerationLabels($data)
{
  $rekognitionClient = getRekognitionClient();

  $result = $rekognitionClient->detectModerationLabels([
    'Image' => [
        'Bytes' => extractFile($data)
    ],
    'MinConfidence' => 50
  ])['ModerationLabels'];

  return APIResponse(json_encode($result));
}

function extractFile($data)
{
  $body = $data['body'];
  $contentType = $data['headers']['Content-Type'];

  preg_match('/boundary=(.*)$/', $contentType, $matches);
  $boundary = $matches[1];
  
  $response = parseMultipartContent(base64_decode($body), $boundary);
  $bytes = $response['0']['value'];

  return $bytes;
}

function getRekognitionClient()
{
  $provider = CredentialProvider::defaultProvider();

  return new RekognitionClient([
    'credentials' => $provider,
    'region' => 'eu-west-1',
    'version' => 'latest'
  ]);
}
