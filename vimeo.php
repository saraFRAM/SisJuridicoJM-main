<?php
  require 'vendor/autoload.php';
  use Vimeo\Vimeo;

  $client = new Vimeo("9ad486bf9706b597bc4894f6deb5ce178842ec62", "q//HOddW46Q5IdYhfGb9iQSjjK9zruGPv5rBYmSKgOZZUwrSCheVgc7jGs6JjQuEtYtQY+3EzcGKQJRxqSfFul9XLjlCYoT7zEi9jAg7H4AENbirgSAOaIwMrWlw1wh6", "66cbed272221be16ef192861b41df4d9");

  $response = $client->request('/tutorial', array(), 'GET');
  print_r($response);