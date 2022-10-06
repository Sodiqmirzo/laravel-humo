<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/19/2022
 * Time: 11:16 AM
 */

namespace Uzbek\Humo\Models;

use Uzbek\Humo\Dtos\Payment\OwnerPassportDTO;
use Uzbek\Humo\Dtos\Payment\P2PCreateDTO;
use Uzbek\Humo\Exceptions\AccessGatewayException;
use Uzbek\Humo\Exceptions\ClientException;
use Uzbek\Humo\Exceptions\ConnectionException;
use Uzbek\Humo\Exceptions\ExceededAmountException;
use Uzbek\Humo\Exceptions\Exception;
use Uzbek\Humo\Exceptions\TimeoutException;
use Uzbek\Humo\Response\BaseResponse;
use Uzbek\Humo\Response\Payment\Cancel;
use Uzbek\Humo\Response\Payment\Confirm;
use Uzbek\Humo\Response\Payment\Create;
use Uzbek\Humo\Response\Payment\P2PCreate;
use Uzbek\Humo\Response\Payment\PaymentReturn;
use Uzbek\Humo\Response\Payment\RecoConfirm;
use Uzbek\Humo\Response\Payment\RecoCreate;

class Payment extends BaseModel
{

    /**
     * @param string $session_id
     * @param string $pan
     * @param string $expiry
     * @param int $amount
     * @param string $merchant_id
     * @param string $terminal_id
     *
     * @return Create
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function create(
        string $session_id,
        string $pan,
        string $expiry,
        int    $amount,
        string $merchant_id,
        string $terminal_id
    ): Create
    {
        $xml = "<SOAP-ENV:Envelope
	xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
	xmlns:ebppif1=\"urn:PaymentServer\">
	<SOAP-ENV:Header/>
	<SOAP-ENV:Body>
		<ebppif1:Payment>
			<billerRef>SOAP_SMS</billerRef>
			<payinstrRef>SOAP_SMS</payinstrRef>
			<sessionID>{$session_id}</sessionID>
			<paymentRef>{$session_id}</paymentRef>
			<details>
				<item>
					<name>pan</name>
					<value>{$pan}</value>
				</item>
				<item>
					<name>expiry</name>
					<value>{$expiry}</value>
				</item>
				<item>
					<name>ccy_code</name>
					<value>860</value>
				</item>
				<item>
					<name>amount</name>
					<value>{$amount}</value>
				</item>
				<item>
					<name>merchant_id</name>
					<value>{$merchant_id}</value>
				</item>
				<item>
					<name>terminal_id</name>
					<value>{$terminal_id}</value>
				</item>
				<item>
					<name>point_code</name>
					<value>100001104110</value>
				</item>
				<item>
					<name>centre_id</name>
					<value>{$this->centre_id}</value>
				</item>
			</details>
			<paymentOriginator>{$this->originator}</paymentOriginator>
		</ebppif1:Payment>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new Create($this->sendXmlRequest('11210', $xml, $session_id, 'payment.create'));
    }

    public function hold(
        string $session_id,
        string $pan,
        string $expiry,
        int    $amount,
        string $merchant_id,
        string $terminal_id
    ): Create
    {
        $xml = "<SOAP-ENV:Envelope
	xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:ebppif1=\"urn:PaymentServer\">
	<SOAP-ENV:Body>
		<ebppif1:Payment>
			<billerRef>SOAP_DMS</billerRef>
			<payinstrRef>SOAP_DMS</payinstrRef>
			<sessionID>{$session_id}</sessionID>
			<paymentRef>{$session_id}</paymentRef>
			<details>
				<item>
					<name>pan</name>
					<value>{$pan}</value>
				</item>
				<item>
					<name>expiry</name>
					<value>{$expiry}</value>
				</item>
				<item>
					<name>ccy_code</name>
					<value>860</value>
				</item>
				<item>
					<name>amount</name>
					<value>{$amount}</value>
				</item>
				<item>
					<name>merchant_id</name>
					<value>{$merchant_id}</value>
				</item>
				<item>
					<name>terminal_id</name>
					<value>{$terminal_id}</value>
				</item>
				<item>
					<name>point_code</name>
					<value>100001104110</value>
				</item>
				<item>
					<name>centre_id</name>
					<value>{$this->centre_id}</value>
				</item>
			</details>
			<paymentOriginator>{$this->originator}</paymentOriginator>
		</ebppif1:Payment>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new Create($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.create'));
    }

    /**
     * @param string $payment_id
     *
     * @return Confirm
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function confirm(string $payment_id): Confirm
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
                xmlns:ebppif1=\"urn:PaymentServer\">
                <SOAP-ENV:Body>
                    <ebppif1:Payment>
                        <paymentID>{$payment_id}</paymentID>
                        <confirmed>true</confirmed>
                        <finished>true</finished>
                        <paymentOriginator>{$this->originator}</paymentOriginator>
                    </ebppif1:Payment>
                </SOAP-ENV:Body>
                </SOAP-ENV:Envelope>";

        return new Confirm($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.confirm'));
    }

    /**
     * @param string $session_id
     * @param string $payment_id
     *
     * @return Cancel
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function cancel(string $session_id, string $payment_id): Cancel
    {
        $xml = "<soapenv:Envelope
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:urn=\"urn:PaymentServer\">
	<soapenv:Header/>
	<soapenv:Body>
		<urn:CancelRequest>
			<sessionID>{$session_id}</sessionID>
			<paymentID>{$payment_id}</paymentID>
			<paymentOriginator>{$this->originator}</paymentOriginator>
		</urn:CancelRequest>
	</soapenv:Body>
</soapenv:Envelope>";

        return new Cancel($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.cancel'));
    }

    /**
     * @param string $payment_id
     * @param string $merchant_id
     * @param string $terminal_id
     *
     * @return PaymentReturn
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function return(string $payment_id, string $merchant_id, string $terminal_id)
    {
        $session_id = $this->getSessionID();

        $xml = "<soapenv:Envelope
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:urn=\"urn:PaymentServer\">
	<soapenv:Header/>
	<soapenv:Body>
		<urn:ReturnPayment>
			<sessionID>{$session_id}</sessionID>
			<paymentID>{$payment_id}</paymentID>
			<item>
				<name>merchant_id</name>
				<value>{$merchant_id}</value>
			</item>
			<item>
				<name>terminal_id</name>
				<value>{$terminal_id}</value>
			</item>
			<item>
				<name>centre_id</name>
				<value>{$this->centre_id}</value>
			</item>
			<paymentOriginator>{$this->originator}</paymentOriginator>
		</urn:ReturnPayment>
	</soapenv:Body>
</soapenv:Envelope>";

        return new PaymentReturn($this->sendXmlRequest('11210', $xml, $session_id, 'payment.return'));
    }

    /**
     * @param string $holder_id
     * @param string $bank_id
     *
     * @return bool
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     * @throws AccessGatewayException
     */
    public function SMSInformingIsEnabled(string $holder_id, string $bank_id): bool
    {
        return false;
    }

