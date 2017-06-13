<?php

$app->post('/api/USPS/getDeliveryStatus', function ($request, $response) {
    
    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['userId','trackId']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $userId = $post_data['args']['userId'];
    $xml = new SimpleXMLElement('<TrackRequest/>');
    $xml->addAttribute('USERID', $userId);

    if(is_array($post_data['args']['trackId'])){
        foreach($post_data['args']['trackId'] as $trackId){
            $track = $xml->addChild('TrackID');
            $track->addAttribute('ID', $trackId);
        }
    } else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = "REQUIRED_FIELDS";
        $result['contextWrites']['to']['status_msg'] = "Please, check and fill trackId field.";
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }

    $xmlString = $xml->asXML();

    $query_str = "http://production.shippingapis.com/ShippingAPI.dll";
    $client = $this->httpClient;

    $data['API'] = "TrackV2";
    $data['XML'] = $xmlString;

    try {
        $resp = $client->get($query_str, [
            'query' => $data
        ]);
        $responseBody = $resp->getBody()->getContents();
        $xmlResponse = simplexml_load_string($responseBody);
        $responseBody = json_encode($xmlResponse);

        if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
            if(empty($result['contextWrites']['to'])) {
                $result['contextWrites']['to']['status_msg'] = "Api return no results";
            }
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    
});
