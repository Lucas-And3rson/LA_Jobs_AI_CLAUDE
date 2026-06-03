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
            ['bold' => true, 'size' => 18]
        );

        $section->addText(
            $resume['title'] ?? ''
        );

        $section->addTextBreak();

        $section->addText(
            'RESUMO',
            ['bold' => true]
        );

        $section->addText(
            $resume['summary'] ?? ''
        );

        $section->addTextBreak();

        $section->addText(
            'SKILLS',
            ['bold' => true]
        );

        foreach ($resume['skills'] ?? [] as $skill) {

            $section->addText(
                '• ' . $skill
            );
        }

        $section->addTextBreak();

        $section->addText(
            'EXPERIÊNCIA',
            ['bold' => true]
        );

        foreach ($resume['experience'] ?? [] as $exp) {

            $section->addText(
                $exp['title'] ?? ''
            );

            $section->addText(
                $exp['description'] ?? ''
            );

            $section->addTextBreak();
        }

        $section->addText(
            'FORMAÇÃO',
            ['bold' => true]
        );

        foreach ($resume['education'] ?? [] as $edu) {

            $section->addText(
                $edu['course'] ?? ''
            );

            $section->addText(
                $edu['institution'] ?? ''
            );
        }

        $filename =
            storage_path(
                'app/resumes/resume_' .
                time() .
                '.docx'
            );

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