    /**
     * @param P2PCreateDTO $p2p
     *
     * @return P2PCreate
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function p2pCreate(P2PCreateDTO $p2p): P2PCreate
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <SOAP-ENV:Envelope
            xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
            xmlns:ebppif1=\"urn:PaymentServer\">
            <SOAP-ENV:Body>
                <ebppif1:Request SOAP-ENV:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
                    <language>en</language>
                    <switchingID>
                        <value>{$p2p->getSwitchId()}</value>
                    </switchingID>
                    <autoSwitch>1</autoSwitch>
                    <details>
                        <item>
                            <name>pan</name>
                            <value>{$p2p->pan}</value>
                        </item>
                        <item>
                            <name>expiry</name>
                            <value>{$p2p->expiry}</value>
                        </item>
                        <item>
                            <name>pan2</name>
                            <value>{$p2p->pan2}</value>
                        </item>
                        <item>
                            <name>amount</name>
                            <value>{$p2p->amount}</value>
                        </item>
                        <item>
                            <name>ccy_code</name>
                            <value>860</value>
                        </item>
                        <item>
                            <name>merchant_id</name>
                            <value>{$p2p->merchant_id}</value>
                        </item>
                        <item>
                            <name>terminal_id</name>
                            <value>{$p2p->terminal_id}</value>
                        </item>
                    </details>
                    <paymentOriginator>{$this->originator}</paymentOriginator>
                </ebppif1:Request>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>";

        return new P2pCreate($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.p2pCreate'));
    }

    /**
     * @param string $payment_id
     *
     * @return Confirm
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function p2pConfirm(string $payment_id): Confirm
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<SOAP-ENV:Envelope
    xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
    xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
    xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
    xmlns:ebppif1=\"urn:PaymentServer\">
    <SOAP-ENV:Body>
        <ebppif1:Payment>
            <paymentID>{$payment_id}</paymentID>
            <confirmed>true</confirmed>
            <finished>true</finished>
            <paymentOriginator>{$this->originator}</paymentOriginator>
        </ebppif1:Payment>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new Confirm($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.p2pConfirm'));
    }

    /**
     * @param string $pan
     * @param string $expiry
     * @param int $amount
     * @param string $merchant_id
     * @param string $terminal_id
     *
     * @return Create
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     * @throws ExceededAmountException
     */
    public function credit(string $pan, string $expiry, int $amount, string $merchant_id, string $terminal_id, OwnerPassportDTO $ownerPassportDTO = null): Create
    {
        $session_id = $this->getSessionID();

        $ownerData = '';
        if ($amount > $this->max_amount_without_passport) {
            if (!empty($ownerPassportDTO)) {
                foreach ($ownerPassportDTO->toArray() as $key => $value) {
                    $ownerData .= "<item>
              <name>{$key}</name>
              <value>{$value}</value>
            </item>";
                }
            } else {
                throw new ExceededAmountException();
            }
        }

        $xml = "<SOAP-ENV:Envelope
  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
  xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
  xmlns:ebppif1=\"urn:PaymentServer\">
  <SOAP-ENV:Body>
    <ebppif1:Payment>
      <billerRef>SOAP_TOCARD_2</billerRef>
      <payinstrRef>SOAP_TOCARD_2</payinstrRef>
      <sessionID>{$session_id}</sessionID>
      <details>
        <item>
          <name>pan2</name>
          <value>{$pan}</value>
        </item>
        <item>
          <name>expiry</name>
          <value>{$expiry}</value>
        </item>
        <item>
          <name>ccy_code</name>
          <value>860</value>
        </item>
        <item>
          <name>amount</name>
          <value>{$amount}</value>
        </item>
        <item>
          <name>merchant_id</name>
          <value>{$merchant_id}</value>
        </item>
        <item>
          <name>terminal_id</name>
          <value>{$terminal_id}</value>
        </item>
        <item>
          <name>point_code</name>
          <value>100010104110</value>
        </item>
        <item>
          <name>centre_id</name>
          <value>{$this->centre_id}</value>
        </item>
        {$ownerData}
      </details>
      <paymentOriginator>{$this->originator}</paymentOriginator>
    </ebppif1:Payment>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new Create($this->sendXmlRequest('11210', $xml, $session_id, 'payment.credit'));
    }

