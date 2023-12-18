<?php

namespace App\Lib\Connector\Google;

use Exception;
use Google\Client;
use Google\Service\Gmail;
use Google_Service_Gmail_Message;
use Throwable;

class GmailService
{
    private Client $client;
    private Gmail $gmailService;
    private const SCOPE = Gmail::MAIL_GOOGLE_COM;

    /**
     * @throws \Google\Exception
     * @throws Exception
     */
    public function __construct(readonly string $googleServiceConfigFile)
    {
        $this->client = new Client();
        $this->client->addScope(static::SCOPE);
        if ($credentials_file = file_exists($this->googleServiceConfigFile) ? $this->googleServiceConfigFile : false) {
            $this->client->setAuthConfig($credentials_file);
        } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            $this->client->useApplicationDefaultCredentials();
        } else {
            throw new Exception('Missing Service Account Details');
        }
        $this->gmailService = new Gmail($this->client);
    }

    /**
     * @throws Exception
     */
    public function send(string $recipient, Google_Service_Gmail_Message $message): void
    {
        try {
            $this->gmailService->users_messages->send('me', $message);
        } catch (Throwable $e) {
            //dump($e);
            throw new Exception('Unable to send message to user ' . $recipient);
        }
    }
}