import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

import { TopbarComponent } from './components/topbar/topbar.component';
import { JobsComponent } from './pages/jobs/jobs.component';

@Component({
    selector: 'app-root',
    standalone: true,
    imports: [CommonModule, TopbarComponent, JobsComponent],
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {
    title = 'LA Jobs AI';
}