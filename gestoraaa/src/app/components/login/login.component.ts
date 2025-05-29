import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ApiService } from '../../services/api.service';
import { Login } from '../../models/login.model'; // <-- Importamos el modelo Login

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule
  ]
})
export class LoginComponent {
  loginForm: FormGroup;
  errorMessage = '';

  constructor(
    private fb: FormBuilder,
    private apiService: ApiService,
    private router: Router
  ) {
    this.loginForm = this.fb.group({
      numero_control: ['', Validators.required],
      password: ['', Validators.required]
    });
  }

  onSubmit(): void {
    if (this.loginForm.invalid) {
      this.errorMessage = 'Todos los campos son obligatorios';
      return;
    }

    // Creamos un objeto Login a partir de los valores del formulario
    const datos: Login = {
      numero_control: this.loginForm.value.numero_control,
      password: this.loginForm.value.password
    };

    this.apiService.login(datos).subscribe({
      next: (resultado: any) => {
        if (resultado.success) {
          localStorage.setItem('numero_control', datos.numero_control);
          this.router.navigate(['/proyectos']);
        } else {
          this.errorMessage = resultado.message || 'Credenciales incorrectas';
        }
      },
      error: (error) => {
        console.error('Error de conexión:', error);
        this.errorMessage = 'Error de conexión con el servidor';
      }
    });
  }
}
