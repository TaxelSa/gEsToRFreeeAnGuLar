import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { CommonModule } from '@angular/common';
import {
  DragDropModule,
  CdkDragDrop,
  moveItemInArray,
  transferArrayItem,
} from '@angular/cdk/drag-drop';
import { ApiService } from '../../services/api.service';
import { Tarea } from '../../models/tarea.model';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-tareas',
  standalone: true,
 imports: [CommonModule, DragDropModule, FormsModule],  templateUrl: './tareas.component.html',
  styleUrls: ['./tareas.component.css'],
})
export class TareasComponent implements OnInit {
  tareasCol1: Tarea[] = [];
  tareasCol2: Tarea[] = [];
  tareasCol3: Tarea[] = [];

  tareaEnEdicion: Tarea | null = null; // Para manejar la tarea que se edita
  nuevaTarea: Partial<Tarea> = {
    nombre_tarea: '',
    fecha_entrega: '',
    hora_entrega: '',
    estado: 'pendiente'
  };
  isLoading = false;
  submitSuccess = false;

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.cargarTareas();
    this.resetearFormulario();
  }

  resetearFormulario() {
    this.nuevaTarea = {
      nombre_tarea: '',
      fecha_entrega: '',
      hora_entrega: '',
      estado: 'pendiente'
    };
  }

  cargarTareas() {
    this.apiService.obtenerTareas().subscribe((tareas: Tarea[]) => {
      this.tareasCol1 = [];
      this.tareasCol2 = [];
      this.tareasCol3 = [];

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
    this.tareaEnEdicion = { ...tarea }; // NUEVO: habilita la edición en línea
  }

  guardarCambios() {
    if (this.tareaEnEdicion) {
      this.apiService.actualizarTarea(this.tareaEnEdicion).subscribe(() => {
        this.tareaEnEdicion = null;
        this.cargarTareas();
      });
    }
  }

  cancelarEdicion() {
    this.tareaEnEdicion = null; // NUEVO: cancela la edición
  }

  eliminarTarea(tarea: Tarea) {
    if (confirm(`¿Seguro que quieres eliminar la tarea "${tarea.nombre_tarea}"?`)) {
      this.apiService.eliminarTarea(tarea.id_tarea).subscribe(() => {
        this.cargarTareas();
      });
    }
  }

  agregarTarea() {
    if (this.nuevaTarea.nombre_tarea && this.nuevaTarea.fecha_entrega && this.nuevaTarea.hora_entrega) {
      this.isLoading = true;
      this.submitSuccess = false;
      
      this.apiService.crearTarea(this.nuevaTarea as Tarea).subscribe({
        next: () => {
          this.cargarTareas();
          this.resetearFormulario();
          this.submitSuccess = true;
          this.isLoading = false;
          
          // Hide success message after 3 seconds
          setTimeout(() => {
            this.submitSuccess = false;
          }, 3000);
        },
        error: (error) => {
          console.error('Error al crear la tarea:', error);
          this.isLoading = false;
          // Show error message using a more user-friendly approach
          const errorMessage = error.error?.message || 'Error al crear la tarea. Por favor, intente nuevamente.';
          alert(errorMessage);
        }
      });
    }
  }
}
