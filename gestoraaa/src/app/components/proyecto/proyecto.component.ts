import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Proyecto } from '../../models/proyecto.model';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-proyecto',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './proyecto.component.html',
})
export class ProyectoComponent implements OnInit {
  proyectos: Proyecto[] = [];

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.readProyecto().subscribe((data: any[]) => {
      this.proyectos = data.map((p: any) => new Proyecto(
        p.id_proyecto,
        p.nombre_proyecto,
        p.descripcion,
        new Date(p.fecha_entrega),
        p.id_estado
      ));
    });
  }

  eliminarProyecto(id: number) {
    this.proyectos = this.proyectos.filter((p: Proyecto) => p.id_proyecto !== id);
  }

  estadoProyecto(proyecto: Proyecto): string {
    return proyecto.estaAtrasado() ? 'Atrasado' : 'A tiempo';
  }
}
