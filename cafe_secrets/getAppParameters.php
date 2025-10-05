<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//require 'AWSSDK/aws.phar';
// Use Composer autoloader instead of PHAR
require 'vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
//Get the application environment parameters from the Parameter Store.

$az = file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone');
$region = substr($az, 0, -1);

$secrets_client = new Aws\SecretsManager\SecretsManagerClient([
  'version' => 'latest',
  'region'  => $region
]);

$secretName = "AnyGroup-RDSSecret";
  $db_url = '';
  $db_name = '';
  $db_user = '';
  $db_password = '';
  $showServerInfo = true;
  $timeZone = '';
  $currency = '';
try {
    $result = $secrets_client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);
        $db_url = $secret['host'];
        $db_name = $secret['dbname'];
        $db_user = $secret['username'];
        $db_password = $secret['password'];

  //      echo "DB host: $db_url\n";
  //      echo "DB user: $db_user\n";
//      echo "DB name: $db_name\n";
    }
} catch (AwsException $e) {
    echo "Error retrieving secret: " . $e->getMessage();
}
#error_log('Settings are: ' . $ep. " / " . $db_name . " / " . $db_user . " / " . $db_password);
?>
