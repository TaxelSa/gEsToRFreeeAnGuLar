// src/app/components/sidebar/sidebar.component.ts
import { Component } from '@angular/core';
import { Router, RouterModule } from '@angular/router';


@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [RouterModule],
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent {
  constructor(private router: Router) {}

  cerrarSesion(): void {
    localStorage.removeItem('numero_control');
    this.router.navigate(['/login']);
  }
}
