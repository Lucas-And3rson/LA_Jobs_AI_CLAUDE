import { Component } from '@angular/core';

import { JobsComponent } from './pages/jobs/jobs.component';

@Component({
    selector: 'app-root',
    standalone: true,
    imports: [JobsComponent],
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {}