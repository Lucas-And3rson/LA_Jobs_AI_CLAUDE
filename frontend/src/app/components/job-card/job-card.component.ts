import { Component, Input, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { trigger, transition, style, animate as ngAnimate, stagger, query } from '@angular/animations';
import { animate } from 'motion';

import { Job } from '../../models/job.model';
import { JobsService } from '../../services/jobs.service';

@Component({
  selector: 'app-job-card',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './job-card.component.html',
  styleUrls: ['./job-card.component.scss'],
  animations: [
    // Animação de entrada do card com fade-in e scale
    trigger('cardHover', [
      transition(':enter', [
        style({ opacity: 0, transform: 'scale(0.95)' }),
        ngAnimate('300ms ease-out', style({ opacity: 1, transform: 'scale(1)' }))
      ])
    ]),

    // Animação escalonada para as tags de tecnologia
    trigger('tagStagger', [
      transition('* => *', [
        query(':enter', [
          style({ opacity: 0, transform: 'translateY(10px)' }),
          stagger('50ms', [
            ngAnimate('300ms ease-out', style({ opacity: 1, transform: 'translateY(0)' }))
          ])
        ], { optional: true })
      ])
    ]),

    // Animação da barra de progresso
    trigger('progressBar', [
      transition(':enter', [
        style({ width: '0%' }),
        ngAnimate('800ms cubic-bezier(0.34, 1.56, 0.64, 1)', style({ width: '*' }))
      ])
    ]),

    // Animação de entrada do modal
    trigger('modalEnter', [
      transition(':enter', [
        style({ opacity: 0 }),
        query('.relative', [
          style({ opacity: 0, transform: 'scale(0.9) translateY(20px)' }),
          ngAnimate('300ms cubic-bezier(0.34, 1.56, 0.64, 1)', style({ opacity: 1, transform: 'scale(1) translateY(0)' }))
        ]),
        ngAnimate('200ms ease-out', style({ opacity: 1 }))
      ]),
      transition(':leave', [
        query('.relative', [
          ngAnimate('200ms ease-in', style({ opacity: 0, transform: 'scale(0.9) translateY(20px)' }))
        ]),
        ngAnimate('200ms ease-in', style({ opacity: 0 }))
      ])
    ])
  ]
})
export class JobCardComponent implements OnInit {
  private jobsService = inject(JobsService);
  
  @Input() job!: Job;

  isExpanded = false;
  isGeneratingATS = false;

  ngOnInit(): void {
    // Motion One será aplicado após a renderização do componente
    this.setupMotionAnimations();
  }

  /**
   * Alterna o estado de expansão do card (modal)
   */
  toggleExpand(event: Event): void {
    event.stopPropagation();
    this.isExpanded = !this.isExpanded;
    
    // Previne scroll do body quando o modal está aberto
    if (this.isExpanded) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'auto';
    }
  }

  /**
   * Aciona a geração do currículo ATS
   */
  generateATS(event: Event): void {
    event.stopPropagation();
    if (this.isGeneratingATS) return;

    this.isGeneratingATS = true;
    
    this.jobsService.generateATS(this.job.id).subscribe({
      next: (response) => {
        console.log('ATS Gerado com sucesso:', response);
        this.isGeneratingATS = false;
        // Aqui você poderia adicionar um toast de sucesso ou abrir o arquivo gerado
        alert('Currículo ATS gerado com sucesso! Verifique a pasta de downloads do sistema.');
      },
      error: (err) => {
        console.error('Erro ao gerar ATS:', err);
        this.isGeneratingATS = false;
        alert('Ocorreu um erro ao gerar o currículo ATS. Tente novamente.');
      }
    });
  }

  /**
   * Configura animações Motion One para hover states avançados
   */
  private setupMotionAnimations(): void {
    // Aguarda a renderização do DOM
    setTimeout(() => {
      const cardElement = document.querySelector(`app-job-card[data-job-id="${this.job.id}"]`)?.querySelector('.glass-md');
      
      if (cardElement) {
        // Animação de hover com Motion One
        cardElement.addEventListener('mouseenter', () => {
          animate(cardElement, {
            boxShadow: [
              '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
              '0 25px 50px -12px rgba(0, 0, 0, 0.25)'
            ]
          }, { duration: 0.3 });
        });

        cardElement.addEventListener('mouseleave', () => {
          animate(cardElement, {
            boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)'
          }, { duration: 0.3 });
        });
      }
    }, 0);
  }

  /**
   * Abre a vaga em uma nova aba
   */
  openJob(): void {
    if (this.job.url) {
      window.open(this.job.url, '_blank', 'noopener,noreferrer');
    }
  }
}
