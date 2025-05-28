import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CdkDropList, CdkDrag, DragDropModule, CdkDragDrop, moveItemInArray, transferArrayItem } from '@angular/cdk/drag-drop';
import { ApiService } from '../../services/api.service';
import { Tarea } from '../../models/tarea.model';

@Component({
  selector: 'app-tareas',
  standalone: true,
  imports: [CommonModule, DragDropModule],
  templateUrl: './tareas.component.html',
  styleUrls: ['./tareas.component.css']
})
export class TareasComponent implements OnInit {

  tareasPendientes: Tarea[] = [];
  tareasEnProgreso: Tarea[] = [];
  tareasTerminadas: Tarea[] = [];

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.cargarTareas();
  }

  cargarTareas(): void {
    this.apiService.obtenerTareas().subscribe((respuesta: any) => {
      const tareas: Tarea[] = respuesta.data; // acceder a data del JSON
      this.tareasPendientes = tareas.filter(t => t.estado === 'pendiente');
      this.tareasEnProgreso = tareas.filter(t => t.estado === 'en_progreso');
      this.tareasTerminadas = tareas.filter(t => t.estado === 'terminada');
    });
  }

  drop(event: CdkDragDrop<Tarea[]>, nuevoEstado: 'pendiente' | 'en_progreso' | 'terminada') {
    if (event.previousContainer === event.container) {
      moveItemInArray(event.container.data, event.previousIndex, event.currentIndex);
    } else {
      const tarea = event.previousContainer.data[event.previousIndex];
      tarea.estado = nuevoEstado;

      transferArrayItem(
        event.previousContainer.data,
        event.container.data,
        event.previousIndex,
        event.currentIndex
      );

      this.apiService.actualizarEstadoTarea(tarea.id_tarea, tarea.estado).subscribe();
    }
  }
}
