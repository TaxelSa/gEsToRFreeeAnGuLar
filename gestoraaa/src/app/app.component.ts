// src/app/app.component.ts
import { Component } from '@angular/core';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { Router, RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [SidebarComponent, RouterOutlet, CommonModule],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  constructor(public router: Router) {}

  // Verifica si estamos en la ruta login
  isLoginRoute(): boolean {
    return this.router.url === '/login';
  }
}
