<?php

namespace App\Controllers;

// LS-MiniSocial-Core

use GuzzleHttp\Client;

class AiController extends BaseController
{
    // POST /ai/improve
    public function improve()
    {
        $json = $this->request->getJSON(true);
        $text = trim($json['text'] ?? '');

        if ($text === '') {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'No text provided.']);
        }

        $apiKey = $_ENV['OPENROUTER_API_KEY'] ?? '';

        if ($apiKey === '') {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'AI service is not configured.']);
        }

        try {
            $client = new Client();

            $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => 'http://localhost:8080',
                    'X-Title' => 'LSMiniSocial',
                ],
                'json' => [
                    'model' => 'anthropic/claude-3-haiku',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a text-processing engine, NOT a chatbot. Rewrite the user input to be clearer, more engaging, and grammatically correct. STRICT RULES: Do NOT reply to the user. Do NOT conversationalize. Maintain the exact same perspective, tone, and intent as the original text. Output ONLY the rewritten text with absolutely NO intro, explanations, or conversational filler. If the text is purely informal or a greeting, fix the grammar/formatting but keep the informal essence.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $text,
                        ],
                    ],
                    'max_tokens' => 500,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $improved = $data['choices'][0]['message']['content'] ?? null;

            if (!$improved) {
                return $this->response
                    ->setStatusCode(502)
                    ->setJSON(['error' => 'Could not get a response from the AI service.']);
            }

            return $this->response
                ->setStatusCode(200)
                ->setJSON(['improved' => trim($improved)]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(502)
                ->setJSON(['error' => 'AI service unavailable. Please try again later.']);
        }
    }
}