    /**
     * @param string $pan
     * @param string $expiry
     * @param int $amount
     * @param string $merchant_id
     * @param string $terminal_id
     *
     * @return Create
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function credit_old(string $pan, string $expiry, int $amount, string $merchant_id, string $terminal_id): Create
    {
        $session_id = $this->getSessionID();

        $xml = "<SOAP-ENV:Envelope
  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
  xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
  xmlns:ebppif1=\"urn:PaymentServer\">
  <SOAP-ENV:Body>
    <ebppif1:Payment>
      <billerRef>SOAP_TOCARD</billerRef>
      <payinstrRef>SOAP_TOCARD</payinstrRef>
      <sessionID>{$session_id}</sessionID>
      <details>
        <item>
          <name>pan2</name>
          <value>{$pan}</value>
        </item>
        <item>
          <name>expiry</name>
          <value>{$expiry}</value>
        </item>
        <item>
          <name>ccy_code</name>
          <value>860</value>
        </item>
        <item>
          <name>amount</name>
          <value>{$amount}</value>
        </item>
        <item>
          <name>merchant_id</name>
          <value>{$merchant_id}</value>
        </item>
        <item>
          <name>terminal_id</name>
          <value>{$terminal_id}</value>
        </item>
        <item>
          <name>point_code</name>
          <value>100010104110</value>
        </item>
        <item>
          <name>centre_id</name>
          <value>{$this->centre_id}</value>
        </item>
      </details>
      <paymentOriginator>{$this->originator}</paymentOriginator>
    </ebppif1:Payment>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new Create($this->sendXmlRequest('11210', $xml, $session_id, 'payment.credit'));
    }

    /**
     * @param string $terminal_id
     *
     * @return RecoCreate
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function recoCreate(string $terminal_id): RecoCreate
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<SOAP-ENV:Envelope
  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
  xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
  xmlns:ebppif1=\"urn:PaymentServer\">
  <SOAP-ENV:Body>
    <ebppif1:Payment>
      <billerRef>SOAP_RECO</billerRef>
      <payinstrRef>SOAP_RECO</payinstrRef>
      <details>
        <item>
          <name>terminal_id</name>
          <value>{$terminal_id}</value>
        </item>
      </details>
      <paymentOriginator>{$this->originator}</paymentOriginator>
    </ebppif1:Payment>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new RecoCreate($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'reco.create'));
    }

    /**
     * @param int $payment_id
     *
     * @return RecoConfirm
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function recoConfirm(int $payment_id): RecoConfirm
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<SOAP-ENV:Envelope
  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"
  xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\"
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
  xmlns:ebppif1=\"urn:PaymentServer\">
  <SOAP-ENV:Body>
    <ebppif1:Payment>
      <paymentID>{$payment_id}</paymentID>
      <confirmed>true</confirmed>
      <finished>true</finished>
      <paymentOriginator>{$this->originator}</paymentOriginator>
    </ebppif1:Payment>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>";

        return new RecoConfirm($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'reco.confirm'));
    }

    /**
     * @param string $payment_id
     *
     * @return BaseResponse
     * @throws AccessGatewayException
     * @throws ClientException
     * @throws ConnectionException
     * @throws Exception
     * @throws TimeoutException
     */
    public function status(string $payment_id): BaseResponse
    {
        $xml = "<soapenv:Envelope
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:urn=\"urn:PaymentServer\">
    <soapenv:Header/>
    <soapenv:Body>
        <urn:GetPayment>
            <paymentID>{$payment_id}</paymentID>
            <paymentOriginator>{$this->originator}</paymentOriginator>
        </urn:GetPayment>
    </soapenv:Body>
</soapenv:Envelope>";

        return new BaseResponse($this->sendXmlRequest('11210', $xml, $this->getSessionID(), 'payment.status'));
    }
}
