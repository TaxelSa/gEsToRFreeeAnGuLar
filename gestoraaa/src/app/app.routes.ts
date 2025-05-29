import { Routes } from '@angular/router';
import { ProyectoCrudComponent } from './components/proyecto/proyecto.component';
import { EquiposComponent } from './components/equipos/equipos.component';
import { TareasComponent } from './components/tareas/tareas.component';
import { LoginComponent } from './components/login/login.component'; // Asegúrate que esté importado

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'proyectos', component: ProyectoCrudComponent },
  { path: 'equipos', component: EquiposComponent },
  { path: 'tareas', component: TareasComponent },
  { path: '', redirectTo: 'login', pathMatch: 'full' }, // Redirige al login al iniciar
];
