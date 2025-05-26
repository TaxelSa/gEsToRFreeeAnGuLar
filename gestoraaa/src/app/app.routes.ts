// src/app/app.routes.ts
import { Routes } from '@angular/router';
import { ProyectoCrudComponent } from './components/proyecto/proyecto.component';
import { EquiposComponent } from './components/equipos/equipos.component';
import { TareasComponent } from './components/tareas/tareas.component';

export const routes: Routes = [
  { path: 'proyectos', component: ProyectoCrudComponent },
  { path: 'equipos', component: EquiposComponent },
  { path: 'tareas', component: TareasComponent },
  { path: '', redirectTo: '/proyectos', pathMatch: 'full' },
];
