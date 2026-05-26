export interface Job {

    id: number;

    title: string;

    company: string;

    description: string;

    url: string;

    seniority: string;

    stack: string[];

    keywords: string[];

    english_required: boolean;

    remote: boolean;

    match_score: number;

    ai_summary: string;

    ai_processed: boolean;

    created_at: string;
}