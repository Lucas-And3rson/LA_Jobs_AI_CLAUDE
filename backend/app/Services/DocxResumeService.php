<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class DocxResumeService
{
    public function generate(array $resume): string
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $section->addText(
            $resume['name'] ?? '',
            [
                'bold' => true,
                'size' => 22
            ],
            [
                'alignment' => 'center'
            ]
        );

        $section->addText(
            $resume['title'] ?? '',
            [
                'size' => 12
            ],
            [
                'alignment' => 'center'
            ]
        );

        $section->addTextBreak();

        $section->addText(
            'RESUMO PROFISSIONAL',
            [
                'bold' => true,
                'size' => 14
            ]
        );

        $section->addText(
            $resume['summary'] ?? ''
        );

        $section->addTextBreak();

        $section->addText(
            'COMPETÊNCIAS',
            [
                'bold' => true,
                'size' => 14
            ]
        );

        foreach ($resume['skills'] ?? [] as $skill) {

           $section->addListItem(
                $skill,
                0
            );
        }

        $section->addTextBreak();

        $section->addText(
            'EXPERIÊNCIA PROFISSIONAL',
            [
                'bold' => true,
                'size' => 14
            ]
        );

        foreach ($resume['experience'] ?? [] as $exp) {

            $section->addText(
                $exp['title'] ?? '',
                [
                    'bold' => true
                ]
            );

            $section->addText(
                $exp['description'] ?? ''
            );

            $section->addTextBreak();
        }

        $section->addText(
            'FORMAÇÃO',
            [
                'bold' => true,
                'size' => 14
            ]
        );


        foreach ($resume['education'] ?? [] as $edu) {

           $section->addText(
                $edu['course'] ?? '',
                [
                    'bold' => true
                ]
            );

            $section->addText(
                $edu['institution'] ?? ''
            );
        }

        $section->addTextBreak();

        $section->addText(
            'IDIOMAS',
            [
                'bold' => true,
                'size' => 14
            ]
        );

        foreach ($resume['languages'] ?? [] as $language) {

            $section->addListItem(
                $language,
                0
            );
        }

        $section->addTextBreak();

        $section->addText(
            'COMPATIBILIDADE ATS',
            [
                'bold' => true,
                'size' => 14
            ]
        );

        $section->addText(
            ($resume['ats_score'] ?? 0) . '%'
        );

        $directory = storage_path('app/resumes');

        if (!file_exists($directory)) {

            mkdir(
                $directory,
                0777,
                true
            );
        }

        $fileName =
            preg_replace(
                '/[^A-Za-z0-9_-]/',
                '_',
                ($resume['name'] ?? 'resume')
            )
            . '_CV_' .
            time()
            . '.docx';

        $filename =
            $directory .
            DIRECTORY_SEPARATOR .
            $fileName;

        if (!file_exists(storage_path('app/resumes'))) {

            mkdir(
                storage_path('app/resumes'),
                0777,
                true
            );
        }

        $writer =
            IOFactory::createWriter(
                $phpWord,
                'Word2007'
            );

        $writer->save($filename);

        return $filename;
    }
}