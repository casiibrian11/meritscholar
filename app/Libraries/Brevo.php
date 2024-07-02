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

use Illuminate\Support\Facades\Auth;
use App\Models\EmailTemplate;
use App\Models\Application;

class Brevo
{
    protected $config;

    public static array $statuses = [
        'completed' => 'has been submitted',
        'under_review' => 'is under review',
        'denied' => 'has been denied',
        'approved' => 'has been approved',
    ];

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

    public function getEmailReportPerEmail($email)
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

    public function getEmailReport($startDate = "", $email = "")
    {
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $this->config
        );

        $limit = 1000;
        $offset = 0;

        if (empty($startDate)) {
            $startDate = now()->subDays(30)->format('Y-m-d'); 
        }

        $endDate = now()->format('Y-m-d');

        $result = [];
        try {
            if (empty($email)) {
                $result = $apiInstance->getEmailEventReport($limit, $offset, $startDate, $endDate);
            } else {
                $result = $apiInstance->getEmailEventReport($limit, $offset, $startDate, $endDate, null, $email);
            }
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

    public static function emailTemplate($data, $user, $applicationId = null)
    {
        $name = ucwords($user['first_name'].' '.$user['last_name']);
        $status = $data['template'];

        $data['status'] = self::$statuses[$status];
        $data['content'] = EmailTemplate::where('status', $data['template'])->first();

        $application = Application::with('scholarship_offers')
                                ->with('users')
                                ->with('school_years')
                                ->when(!empty($applicationId), function($query) use($applicationId){
                                    $query->where('id', $applicationId);
                                })
                                ->first();
        
        $scholarship = strtoupper($application['scholarship_offers']['scholarships']['description']);
        $sy = $application['school_years'];
        $semester = "{$sy['semester']} semester";
        $school_year = "S.Y. {$sy['start_year']} - {$sy['end_year']}";

        if (empty($data['content'])) {
            $data['greetings'] = __('Hi :name,', [ 'name' => $name ]);
            $messageContent = "";
            $messageContent .= "Your application for <b>{$scholarship}</b> for the {$semester} of the {$school_year} {$data['status']}.";

            $data['content'] = null;
            $data['default'] = $messageContent;
        } else {
            $name = ucwords($application['users']['first_name'].' '.$application['users']['last_name']);
            $data['content'] = __($data['content']['email_content'], [
                                    'applicant_name' => $name,
                                    'semester' => $semester,
                                    'school_year' => $school_year,
                                    'scholarship' => $scholarship,
                                    'status' => $status
                                ]);

            $data['application'] = $application->toArray();
        }

        return $data;
    }
}
