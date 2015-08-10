<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/19/2015
 * Time: 1:26 AM
 */

$locationsUrl     = 'http://locator.wizards.com/Service/LocationService.svc/GetLocations';
$eventTypes       = array(
    'GT',
    'PPTQ'
);
$salesBrandCode   = array(//    'MG'
);
$productLineCodes = array(//    'MG'
);
$payload          = array(
    'language'            => "en-us",
    'request'             => array(
        'North'                  => 47.43051911539,
        'East'                   => -122.15397811701,
        'South'                  => 46.98346908461,
        'West'                   => -122.81202808299,
        'LocalTime'              => "/Date(" . getDateForRequest() . ")/",
        'ProductLineCodes'       => $productLineCodes,
        'EventTypeCodes'         => $eventTypes,
        'PlayFormatCodes'        => array(),
        'SalesBrandCodes'        => $salesBrandCode,
        'MarketingProgramCodes'  => array(),
        'EarliestEventStartDate' => null,
        'LatestEventStartDate'   => null
    ),
    'page'                => 1,
    'count'               => 30,
    'filter_mass_markets' => true,
);

?>
<h3>
    Events for <?php echo implode(', ', $eventTypes); ?>
</h3>
<?php
$stores = performRequest($locationsUrl, $payload);
foreach ($stores['d']['Results'] as $store) {
    ?>
    Store: <?php echo $store['Organization']['Name']; ?><br/>
    Id: <?php echo $store['Organization']['Id']; ?><br/>
    Phone: <?php echo $store['Organization']['Phone']; ?><br/>
    Email: <?php echo $store['Organization']['Email']; ?><br/>
    URL: <?php echo $store['Organization']['PrimaryUrl']; ?><br/>
    Address:
    <?php echo $store['Address']['Line1']; ?><br/>
    <?php if ($store['Address']['Line2']): ?><?php echo $store['Address']['Line2']; ?><br/><?php endif; ?>
    <?php if ($store['Address']['Line3']): ?><?php echo $store['Address']['Line3']; ?><br/><?php endif; ?>
    <?php echo $store['Address']['City']; ?>,
    <?php echo $store['Address']['StateProvinceCode']; ?>
    <?php echo $store['Address']['PostalCode']; ?>
    <br/>
    <?php echo printEventDetails($eventTypes, $store['Address']['Id'], $store['Organization']['Id']); ?>
    <br/>
    <?php
}

function printEventDetails($eventTypes, $addressId, $organizationId)
{
    $url = 'http://locator.wizards.com/Service/LocationService.svc/GetLocationDetails';

    $params = array(
        'language' => "en-us",
        'request'  => array(
            'BusinessAddressId'      => $addressId,
            'OrganizationId'         => $organizationId,
            'EventTypeCodes'         => $eventTypes,
            'PlayFormatCodes'        => array(),
            'ProductLineCodes'       => array(),
            'LocalTime'              => '/Date(' . getDateForRequest() . ')/',
            'EarliestEventStartDate' => null,
            'LatestEventStartDate'   => null
        )
    );

    $data = performRequest($url, $params);
    $Info = '';
    foreach($data['d']['Result']['EventsAtVenue'] as $event) {
        if(in_array($event['EventTypeCode'], $eventTypes)) {
            preg_match('/(\d+)/', $event['StartDate'], $matches);
            $Info .= 'Event: ' . $event['Name'];
            $Info .= ' Date: ' . date('Y-m-d', $matches[0]/1000);
            $Info .= ' Format: ' . $event['PlayFormatCode'];
            $Info .= '<br />';
        }
    }
    return $Info;
}

function getDateForRequest()
{
    return ((int)(microtime(true) * 1000));
}


/**
 * @param $locationsUrl
 * @param $payload
 * @return array
 */
function performRequest($locationsUrl, $payload)
{
    $curl = curl_init($locationsUrl);
    curl_setopt_array($curl, array(
                               CURLOPT_POST           => 1,
                               CURLOPT_POSTFIELDS     => json_encode($payload),
                               CURLOPT_RETURNTRANSFER => 1,
                               CURLOPT_HTTPHEADER     => array(
                                   'Content-Type: application/json',
                                   'Content-Length: ' . strlen(json_encode($payload))
                               )
                           )
    );

//    echo json_encode($payload).'<br />';
    $result = curl_exec($curl);
    $data = json_decode($result, true);
    if(!$data) {
        die($result);
    }
    return $data;
}