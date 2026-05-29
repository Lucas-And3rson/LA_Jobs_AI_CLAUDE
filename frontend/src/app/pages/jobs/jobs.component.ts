import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { trigger, transition, style, animate, stagger, query } from '@angular/animations';

import { JobsService } from '../../services/jobs.service';
import { Job } from '../../models/job.model';
import { JobCardComponent } from '../../components/job-card/job-card.component';
import { SidebarComponent } from '../../components/sidebar/sidebar.component';

@Component({
  selector: 'app-jobs',
  standalone: true,
  imports: [
    CommonModule,
    JobCardComponent,
    SidebarComponent
  ],
  templateUrl: './jobs.component.html',
  styleUrls: ['./jobs.component.scss'],
  animations: [
    // Animação escalonada para entrada dos cards
    trigger('cardStagger', [
      transition('* => *', [
        query(':enter', [
          style({ opacity: 0, transform: 'translateY(20px)' }),
          stagger('100ms', [
            animate('500ms cubic-bezier(0.34, 1.56, 0.64, 1)', style({ opacity: 1, transform: 'translateY(0)' }))
          ])
        ], { optional: true })
      ])
    ])
  ]
})
export class JobsComponent implements OnInit {
  private jobsService = inject(JobsService);

  jobs: Job[] = [];
  filteredJobs: Job[] = [];
  isLoading = false;
  error: string | null = null;
  private allJobs: Job[] = [];

  ngOnInit(): void {
    this.loadJobs();
  }

  /**
   * Carrega as vagas do serviço
   */
  loadJobs(): void {
    this.isLoading = true;
    this.error = null;

    this.jobsService
      .getJobs()
      .subscribe({
        next: (data: Job[]) => {
          this.allJobs = data;
          this.jobs = data;
          this.filteredJobs = data;
          this.isLoading = false;
        },
        error: (err) => {
          console.error('Erro ao carregar vagas:', err);
          this.error = 'Não foi possível carregar as vagas. Tente novamente mais tarde.';
          this.isLoading = false;
        }
      });
  }

  /**
   * Manipula aplicação de filtros da sidebar
   */
  onFiltersApplied(filters: any): void {
    this.filteredJobs = this.allJobs.filter((job) => {
      // Filtro por seniority
      if (filters.seniorities.length > 0 && !filters.seniorities.includes(job.seniority)) {
        return false;
      }

      // Filtro por tipo de trabalho (remoto/presencial)
      if (filters.remoteTypes.length > 0) {
        const isRemote = filters.remoteTypes.includes(true);
        const isOnSite = filters.remoteTypes.includes(false);
        if (isRemote && isOnSite) {
          // Ambos selecionados, aceita todos
        } else if (isRemote && !job.remote) {
          return false;
        } else if (isOnSite && job.remote) {
          return false;
        }
      }

      // Filtro por match score mínimo
      if (job.match_score < filters.minMatchScore) {
        return false;
      }

      return true;
    });

    this.jobs = this.filteredJobs;
  }

  /**
   * Manipula fechamento da sidebar
   */
  onSidebarClosed(): void {
    // Pode ser usado para outras ações quando a sidebar fecha
  }
}
