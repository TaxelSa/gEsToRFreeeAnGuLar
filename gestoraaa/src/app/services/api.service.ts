// src/app/services/api.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Proyecto } from '../models/proyecto.model';
import { Observable } from 'rxjs';

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
  
}
