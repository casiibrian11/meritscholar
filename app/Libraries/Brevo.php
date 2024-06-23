<?php

namespace App\Libraries;

use Exception;
use GuzzleHttp\Client;
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Api\TransactionalSMSApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Model\CreateContact;
use Brevo\Client\Model\RemoveContactFromList;
use Brevo\Client\Model\SendTransacSms;

class Brevo
{
    protected $config;

    public function __construct()
    {
        $this->config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('brevo.api_key'));
    }
    
    public function emailsBatchSend($data = [])
    {
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $this->config
        );
        
        try {
            $result = $apiInstance->sendTransacEmail($data);
            return [ 'success' => json_decode($result, true) ];
        } catch (Exception $e) {
            return [ 'error' => $e->getMessage() ];
        }
    }

    /**
     * Brevo : Batch send customised transactional emails
     * JSON Payload
     * @see https://developers.brevo.com/docs/batch-send-transactional-emails#creating-the-api-call-with-html-content
     * @param array $data
     * @param array $messageVersions
     * @return array|object
     */

    public function getPayload($data = [], $messageVersions = [])
    {
        $payload = [
            'sender' => [
                'email' => $data['senderEmail'],
                'name' => $data['senderName']
            ],
            'subject' => $data['defaultSubject'] ?? $data['subject'],
            'htmlContent' => $data['defaultHtmlContent'] ?? $data['htmlContent'],
            'messageVersions' => $messageVersions
        ];

        return $payload;
    }

    /**
     * Message Versions array of object
     * @see https://developers.brevo.com/docs/batch-send-transactional-emails#creating-the-api-call-with-html-content
     * @param array $data
     * @return array|object
     */

    public function getMessageVersions($data = [])
    {
        $toArray = [ 'to' => [ [ 'email' => $data['email'], 'name' => $data['name'] ] ] ];
        
        $messageVersions = $toArray;
        if (!empty($data['htmlContent']) && !empty($data['subject'])) {
            $customContent = [
                'htmlContent' => $data['htmlContent'],
                'subject' => $data['subject']
            ];
            $messageVersions = array_merge($toArray, $customContent);
        }

        return $messageVersions;
    }

    /**
     * Execute Brevo Batch send customised transactional emails
     * @param array $data
     * @return array
     */

    public function executeEmailsBatchSend($data = [])
    {
        $messageVersions[] = $this->getMessageVersions($data);
        $data = $this->getPayload($data, $messageVersions);
        $result = $this->emailsBatchSend($data);

        return $result;
    }

    public static function getSenderData($store = '')
    {
        $data = [];
        $data['senderEmail'] = (!empty($store)) ? config('mail')['stores'][$store]['from']['address'] : config('mail')['from']['address'];
        $data['senderName'] = (!empty($store)) ? config('mail')['stores'][$store]['from']['name'] : config('mail')['from']['name'];

        return $data;
    }

    public function getEmailReportPerEmail($email, $startDate = "", $endDate = "")
    {
        $client = new Client();

        $response = $client->request('GET', 'https://api.brevo.com/v3/smtp/emails?email='.$email, [
            'headers' => [
                'accept' => 'application/json',
                'api-key' => config('brevo.api_key')
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getEmailReport($startDate = "")
    {
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $this->config
        );

        $limit = 100;
        $offset = 0;

        if (empty($startDate)) {
            $startDate = now()->startOfMonth()->format('Y-m-d'); 
        }

        $endDate = now()->format('Y-m-d');

        $result = [];
        try {
            $result = $apiInstance->getEmailEventReport($limit, $offset, $startDate, $endDate);
            return json_decode($result, true);
        } catch (Exception $e) {
            $result['error'] = 'Exception when calling TransactionalEmailsApi->getEmailEventReport: '.$e->getMessage();
            return $result;
        }
    }

    public function sendSms($recipient, $content)
    {
        $apiInstance = new TransactionalSMSApi(
            new Client(),
            $this->config
        );
        
        $sendTransacSms = new SendTransacSms();
        $sendTransacSms['sender'] = 'OSSA';
        $sendTransacSms['recipient'] = $recipient;
        $sendTransacSms['content'] = $content;

        try {
            $result = $apiInstance->sendTransacSms($sendTransacSms);
            return $result;
        } catch (Exception $e) {
            $result['error'] = 'Exception when calling TransactionalSMSApi->sendTransacSms: '. $e->getMessage();
            return $result;
        }
    }
}
