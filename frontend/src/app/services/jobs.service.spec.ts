import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class JobsService {

    private api =
        'http://127.0.0.1:8000/api/tracked-jobs';

    constructor(
        private http: HttpClient
    ) {}

    getJobs(): Observable<any> {

        return this.http.get(this.api);
    }
}