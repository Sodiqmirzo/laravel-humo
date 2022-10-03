<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 9/30/2022
 * Time: 3:11 PM
 */

namespace Uzbek\Humo\Models;

use Uzbek\Humo\Response\AccessGateway\SmsStatus;

class AccessGateway extends BaseModel
{
    public function smsStatus($holder_id, $bank_id): SmsStatus
    {
        $session_id = $this->getNewSessionID();
        $xml = "<soapenv:Envelope
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:urn=\"urn:AccessGateway\">
	<soapenv:Header/>
	<soapenv:Body>
		<urn:export>
			<cardholderID>{$holder_id}-{$bank_id}</cardholderID>
			<bankId>MB_STD</bankId>
		</urn:export>
	</soapenv:Body>
</soapenv:Envelope>";

        return new SmsStatus($this->sendXmlRequest('13010', $xml, $session_id, 'smsStatus'));
    }

    public function smsOn(string $holderName, string $holderID, string $card_number, string $card_expiry, string $phone): bool
    {
        $bank_c = substr($card_number, 4, 2);

        $xml = "<SOAP-ENV:Envelope
  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
  xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
  xmlns:ag=\"urn:AccessGateway\">
  <SOAP-ENV:Body>
    <ag:import>
      <bankId>MB_STD</bankId>
      <cardholderName>{$holderName}</cardholderName>
      <cardholderID>{$holderID}-{$bank_c}</cardholderID>
      <state>on</state>
      <language>ru_translit</language>
      <Charge>
        <agreementCharge>MONTH.FEE.OFF</agreementCharge>
        <chargeAccount></chargeAccount>
      </Charge>
      <Card>
        <state>on</state>
        <pan>{$card_number}</pan>
        <expiry>{$card_expiry}</expiry>
        <Service>
          <serviceID>MB-ALL</serviceID>
          <serviceChannel>-</serviceChannel>
        </Service>
      </Card>
      <Phone>
        <state>on</state>
        <msisdn>{$phone}</msisdn>
        <deliveryChannel>-</deliveryChannel>
      </Phone>
    </ag:import>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        $result = $this->sendXmlRequest('13010', $xml, $this->getNewSessionID(), 'smsOn');

        if (isset($result['importResponse']) && is_array($result['importResponse'])) {
            return empty($result['importResponse']);
        }

        return false;
    }
}
