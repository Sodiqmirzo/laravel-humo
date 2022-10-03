<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 9/30/2022
 * Time: 5:32 PM
 */

namespace Uzbek\Humo\Models;

use DateTime;
use Uzbek\Humo\Exceptions\Exception;
use Uzbek\Humo\Response\Issuing\CardInfo;
use Uzbek\Humo\Response\Issuing\ExecuteTransaction;
use Uzbek\Humo\Response\Issuing\TransactionHistory;

class Issuing extends BaseModel
{
    public function queryCardInfo(string $card): CardInfo
    {
        $session_id = $this->getNewSessionID();
        $bank_c = substr($card, 4, 2);

        $xml = "<soapenv:Envelope
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:bin=\"urn:IssuingWS/binding\">
	<soapenv:Header/>
	<soapenv:Body>
		<bin:queryCardInfo soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding\">
			<ConnectionInfo xsi:type=\"urn:OperationConnectionInfo\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
				<EXTERNAL_SESSION_ID xsi:type=\"xsd:string\">{$session_id}</EXTERNAL_SESSION_ID>
			</ConnectionInfo>
			<Parameters xsi:type=\"urn:RowType_QueryCardInfo_Request\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<CARD xsi:type=\"xsd:string\">{$card}</CARD>
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
			</Parameters>
		</bin:queryCardInfo>
	</soapenv:Body>
</soapenv:Envelope>";

        return new CardInfo($this->sendXmlRequest('8443', $xml, $session_id, 'queryCardInfo'));
    }

    public function transactionHistory(string $card, DateTime $beginDate, DateTime $endDate): array
    {
        $session_id = $this->getNewSessionID();
        $bank_c = substr($card, 4, 2);

        $begin_date = $beginDate->format('Y-m-d\T') . '00:00:00';
        $end_date = $endDate->format('Y-m-d\T') . '23:59:59';

        $xml = "<soapenv:Envelope
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:bin=\"urn:IssuingWS/binding\">
	<soapenv:Header/>
	<soapenv:Body>
		<bin:queryTransactionHistory soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
			<ConnectionInfo xsi:type=\"urn:OperationConnectionInfo\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
				<EXTERNAL_SESSION_ID xsi:type=\"xsd:string\">{$session_id}</EXTERNAL_SESSION_ID>
			</ConnectionInfo>
			<Parameters xsi:type=\"urn:RowType_TransactionHist_Request\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<CARD xsi:type=\"xsd:string\">{$card}</CARD>
				<BEGIN_DATE xsi:type=\"xsd:dateTime\">{$begin_date}</BEGIN_DATE>
				<END_DATE xsi:type=\"xsd:dateTime\">{$end_date}</END_DATE>
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
				<LOCKING_FLAG xsi:type=\"xsd:string\">1</LOCKING_FLAG>
			</Parameters>
		</bin:queryTransactionHistory>
	</soapenv:Body>
</soapenv:Envelope>";

        $history = $this->sendXmlRequest('8443', $xml, $session_id, 'transactionHistory');
        $res_code = (int)($history['queryTransactionHistoryResponse']['ResponseInfo']['response_code'] ?? -1);
        if ($res_code === 0) {
            $data = $history['queryTransactionHistoryResponse']['Details']['row'] ?? [];

            if (is_array($data) && isset($data['item']) && count($data) === 1) {
                $data = [$data];
            }

            $objects = [];
            foreach ($data as $item) {
                $params = [];
                $tranhis = $item['item'] ?? [];
                foreach ($tranhis as $nv) {
                    if (isset($nv['name'])) {
                        if (is_array($nv['name']) && isset($nv['name'][0])) {
                            $key = $nv['name'][0];
                        } else {
                            $key = $nv['name'];
                        }

                        if (is_array($nv['value'])) {
                            $value = $nv['value'][0] ?? null;
                        } else {
                            $value = $nv['value'] ?? null;
                        }

                        $params[$key] = $value;
                    }
                }
                $objects[] = new TransactionHistory($params);
            }

            return $objects;
        } else {
            throw new Exception('Cannot connect to NMPC', 20002);
        }
    }

    public function execute_transaction(
        string $card,
        int    $operation_id,
        int    $amount,
        bool   $isCredit
    ): ExecuteTransaction {
        $session_id = $this->getNewSessionID();
        $bank_c = substr($card, 4, 2);
        $slip_nr = substr($operation_id, -8);
        $batch_nr = date('ymd');
        $tran_date = date('Y-m-d\TH:i:s');

        if ($isCredit) {
            $payment_mode = 2;
            $tran_type = '11V';
        } else {
            $payment_mode = 3;
            $tran_type = '12V';
        }

        $xml = "<soapenv:Envelope
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:bin=\"urn:IssuingWS/binding\">
	<soapenv:Header/>
	<soapenv:Body>
		<bin:executeTransaction soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
			<ConnectionInfo xsi:type=\"urn:OperationConnectionInfo\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
				<EXTERNAL_SESSION_ID xsi:type=\"xsd:string\">{$session_id}</EXTERNAL_SESSION_ID>
			</ConnectionInfo>
			<Parameters xsi:type=\"urn:RowType_ExecTransaction_Request\"
				xmlns:urn=\"urn:issuing_v_01_02_xsd\">
				<PAYMENT_MODE xsi:type=\"xsd:string\">{$payment_mode}</PAYMENT_MODE>
				<CARD xsi:type=\"xsd:string\">{$card}</CARD>
				<TRAN_TYPE xsi:type=\"xsd:string\">{$tran_type}</TRAN_TYPE>
				<BATCH_NR xsi:type=\"xsd:string\">{$batch_nr}</BATCH_NR>
				<SLIP_NR xsi:type=\"xsd:string\">{$slip_nr}</SLIP_NR>
				<TRAN_CCY xsi:type=\"xsd:string\">UZS</TRAN_CCY>
				<TRAN_AMNT xsi:type=\"xsd:decimal\">{$amount}</TRAN_AMNT>
				<BANK_C xsi:type=\"xsd:string\">{$bank_c}</BANK_C>
				<GROUPC xsi:type=\"xsd:string\">01</GROUPC>
				<CHECK_DUPL>1</CHECK_DUPL>
				<TRAN_DATE_TIME>{$tran_date}</TRAN_DATE_TIME>
			</Parameters>
		</bin:executeTransaction>
	</soapenv:Body>
</soapenv:Envelope>";

        return new ExecuteTransaction($this->sendXmlRequest('8443', $xml, $session_id, 'execute_transaction'));
    }
}
