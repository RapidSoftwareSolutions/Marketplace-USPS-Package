[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/USPS/functions?utm_source=RapidAPIGitHub_USPSFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)
# USPS Package

* Domain: [usps.com](https://usps.com)
* Credentials: userId

## How to get credentials: 
1. Sign in [usps.com](https://www.usps.com/)
2. Navigate to [Web tools api portal](https://www.usps.com/business/web-tools-apis/welcome.htm)
3. Create developer account

## Custom datatypes:
 |Datatype|Description|Example
 |--------|-----------|----------
 |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
 |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
 |List|Simple array|```["123", "sample"]```
 |Select|String with predefined values|```sample```
 |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```

## USPS.getDeliveryStatus
Lets customers determine the delivery status of their Priority Mail, Express Mail, and Package Services.

| Field  | Type       | Description
|--------|------------|----------
| userId | credentials| Your application's bitly client id.
| trackId| List       | List of track id.

## USPS.setEmailForTrackingNotification
Allows the customer to submit their email address to be notified of current or future tracking activity.

| Field      | Type       | Description
|------------|------------|----------
| userId     | credentials| Your application's bitly client id.
| trackId    | String     | Must be alphanumeric characters.
| clientIp   | String     | User IP address.
| mpSuffix   | String     | MPSUFFIX value located in Track/Confirm Fields API response data. Unique to each TrackID.
| mpDate     | String     | MPDATE value located in Track/Confirm Fields API response data. Unique to each TrackId.
| requestType| Select     | Enter a notification request type from the choices available. 'EC' – (Email Current) Email all activity to-date. 'EN' – (Email New) Email all future tracking activity. 'EB' – (Email Both) Email both activity to-date and future tracking activity. 'ED' – E-Mail Delivery/Non Delivery activity
| firstName  | String     | Recipient First Name.
| lastName   | String     | Recipient Last Name.
| email      | String     | Complete valid e-mail address.

## USPS.getProofOfDeliveryNotification
The Proof of Delivery API allows the customer to request proof of delivery notification via email.

| Field      | Type       | Description
|------------|------------|----------
| userId     | credentials| Your application's bitly client id.
| trackId    | String     | Must be alphanumeric characters.
| clientIp   | String     | User IP address.
| mpSuffix   | String     | MPSUFFIX value located in Track/Confirm Fields API response data. Unique to each TrackID.
| mpDate     | String     | MPDATE value located in Track/Confirm Fields API response data. Unique to each TrackId.
| requestType| Select     | Enter a notification request type from the choices available. 'EC' – (Email Current) Email all activity to-date. 'EN' – (Email New) Email all future tracking activity. 'EB' – (Email Both) Email both activity to-date and future tracking activity. 'ED' – E-Mail Delivery/Non Delivery activity
| firstName  | String     | Recipient First Name.
| lastName   | String     | Recipient Last Name.
| email      | String     | Complete valid e-mail address.
| tableCode  | String     | Table Code value located in Track/Confirm Fields API response data. Unique to each TrackID.

## USPS.getProofOfDeliveryCopy
The Return Receipt Electronic API allows the customer to request a copy of the proof of delivery record via email.

| Field    | Type       | Description
|----------|------------|----------
| userId   | credentials| Your application's bitly client id.
| trackId  | String     | Must be alphanumeric characters.
| clientIp | String     | User IP address.
| mpSuffix | String     | MPSUFFIX value located in Track/Confirm Fields API response data. Unique to each TrackID.
| mpDate   | String     | MPDATE value located in Track/Confirm Fields API response data. Unique to each TrackId.
| firstName| String     | Recipient First Name.
| lastName | String     | Recipient Last Name.
| email    | String     | Complete valid e-mail address.
| tableCode| String     | Table Code value located in Track/Confirm Fields API response data. Unique to each TrackID.

