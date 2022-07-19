<?php

require_once __DIR__ . '/shared.php';
require '/opt' . '/vendor/autoload.php';

use Aws\Comprehend\ComprehendClient;
use Aws\Credentials\CredentialProvider;

function detectSentiment($data) 
{
  $provider = CredentialProvider::defaultProvider();
  
  $options = [
    'credentials' => $provider,
    'region' => 'eu-west-1',
    'version' => 'latest'
  ];
  
  $comprehendClient = new ComprehendClient($options);

  $body = json_decode(base64_decode($data['body']), true);
  
  $textList = $body['texts'];
  
  $config = [
    'LanguageCode' => $body['languageCode'],
    'TextList' => $textList
  ];
  
  $awsResult = $comprehendClient->batchDetectSentiment($config);
  
  $resultList = [];
  
  foreach ($awsResult['ResultList'] as $result) {
      $result['Text'] = $textList[$result['Index']];
      $resultList[] = $result;
  }

  return APIResponse(json_encode($resultList));
}
