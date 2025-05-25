// src/app/services/api.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Proyecto } from '../models/proyecto.model';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  PHP_API_SERVER = "http://127.0.0.1:80";

  constructor(private httpClient: HttpClient) {}

  // --- CRUD de USUARIOS ---

  // --- CRUD de PROYECTOS ---
  readProyecto(): Observable<Proyecto[]> {
    return this.httpClient.get<Proyecto[]>(`${this.PHP_API_SERVER}/gEsToRFreeeAnGuLar/gestoraaa/php/teams-table-proyectos.php`);
  }

  createProyecto(proyecto: Proyecto): Observable<Proyecto> {
    return this.httpClient.post<Proyecto>(`${this.PHP_API_SERVER}/gEsToRFreeeAnGuLar/gestoraaa/php/teams-table-insert-proyectos.php`, proyecto);
  }

  updateProyecto(proyecto: Proyecto): Observable<Proyecto> {
    return this.httpClient.put<Proyecto>(`${this.PHP_API_SERVER}/gEsToRFreeeAnGuLar/gestoraaa/php/teams-table-update-proyectos.php`, proyecto);
  }

  deleteProyecto(id: number): Observable<Proyecto> {
    return this.httpClient.delete<Proyecto>(`${this.PHP_API_SERVER}/gEsToRFreeeAnGuLar/gestoraaa/php/teams-table-delete-proyectos.php/?id=${id}`);
  }
}
