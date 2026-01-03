<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BrevoProvider implements EmailProviderInterface
{
    private Client $client;

    public function __construct(private string $apiKey, private string $fromEmail, private ?string $fromName = null, private bool $verify = true)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.brevo.com/v3/',
            'timeout' => 8.0,
            'verify' => $this->verify,
        ]);
    }

    public function send(string $toEmail, string $subject, string $textBody, ?string $htmlBody = null): void
    {
        try {
            $payload = [
                'sender' => [
                    'email' => $this->fromEmail,
                    'name' => $this->fromName ?: $this->fromEmail,
                ],
                'to' => [
                    ['email' => $toEmail],
                ],
                'subject' => $subject,
                'textContent' => $textBody,
            ];

            if ($htmlBody) {
                $payload['htmlContent'] = $htmlBody;
            }

            $response = $this->client->post('smtp/email', [
                'headers' => [
                    'api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $code = $response->getStatusCode();
            if ($code >= 300) {
                $body = (string)$response->getBody();
                error_log('Brevo send error: HTTP ' . $code . ' body=' . $body);
                throw new RuntimeException('Email could not be sent, try again');
            }
        } catch (GuzzleException $e) {
            error_log('Brevo send error: ' . $e->getMessage());
            throw new RuntimeException('Email could not be sent, try again');
        }
    }
}
