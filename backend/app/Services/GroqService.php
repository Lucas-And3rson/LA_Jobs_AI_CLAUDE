<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    public function analyzeJob(array $jobData): array
    {
        $prompt = "
        Analise esta vaga de tecnologia.

        Retorne APENAS JSON válido.

        {
            \"seniority\": \"Junior|Pleno|Senior\",
            \"stack\": [],
            \"keywords\": [],
            \"english_required\": true,
            \"remote\": true,
            \"match_score\": 0,
            \"ai_summary\": \"\"
        }

        VAGA:

        Título: {$jobData['title']}

        Empresa: {$jobData['company']}

        Descrição:
        {$jobData['description']}
        ";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(
            'https://api.groq.com/openai/v1/chat/completions',
            [
                'model' => env('GROQ_MODEL'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.2
            ]
        );

        logger('GROQ STATUS', [
            'status' => $response->status()
        ]);

        logger('GROQ RAW', [
            'body' => $response->body()
        ]);

        $content =
            $response['choices'][0]['message']['content']
            ?? '{}';

        $content = trim($content);

        $content = str_replace('```json', '', $content);
        $content = str_replace('```', '', $content);

        logger('GROQ CONTENT', [
            'content' => $content
        ]);

        $json = json_decode($content, true);

        if (!$json) {

            logger('JSON INVÁLIDO');

            return [];
        }

        return $json;
    }
}