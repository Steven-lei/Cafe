<?php

require 'AWSSDK/aws.phar';

//Get the application environment parameters from the Parameter Store.

$az = file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone');
$region = substr($az, 0, -1);

$secrets_client = new Aws\SecretsManager\SecretsManagerClient([
  'version' => 'latest',
  'region'  => $region
]);

$showServerInfo = "";
$timeZone = "";
$currency = "";
$db_url = "";
$db_name = "";
$db_user = "";
$db_password = "";

try {
  $db_url = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/dbUrl'
  ]);
  $db_url = $db_url["SecretString"];
  $db_user = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/dbUser'
  ]);
  $db_user = $db_user["SecretString"];
  $db_password = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/dbPassword'
  ]);
  $db_password = $db_password["SecretString"];
  $db_name = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/dbName'
  ]);
  $db_name = $db_name["SecretString"];
  $currency = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/currency'
  ]);
  $currency = $currency["SecretString"];  
  $timezone = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/timeZone'
  ]);
  $timezone = $timezone["SecretString"];  
  $showServerInfo = $secrets_client->getSecretValue([
    'SecretId' => '/cafe/showServerInfo'
  ]);
  $showServerInfo = $showServerInfo["SecretString"];  

}
catch (Exception $e) {
  $db_url = '';
  $db_name = '';
  $db_user = '';
  $db_password = '';
  $showServerInfo = '';
  $timeZone = '';
  $currency = '';
}
#error_log('Settings are: ' . $ep. " / " . $db_name . " / " . $db_user . " / " . $db_password);
?>
