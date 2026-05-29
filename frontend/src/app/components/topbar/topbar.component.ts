import { Component, OnInit, HostListener, Output, EventEmitter, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { trigger, transition, style, animate } from '@angular/animations';
import { debounceTime, Subject } from 'rxjs';

import { JobsService } from '../../services/jobs.service';

@Component({
  selector: 'app-topbar',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './topbar.component.html',
  styleUrls: ['./topbar.component.scss'],
  animations: [
    // Animação de entrada da topbar
    trigger('topbarEnter', [
      transition(':enter', [
        style({ opacity: 0, transform: 'translateY(-10px)' }),
        animate('300ms ease-out', style({ opacity: 1, transform: 'translateY(0)' }))
      ])
    ]),

    // Animação de foco do input de busca
    trigger('searchInputFocus', [
      transition('false => true', [
        animate('200ms ease-out', style({ transform: 'scale(1.02)' }))
      ]),
      transition('true => false', [
        animate('200ms ease-out', style({ transform: 'scale(1)' }))
      ])
    ]),

    // Animação de entrada dos dropdowns
    trigger('dropdownEnter', [
      transition(':enter', [
        style({ opacity: 0, transform: 'translateY(-10px)' }),
        animate('200ms ease-out', style({ opacity: 1, transform: 'translateY(0)' }))
      ]),
      transition(':leave', [
        animate('200ms ease-out', style({ opacity: 0, transform: 'translateY(-10px)' }))
      ])
    ])
  ]
})
export class TopbarComponent implements OnInit {
  private jobsService = inject(JobsService);

  @Output() searchChange = new EventEmitter<string>();
  @Output() filtersToggle = new EventEmitter<void>();

  searchQuery = '';
  searchFocused = false;
  searchSuggestions: string[] = [];
  userMenuOpen = false;
  scrollProgress = 0;

  private searchSubject = new Subject<string>();
  private allTechs: string[] = [];
  private allCompanies: string[] = [];

  ngOnInit(): void {
    // Debounce da busca para não fazer requisições a cada keystroke
    this.searchSubject
      .pipe(debounceTime(300))
      .subscribe((query) => {
        this.updateSuggestions(query);
        this.searchChange.emit(query);
      });

    // Carrega sugestões iniciais (tecnologias e empresas)
    this.loadSuggestions();
  }

  /**
   * Listener para scroll da página
   */
  @HostListener('window:scroll', ['$event'])
  onScroll(): void {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    this.scrollProgress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
  }

  /**
   * Listener para fechar menus ao clicar fora
   */
  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent): void {
    const target = event.target as HTMLElement;
    if (!target.closest('[role="menuitem"]') && !target.closest('button')) {
      this.userMenuOpen = false;
      this.searchFocused = false;
    }
  }

  /**
   * Atualiza sugestões de busca
   */
  private updateSuggestions(query: string): void {
    if (!query.trim()) {
      this.searchSuggestions = [];
      return;
    }

    const lowerQuery = query.toLowerCase();

    // Filtra tecnologias e empresas que correspondem à query
    const techMatches = this.allTechs.filter((tech) =>
      tech.toLowerCase().includes(lowerQuery)
    );

    const companyMatches = this.allCompanies.filter((company) =>
      company.toLowerCase().includes(lowerQuery)
    );

    // Combina e limita a 5 sugestões
    this.searchSuggestions = [...techMatches, ...companyMatches].slice(0, 5);
  }

  /**
   * Carrega sugestões iniciais de tecnologias e empresas
   */
  private loadSuggestions(): void {
    this.jobsService.getJobs().subscribe((jobs) => {
      const techSet = new Set<string>();
      const companySet = new Set<string>();

      jobs.forEach((job) => {
        job.stack.forEach((tech) => techSet.add(tech));
        companySet.add(job.company);
      });

      this.allTechs = Array.from(techSet).sort();
      this.allCompanies = Array.from(companySet).sort();
    });
  }

  /**
   * Manipula mudanças no input de busca
   */
  onSearchChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    this.searchQuery = input.value;
    this.searchSubject.next(this.searchQuery);
  }

  /**
   * Seleciona uma sugestão
   */
  selectSuggestion(suggestion: string): void {
    this.searchQuery = suggestion;
    this.searchSuggestions = [];
    this.searchChange.emit(suggestion);
  }

  /**
   * Toggle do menu de filtros
   */
  toggleFilters(): void {
    this.filtersToggle.emit();
  }

  /**
   * Toggle do menu de usuário
   */
  toggleUserMenu(): void {
    this.userMenuOpen = !this.userMenuOpen;
  }

  /**
   * Limpa a busca
   */
  clearSearch(): void {
    this.searchQuery = '';
    this.searchSuggestions = [];
    this.searchChange.emit('');
  }
}
