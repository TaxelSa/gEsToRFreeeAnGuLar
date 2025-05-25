// src/app/models/proyecto.model.ts
export class Proyecto {
  constructor(
    public id_proyecto: number,
    public nombre_proyecto: string,
    public descripcion: string,
    public fecha_entrega: Date,
    public id_estado: number
  ) {}

  estaAtrasado(): boolean {
    return new Date() > new Date(this.fecha_entrega);
  }

  diasRestantes(): number {
    const diff = new Date(this.fecha_entrega).getTime() - new Date().getTime();
    return Math.ceil(diff / (1000 * 60 * 60 * 24));
  }
}
