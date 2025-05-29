// src/app/services/api.service.ts
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { map } from 'rxjs/operators';
import { Proyecto } from '../models/proyecto.model';
import { Observable } from 'rxjs';
import { Equipo } from '../models/equipo.model';
import { Tarea } from '../models/tarea.model';


@Injectable({
  providedIn: 'root'
})
export class ApiService {

  PHP_API_SERVER = "http://127.0.0.1:80/gEsToRFreeeAnGuLar/gestoraaa/php";

  constructor(private httpClient: HttpClient) {}

  // --- CRUD de USUARIOS ---

  // --- CRUD de PROYECTOS ---

  obtenerProyectos(): Observable<Proyecto[]> {
    return this.httpClient.get<Proyecto[]>(`${this.PHP_API_SERVER}/teams-table-proyectos.php`);
  }

  crearProyecto(proyecto: Proyecto): Observable<any> {
    return this.httpClient.post(`${this.PHP_API_SERVER}/teams-table-insert-proyectos.php`, proyecto);
  }

  actualizarProyecto(proyecto: Proyecto): Observable<any> {
    return this.httpClient.put(`${this.PHP_API_SERVER}/teams-table-update-proyecto.php`, proyecto);
  }

  eliminarProyecto(id_proyecto: string): Observable<any> {
    return this.httpClient.request('delete', `${this.PHP_API_SERVER}/teams-table-delete-proyecto.php`, {
      body: { id_proyecto }
    });
  }

  obtenerEquipos(): Observable<Equipo[]> {
      return this.httpClient.get<Equipo[]>(`${this.PHP_API_SERVER}/teams-table.php`);
    }

  getEquipos(): Observable<Equipo[]> {
    return this.httpClient.get<Equipo[]>(`${this.PHP_API_SERVER}/equipo.php`);
  }


    agregarEquipo(equipo: Equipo): Observable<any> {
      return this.httpClient.post(`${this.PHP_API_SERVER}/teams-table-insert.php`, equipo);
    }

    eliminarEquipo(codigo: string): Observable<any> {
      return this.httpClient.delete(`${this.PHP_API_SERVER}/teams-table-delete.php?codigo_equipo=${codigo}`);
    }

    actualizarEquipo(equipo: Equipo): Observable<any> {
      return this.httpClient.post(`${this.PHP_API_SERVER}/teams-table-update.php`, equipo);
    }

 obtenerTareas(): Observable<Tarea[]> {
  return this.httpClient.get<{ status: string, data: Tarea[] }>(`${this.PHP_API_SERVER}/tareas.php`)
    .pipe(map((respuesta: { status: string, data: Tarea[] }) => respuesta.data));
}


actualizarTarea(tarea: Tarea): Observable<any> {
  const headers = new HttpHeaders({ 'Content-Type': 'application/json' });

  return this.httpClient.post(
    `${this.PHP_API_SERVER}/modificar_tarea.php`,
    JSON.stringify(tarea),
    { headers }
  );
}

eliminarTarea(id_tarea: number): Observable<any> {
  return this.httpClient.delete(`${this.PHP_API_SERVER}/eliminart.php?id_tarea=${id_tarea}`);
}
}
