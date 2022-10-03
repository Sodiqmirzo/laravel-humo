<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:32 PM
 */

namespace Uzbek\Humo\Models;

use Uzbek\Humo\Dtos\Card\CardDto;
use Uzbek\Humo\Dtos\Card\ChargeDto;
use Uzbek\Humo\Dtos\Card\EmailDto;
use Uzbek\Humo\Dtos\Card\PhoneDto;
use Uzbek\Humo\Dtos\Card\RateDto;
use Uzbek\Humo\Exceptions\Exception;
use Uzbek\Humo\Response\Card\AccountBalance;
use Uzbek\Humo\Response\Card\Customer;
use Uzbek\Humo\Response\Card\CustomerActivate;
use Uzbek\Humo\Response\Card\CustomerCardByPassport;
use Uzbek\Humo\Response\Card\CustomerChangeCardholdersMessageLang;
use Uzbek\Humo\Response\Card\CustomerChangePhoneNumber;
use Uzbek\Humo\Response\Card\CustomerDeactivate;
use Uzbek\Humo\Response\Card\CustomerEditCard;
use Uzbek\Humo\Response\Card\CustomerList;
use Uzbek\Humo\Response\Card\CustomerRemoveCard;
use Uzbek\Humo\Response\Card\ExchangeRate;
use Uzbek\Humo\Response\Card\Info;
use Uzbek\Humo\Response\Card\TransactionScoring;

class Card extends BaseModel
{
    public const STATUS_APPROVED = 000;

    public const STATUS_DECLINE_RESTRICTED_CARD = 104;

    public const STATUS_CARD_NOT_EFFECTIVE = 125;

    public const STATUS_PICK_UP_RESTRICTED_CARD = 204;

    public const STATUS_PICK_UP_SPECIAL_CONDITIONS = 207;

    public const STATUS_PICK_UP_LOST_CARD = 208;

    public const STATUS_PICK_UP_STOLEN_CARD = 209;

    public const STATUS_DECLINE_CARD_IS_NOT_ACTIVE_AT_BANK_WILL = 280;

    public const STATUS_DECLINE_CARD_IS_NOT_ACTIVE_AT_CARDHOLDER_WILL = 281;

    public function accountBalance(string $card_number): AccountBalance
    {
        $xml = "<soapenv:Envelope
	xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
	xmlns:urn=\"urn:IIACardServices\">
	<soapenv:Header/>
	<soapenv:Body>
		<urn:getCardAccountsBalance>
			<primaryAccountNumber>{$card_number}</primaryAccountNumber>
		</urn:getCardAccountsBalance>
	</soapenv:Body>
</soapenv:Envelope>";

        return new AccountBalance($this->sendXmlRequest('6677', $xml, $this->getNewSessionID(), 'getCardAccountsBalance'));
    }

