<?php
$routes = [
    'metadata',
    'getDeliveryStatus',
    'setEmailForTrackingNotification',
    'getProofOfDeliveryNotification',
    'getProofOfDeliveryCopy'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

