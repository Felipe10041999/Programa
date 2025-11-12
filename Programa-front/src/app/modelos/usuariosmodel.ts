
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
    correo: string
    usuario_bestvoiper: string
    extension: string
    no_diadema: String

}


