import { Component, OnInit, inject } from '@angular/core';

import { CommonModule } from '@angular/common';

import { JobsService } from '../../services/jobs.service';

import { Job } from '../../models/job.model';

import { JobCardComponent } from '../../components/job-card/job-card.component';

@Component({
    selector: 'app-jobs',
    standalone: true,
    imports: [
        CommonModule,
        JobCardComponent
    ],
    templateUrl: './jobs.component.html',
    styleUrls: ['./jobs.component.scss']
})
export class JobsComponent implements OnInit {

    private jobsService = inject(JobsService);

    jobs: Job[] = [];

    ngOnInit(): void {

        this.loadJobs();
    }

    loadJobs(): void {

        this.jobsService
            .getJobs()
            .subscribe((data: Job[]) => {

                this.jobs = data;
            });
    }
}