    public function customerActivate(string $bankId, string $language = null, ChargeDto $chargeDto, CardDto $cardDto, PhoneDto $phoneDto, EmailDto $emailDto): CustomerActivate
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/activate', [
            'bankId' => $bankId,
            'language' => $language,
            'Charge' => $chargeDto,
            'Card' => $cardDto,
            'Phone' => $phoneDto,
            'Email' => $emailDto,
        ]);

        return new CustomerActivate($response);
    }

    public function getCustomer(string $customerId, string $bankId): Customer
    {
        $response = $this->sendRequest('post', '/v2/mb/customer', [
            'customerId' => $customerId,
            'bankId' => $bankId,
        ]);

        return new Customer($response);
    }

    public function getCustomerList(string $phone, string $bankId): CustomerList
    {
        $response = $this->sendRequest('post', '/v2/mb/customer-list', [
            'phone' => $phone,
            'bankId' => $bankId,
        ]);

        return new CustomerList($response);
    }

    public function customerDeactivate(string $customerId): CustomerDeactivate
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/deactivate', [
            'customerId' => $customerId,
        ]);

        return new CustomerDeactivate($response);
    }

    public function customerChangePhoneNumber(string $customerId, PhoneDto $phoneDto): CustomerChangePhoneNumber
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/change-phone-number', [
            'customerId' => $customerId,
            'Phone' => $phoneDto,
        ]);

        return new CustomerChangePhoneNumber($response);
    }

    public function customerChangeCardholdersMessageLang(string $customerId, string $language): CustomerChangeCardholdersMessageLang
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/change-cardholders-message-lang', [
            'customerId' => $customerId,
            'language' => $language,
        ]);

        return new CustomerChangeCardholdersMessageLang($response);
    }

    public function customerRemoveCard(string $pan): CustomerRemoveCard
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/remove-card', [
            'Card' => [
                'pan' => $pan,
            ],
        ]);

        return new CustomerRemoveCard($response);
    }

    public function customerEditCard(CardDto $cardDto): CustomerEditCard
    {
        $response = $this->sendRequest('post', '/v2/mb/customer/edit-card', [
            'Card' => $cardDto,
        ]);

        return new CustomerEditCard($response);
    }

    public function info(string $primaryAccountNumber, int $mbFlag)
    {
        $response = $this->sendRequest('post', '/v2/iiacs/card', [
            'primaryAccountNumber' => $primaryAccountNumber,
            'mb_flag' => $mbFlag,
        ]);

        if (isset($data['error'])) {
            $message = $data['error']['message'] ?? 'Unknown Humo Middleware error';
            $code = $data['error']['code'] ?? 10001;

            throw new Exception($message, $code);
        }
        $card = $data['result']['card'] ?? null;
        if ($card) {
            $statuses = $card['statuses']['item'] ?? [];
            $card_status = '';
            foreach ($statuses as $status) {
                if ($status['type'] == 'card') {
                    $card_status = [
                        "actionCode" => $status['actionCode'],
                        "actionDescription" => $status['actionDescription'],
                        "effectiveDate" => $status['effectiveDate'],
                    ];

                    break;
                }
            }

            $info = new Info([
                "count" => 2,
                "success" => 2,
                "fail" => 0,
                "records" => [
                    [
                        "status" => "ok",
                        "index" => 0,
                        "data" => [
                            [
                                "card" => [
                                    "pan" => $card['primaryAccountNumber'],
                                    "expiry" => $card['expiry'],
                                    "institutionId" => $card['institutionId'],
                                    "nameOnCard" => $card['nameOnCard'],
                                    "cardholderId" => $card['cardUserId'],
                                    "statuses" => $card_status,
                                    "bank_c" => $card['bankC'],
                                    "pinTryCount" => $card['pinTryCount'],
                                ],
                            ],
                        ],
                    ],
                    [
                        "status" => "ok",
                        "index" => 1,
                        "data" => [
                            [
                                "mb_agreement" => [
                                    "description" => $card['mb']['message'] ?? '',
                                    "state" => $card['mb']['state'] ?? '',
                                    "phone" => $card['mb']['phone'] ?? '',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            return $info;
        }

        throw new Exception('MBPM Error! ' . __LINE__);
    }

    public function customerCardByPassport(string $serialNo, string $idCard): CustomerCardByPassport
    {
        $response = $this->sendRequest('post', '/cs/v1/customer/cards/by-passport', [
            'serial_no' => $serialNo,
            'id_card' => $idCard,
        ]);

        return new CustomerCardByPassport($response);
    }

    public function customerCardByPersonCode(string $personCode): CustomerCardByPassport
    {
        $response = $this->sendRequest('post', '/cs/v1/customer/cards/by-person-code', [
            'person_code' => $personCode,
        ]);

        return new CustomerCardByPassport($response);
    }

    public function transactionScoring(string $card, string $dateFrom, string $dateTo): TransactionScoring
    {
        $response = $this->sendRequest('post', '/cs/v1/transactions/scoring', [
            'card' => $card,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        //TODO asking response
        return new TransactionScoring($response);
    }

    public function exchangeRate(RateDto $rate): ExchangeRate
    {
        $response = $this->sendRequest('post', '/v2/ccr2/exchange-rates', [
            'rate' => $rate,
        ]);

        return new ExchangeRate($response);
    }
}
