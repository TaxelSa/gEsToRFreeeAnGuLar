import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Proyecto } from '../../models/proyecto.model';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-proyecto-crud',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './proyecto.component.html',
  styleUrls: ['./proyecto.component.css']
})
export class ProyectoCrudComponent implements OnInit {
  proyectos: Proyecto[] = [];
  form: Proyecto = new Proyecto();
  editando: boolean = false;
  mensaje: string = '';
  mensajeTipo: string = '';
  darkMode: boolean = false;

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.obtenerProyectos();
  }

  toggleDarkMode(): void {
    this.darkMode = !this.darkMode;
  }

  obtenerProyectos(): void {
    this.apiService.obtenerProyectos().subscribe({
      next: (proyectos) => this.proyectos = proyectos,
      error: () => this.mostrarMensaje("❌ Error al obtener los proyectos.", "error")
    });
  }

  guardarProyecto(): void {
    const peticion = this.editando
      ? this.apiService.actualizarProyecto(this.form)
      : this.apiService.crearProyecto(this.form);

    peticion.subscribe({
      next: (res: any) => {
        this.mostrarMensaje(res.mensaje || (this.editando ? "✅ Proyecto actualizado." : "✅ Proyecto creado."), "success");
        this.limpiarFormulario();
        this.obtenerProyectos();
      },
      error: () => this.mostrarMensaje("❌ Error de conexión con el servidor.", "error")
    });
  }

  eliminarProyecto(id: string): void {
    if (!confirm("¿Estás seguro de eliminar este proyecto?")) return;

    this.apiService.eliminarProyecto(id).subscribe({
      next: (res: any) => {
        this.mostrarMensaje(res.mensaje || "✅ Proyecto eliminado.", "success");
        this.obtenerProyectos();
      },
      error: () => this.mostrarMensaje("❌ Error al eliminar.", "error")
    });
  }

  editarProyecto(proyecto: Proyecto): void {
    this.form = { ...proyecto };
    this.editando = true;
  }

  limpiarFormulario(): void {
    this.form = new Proyecto();
    this.editando = false;
  }

  mostrarMensaje(mensaje: string, tipo: string): void {
    this.mensaje = mensaje;
    this.mensajeTipo = tipo;
  }
}
