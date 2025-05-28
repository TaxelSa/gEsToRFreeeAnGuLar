export interface Tarea {
  id_tarea: number;
  nombre_tarea: string;
  fecha_entrega: string;
  hora_entrega: string;
  estado: 'pendiente' | 'en_progreso' | 'terminada'; // Asegúrate de que se incluya si lo usas
}
