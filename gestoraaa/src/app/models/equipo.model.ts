// src/app/models/equipo.model.ts
export class Equipo {
  constructor(
    public codigo_equipo: string,
    public nombre_equipo: string,
    public descripcion: string,
    public numero_control: string
  ) {}

  getResumen(): string {
    return `${this.nombre_equipo} (${this.codigo_equipo})`;
  }
}
