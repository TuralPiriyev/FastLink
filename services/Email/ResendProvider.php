<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ResendProvider implements EmailProviderInterface
{
    private Client $client;

    public function __construct(private string $apiKey, private string $fromEmail, private ?string $fromName = null, private bool $verify = true)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.resend.com/',
            'timeout' => 8.0,
            'verify' => $this->verify,
        ]);
    }

    public function send(string $toEmail, string $subject, string $textBody, ?string $htmlBody = null): void
    {
        try {
            $payload = [
                'from' => $this->fromName ? sprintf('%s <%s>', $this->fromName, $this->fromEmail) : $this->fromEmail,
                'to' => [$toEmail],
                'subject' => $subject,
                'text' => $textBody,
            ];

            if ($htmlBody) {
                $payload['html'] = $htmlBody;
            }

            $response = $this->client->post('emails', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $code = $response->getStatusCode();
            if ($code >= 300) {
                $body = (string)$response->getBody();
                error_log('Resend send error: HTTP ' . $code . ' body=' . $body);
                throw new RuntimeException('Email could not be sent, try again');
            }
        } catch (GuzzleException $e) {
            error_log('Resend send error: ' . $e->getMessage());
            throw new RuntimeException('Email could not be sent, try again');
        }
    }
}
