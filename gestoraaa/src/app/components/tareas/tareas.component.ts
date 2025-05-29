import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import {
  DragDropModule,
  CdkDragDrop,
  moveItemInArray,
  transferArrayItem,
} from '@angular/cdk/drag-drop';
import { ApiService } from '../../services/api.service';
import { Tarea } from '../../models/tarea.model';

@Component({
  selector: 'app-tareas',
  standalone: true,
  imports: [CommonModule, DragDropModule],
  templateUrl: './tareas.component.html',
  styleUrls: ['./tareas.component.css'],
})
export class TareasComponent implements OnInit {
  tareasCol1: Tarea[] = [];
  tareasCol2: Tarea[] = [];
  tareasCol3: Tarea[] = [];

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.cargarTareas();
  }

  cargarTareas() {
    this.apiService.obtenerTareas().subscribe((tareas: Tarea[]) => {
      // Limpiar las columnas
      this.tareasCol1 = [];
      this.tareasCol2 = [];
      this.tareasCol3 = [];

      // Repartir tareas entre las 3 columnas
      tareas.forEach((tarea, index) => {
        if (index % 3 === 0) this.tareasCol1.push(tarea);
        else if (index % 3 === 1) this.tareasCol2.push(tarea);
        else this.tareasCol3.push(tarea);
      });
    });
  }

  drop(event: CdkDragDrop<Tarea[]>) {
    if (event.previousContainer === event.container) {
      moveItemInArray(event.container.data, event.previousIndex, event.currentIndex);
    } else {
      transferArrayItem(
        event.previousContainer.data,
        event.container.data,
        event.previousIndex,
        event.currentIndex
      );
    }
  }


  editarTarea(tarea: Tarea) {
    const nuevoNombre = prompt('Modificar nombre de la tarea:', tarea.nombre_tarea);
    if (nuevoNombre !== null && nuevoNombre.trim() !== '') {
      const tareaEditada = { ...tarea, nombre_tarea: nuevoNombre.trim() };
      this.apiService.actualizarTarea(tareaEditada).subscribe(() => {
        this.cargarTareas();
      });
    }
  }

  eliminarTarea(tarea: Tarea) {
    if (confirm(`¿Seguro que quieres eliminar la tarea "${tarea.nombre_tarea}"?`)) {
      this.apiService.eliminarTarea(tarea.id_tarea).subscribe(() => {
        this.cargarTareas();
      });
    }
  }
}
