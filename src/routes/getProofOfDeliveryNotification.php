<?php

$app->post('/api/USPS/getProofOfDeliveryNotification', function ($request, $response) {
    
    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['userId','trackId','mpSuffix','mpDate','requestType','email','tableCode']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $userId = $post_data['args']['userId'];
    $trackId = $post_data['args']['trackId'];
    $mpSuffix = $post_data['args']['mpSuffix'];
    $mpDate = $post_data['args']['mpDate'];
    $requestType = $post_data['args']['requestType'];
    $email = $post_data['args']['email'];
    $tableCode = $post_data['args']['tableCode'];


    $xml = new SimpleXMLElement('<PTSEmailRequest/>');
    $xml->addAttribute('USERID', $userId);
    $trackIdXml = $xml->addChild('TrackId', $trackId);
    $mpSuffixXml = $xml->addChild('MpSuffix', $mpSuffix);
    $mpDateXml = $xml->addChild('MpDate', $mpDate);
    $requestTypeXml = $xml->addChild('RequestType', $requestType);
    $emailXml = $xml->addChild('Email1', $email);
    $tableCodeXml = $xml->addChild('TableCode', $tableCode);

    if(!empty($post_data['args']['clientIp'])){
        $clientIpXml = $xml->addChild('ClientIp', $post_data['args']['clientIp']);
    }

    if(!empty($post_data['args']['firstName'])){
        $firstNameXml = $xml->addChild('FirstName', $post_data['args']['firstName']);
    }

    if(!empty($post_data['args']['lastName'])){
        $lastNameXml = $xml->addChild('LastName', $post_data['args']['lastName']);
    }
    
    $xmlString = $xml->asXML();

    $query_str = "https://stg-secure.shippingapis.com/ShippingAPI.dll";
    $client = $this->httpClient;

    $data['API'] = "PTSPod";
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
