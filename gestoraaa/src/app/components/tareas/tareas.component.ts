import { Component, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ApiService } from '../../services/api.service';
import { Tarea } from '../../models/tarea.model';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-tareas',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './tareas.component.html',
  styleUrls: ['./tareas.component.css']
})
export class TareasComponent implements OnInit, OnDestroy {
  todasLasTareas: Tarea[] = [];
  isLoading = true;
  error: string | null = null;
  private subscription: Subscription = new Subscription();

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.cargarTareas();
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

  cargarTareas(): void {
    this.isLoading = true;
    this.error = null;
    
    this.subscription = this.apiService.obtenerTareas().subscribe({
      next: (respuesta: any) => {
        try {
          // Handle both response formats: direct array or {data: array}
          const tareas = Array.isArray(respuesta) ? respuesta : (respuesta?.data || []);
          
          if (!Array.isArray(tareas)) {
            throw new Error('Formato de respuesta inesperado');
          }

          console.log('Tareas recibidas:', tareas);
          this.todasLasTareas = [...tareas]; // Crear una nueva referencia del array
          console.log('Total de tareas cargadas:', this.todasLasTareas.length);
        } catch (err) {
          console.error('Error procesando la respuesta:', err);
          this.error = 'Error al procesar las tareas';
        } finally {
          this.isLoading = false;
        }
      },
      error: (error) => {
        console.error('Error al cargar tareas:', error);
        this.error = 'No se pudieron cargar las tareas. Intente nuevamente.';
        this.isLoading = false;
      }
    });
  }

  // Mejora el rendimiento del *ngFor
  trackByTarea(index: number, tarea: Tarea): number {
    return tarea.id_tarea || index;
  }
  
  onDrop(event: CdkDragDrop<Tarea[]>) {
    if (event.previousContainer === event.container) {
      // Mover dentro de la misma lista
      moveItemInArray(
        event.container.data,
        event.previousIndex,
        event.currentIndex
      );
    } else {
      // Mover entre listas
      transferArrayItem(
        event.previousContainer.data,
        event.container.data,
        event.previousIndex,
        event.currentIndex
      );
      
      // Actualizar el estado de la tarea según la lista de destino
      const tarea = event.container.data[event.currentIndex];
      const nuevoEstado = this.getEstadoFromContainerId(event.container.id);
      
      if (tarea && nuevoEstado) {
        tarea.estado = nuevoEstado;
        this.actualizarEstadoTarea(tarea);
      }
    }
  }
  
  private getEstadoFromContainerId(containerId: string): string | null {
    if (containerId.includes('pendientes')) return 'pendiente';
    if (containerId.includes('progreso')) return 'en_progreso';
    if (containerId.includes('terminadas')) return 'terminada';
    return null;
  }
  
  private actualizarEstadoTarea(tarea: Tarea): void {
    if (!tarea.id_tarea) return;
    
    this.apiService.actualizarEstadoTarea(tarea.id_tarea, tarea.estado || 'pendiente')
      .subscribe({
        next: () => {
          console.log('Estado de tarea actualizado');
        },
        error: (error) => {
          console.error('Error al actualizar el estado de la tarea:', error);
          // Aquí podrías revertir el cambio o mostrar un mensaje de error
        }
      });
  }
}
