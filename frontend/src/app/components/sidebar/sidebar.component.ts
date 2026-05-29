import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { trigger, transition, style, animate } from '@angular/animations';

@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss'],
  animations: [
    // Animação de entrada da sidebar
    trigger('sidebarEnter', [
      transition(':enter', [
        style({ opacity: 0, transform: 'translateX(-20px)' }),
        animate('300ms ease-out', style({ opacity: 1, transform: 'translateX(0)' }))
      ]),
      transition(':leave', [
        animate('300ms ease-out', style({ opacity: 0, transform: 'translateX(-20px)' }))
      ])
    ]),
    // Animação de overlay
    trigger('overlayEnter', [
      transition(':enter', [
        style({ opacity: 0 }),
        animate('200ms ease-out', style({ opacity: 1 }))
      ]),
      transition(':leave', [
        animate('200ms ease-out', style({ opacity: 0 }))
      ])
    ])
  ]
})
export class SidebarComponent {
  @Output() filtersApplied = new EventEmitter<any>();
  @Output() sidebarClosed = new EventEmitter<void>();

  sidebarOpen = false;
  minMatchScore = 0;
  selectedSeniorities: Set<string> = new Set();
  selectedRemote: Set<boolean> = new Set();

  /**
   * Alterna a abertura/fechamento da sidebar
   */
  toggleSidebar(): void {
    this.sidebarOpen = !this.sidebarOpen;
  }

  /**
   * Fecha a sidebar
   */
  closeSidebar(): void {
    this.sidebarOpen = false;
    this.sidebarClosed.emit();
  }

  /**
   * Manipula mudança no filtro de seniority
   */
  onSeniorityChange(seniority: string): void {
    if (this.selectedSeniorities.has(seniority)) {
      this.selectedSeniorities.delete(seniority);
    } else {
      this.selectedSeniorities.add(seniority);
    }
  }

  /**
   * Manipula mudança no filtro de tipo de trabalho
   */
  onRemoteChange(remote: boolean): void {
    if (this.selectedRemote.has(remote)) {
      this.selectedRemote.delete(remote);
    } else {
      this.selectedRemote.add(remote);
    }
  }

  /**
   * Manipula mudança no score mínimo
   */
  onMatchScoreChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    this.minMatchScore = parseInt(input.value, 10);
  }

  /**
   * Aplica os filtros
   */
  applyFilters(): void {
    const filters = {
      seniorities: Array.from(this.selectedSeniorities),
      remoteTypes: Array.from(this.selectedRemote),
      minMatchScore: this.minMatchScore
    };
    this.filtersApplied.emit(filters);
    this.closeSidebar();
  }

  /**
   * Reseta todos os filtros
   */
  resetFilters(): void {
    this.selectedSeniorities.clear();
    this.selectedRemote.clear();
    this.minMatchScore = 0;
  }
}
