// src/app/components/equipos/equipos.component.ts
import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms'; // ✅ Necesario para ngModel
import { EquipoService } from '../../services/equipo.service';
import { Equipo } from '../../models/equipo.model';

@Component({
  selector: 'app-equipos',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './equipos.component.html',
  styleUrls: ['./equipos.component.css']
})
export class EquiposComponent {
  equipos: Equipo[] = [];
  nuevoEquipo: Equipo = new Equipo('', '', '', '');

  mensaje: string = '';
  tipoMensaje: string = ''; // ejemplo: 'exito' o 'error'

  equipoEditado: Equipo | null = null;

  constructor(private equipoService: EquipoService) {}

  ngOnInit() {
    this.obtenerEquipos();
  }

  obtenerEquipos() {
    this.equipoService.getEquipos().subscribe((data: Equipo[]) => {
      this.equipos = data;
    });
  }

  agregarEquipo() {
    this.equipoService.agregarEquipo(this.nuevoEquipo).subscribe(() => {
      this.obtenerEquipos();
      this.nuevoEquipo = new Equipo('', '', '', '');
      this.mensaje = 'Equipo agregado correctamente';
      this.tipoMensaje = 'exito';
    }, error => {
      this.mensaje = 'Error al agregar el equipo';
      this.tipoMensaje = 'error';
    });
  }

  editarEquipo(equipo: Equipo) {
    this.equipoEditado = new Equipo(
      equipo.codigo_equipo,
      equipo.nombre_equipo,
      equipo.descripcion,
      equipo.numero_control
    );
  }

  guardarEdicion() {
    if (this.equipoEditado) {
      this.equipoService.actualizarEquipo(this.equipoEditado).subscribe(() => {
        this.obtenerEquipos();
        this.mensaje = 'Equipo actualizado correctamente';
        this.tipoMensaje = 'exito';
        this.equipoEditado = null;
      }, error => {
        this.mensaje = 'Error al actualizar el equipo';
        this.tipoMensaje = 'error';
      });
    }
  }

  cancelarEdicion() {
    this.equipoEditado = null;
  }

  eliminarEquipo(codigo_equipo: string) {
    this.equipoService.eliminarEquipo(codigo_equipo).subscribe(() => {
      this.obtenerEquipos();
      this.mensaje = 'Equipo eliminado correctamente';
      this.tipoMensaje = 'exito';
    }, error => {
      this.mensaje = 'Error al eliminar el equipo';
      this.tipoMensaje = 'error';
    });
  }
}
