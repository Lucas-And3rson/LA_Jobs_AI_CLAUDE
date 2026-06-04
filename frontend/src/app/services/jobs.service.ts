import { Injectable, inject } from '@angular/core';

import { HttpClient } from '@angular/common/http';

import { Observable } from 'rxjs';

import { Job } from '../models/job.model';

@Injectable({
    providedIn: 'root'
})
export class JobsService {

    private http = inject(HttpClient);

    private api =
        'http://127.0.0.1:8000/api/tracked-jobs';

    getJobs(): Observable<Job[]> {
        return this.http.get<Job[]>(this.api);
    }

    /**
     * Solicita a geração de um currículo ATS para uma vaga específica
     */
    generateATS(jobId: number): Observable<any> {
        return this.http.post(`http://127.0.0.1:8000/api/jobs/${jobId}/generate-resume`, {});
    }
}