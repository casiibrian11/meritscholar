<?php

namespace App\Libraries;

use Exception;
use GuzzleHttp\Client;
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Model\CreateContact;
use Brevo\Client\Model\RemoveContactFromList;

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
}
