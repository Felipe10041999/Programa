export interface UsuarioModel {
    id: number
    nombres: string
    apellidos: string
    cedula: string
    telefono: String
    cartera: String
    numero_equipo: string
    equipo_usuario: {
        id: number;
        usuario : String;
        clave: String;
    }
    huella: {
        id: number
        usuario: string
        clave: string
        nombre_usuario: string
    }
    best: {
        nombre_usuario: String
        extension: String
        usuario: String
        clave: String
    }
    correo: string
    no_diadema: String
    almuerzo: number

}


