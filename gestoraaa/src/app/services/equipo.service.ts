import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Equipo } from '../models/equipo.model';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class EquipoService {
  private baseUrl = 'http://localhost/vueGestorFree/GestorFree/php';
  private apiUrl = 'http://localhost/vueGestorFree/GestorFree/php';
  
  constructor(private http: HttpClient) {}

  obtenerEquipos(): Observable<Equipo[]> {
    return this.http.get<Equipo[]>(`${this.baseUrl}/teams-table.php`);
  }
  getEquipos(): Observable<Equipo[]> {
  return this.http.get<Equipo[]>(`${this.apiUrl}/equipo.php`);
}


  agregarEquipo(equipo: Equipo): Observable<any> {
    return this.http.post(`${this.baseUrl}/teams-table-insert.php`, equipo);
  }

  eliminarEquipo(codigo: string): Observable<any> {
    return this.http.delete(`${this.baseUrl}/teams-table-delete.php?codigo_equipo=${codigo}`);
  }

  actualizarEquipo(equipo: Equipo): Observable<any> {
    return this.http.post(`${this.baseUrl}/teams-table-update.php`, equipo);
  }
}
