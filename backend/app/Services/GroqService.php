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
            \"strengths\": [],
            \"weaknesses\": [],
            \"match_reasons\": [],
            \"recommendation\": \"\",
            \"ai_summary\": \"\"
        }

        VAGA:

        Título: {$jobData['title']}

        Empresa: {$jobData['company']}

        Descrição:
        {$jobData['description']}

        CANDIDATO:

        " . json_encode(
            $jobData['candidate'],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ) . "

        Compare a vaga com o candidato.

        Calcule um MATCH_SCORE de 0 a 100.

        Critérios:

        40% = Compatibilidade da stack
        20% = Senioridade
        15% = Experiência
        10% = Inglês
        10% = Modelo remoto/presencial
        5% = Palavras-chave

        Regras:

        - match_score deve ser um inteiro entre 0 e 100.
        - strengths deve ser um array.
        - weaknesses deve ser um array.
        - match_reasons deve ser um array.
        - recommendation deve possuir no máximo 2 frases.
        - seniority deve retornar apenas:
        Junior
        Pleno
        Senior

        Classificação:

        95-100 = Excelente compatibilidade
        80-94 = Muito boa compatibilidade
        60-79 = Compatibilidade média
        40-59 = Compatibilidade baixa
        0-39 = Não recomendado

        Retorne APENAS JSON válido.";

        return $this->callGroq($prompt);
    }

    public function generateATSResume(
        array $job,
        array $resume
    ): array {

        $prompt = "
        Você é um especialista em currículos ATS.

        Receberá:

        1. Uma vaga de emprego.
        2. Um currículo mestre.

        Sua missão:

        - Adaptar o currículo para a vaga.
        - Destacar experiências relevantes.
        - Reorganizar habilidades.
        - Priorizar palavras-chave ATS.
        - Melhorar o resumo profissional.
        - NÃO inventar experiências.
        - NÃO inventar formações.
        - NÃO inventar certificações.
        - Utilize somente informações existentes no currículo mestre.

        Retorne APENAS JSON válido.

        Estrutura:

        {
            \"name\": \"\",
            \"title\": \"\",
            \"summary\": \"\",
            \"skills\": [],
            \"experience\": [],
            \"education\": [],
            \"languages\": [],
            \"keywords\": [],
            \"ats_score\": 0
        }

        VAGA:

        " . json_encode(
            $job,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ) . "

        CURRÍCULO MESTRE:

        " . json_encode(
            $resume,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ) . "

        Regras:

        - ats_score deve ser inteiro de 0 a 100.
        - skills deve conter apenas habilidades relevantes para a vaga.
        - keywords deve conter palavras ATS encontradas na vaga.
        - summary deve ser otimizado para ATS.
        - experience deve manter apenas experiências existentes no currículo.
        - education deve manter apenas formações existentes.
        - languages deve manter apenas idiomas existentes no currículo mestre.
        - Nunca invente idiomas.
        - Retorne APENAS JSON válido.
        ";

        return $this->callGroq($prompt);
    }

    private function extractJson(string $content): string
    {
        $content = str_replace('```json', '', $content);
        $content = str_replace('```', '', $content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            return trim($matches[0]);
        }

        return '{}';
    }

    private function callGroq(string $prompt): array
    {
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

        $content =
            $response['choices'][0]['message']['content']
            ?? '{}';

        $content = $this->extractJson($content);

        logger('GROQ CONTENT', [
            'content' => $content
        ]);

        $json = json_decode($content, true);

        logger('JSON DECODE', [
            'json' => $json
        ]);

        if (!$json) {

            logger('JSON INVÁLIDO');

            return [];
        }

        return $json;
    }
}