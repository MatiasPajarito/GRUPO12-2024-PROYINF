# Simulación de lista de solicitudes
solicitudes = []

# Función para realizar la petición de un boletín
def realizar_peticion_boletin():
    tema = input("Ingrese el tema del boletín: ")
    
    # Pedir el plazo al usuario
    print("Seleccione el plazo de entrega:")
    print("1. Corto plazo (1 mes)")
    print("2. Mediano plazo (3 meses)")
    print("3. Largo plazo (6 meses)")
    
    plazo_seleccionado = input("Ingrese el número correspondiente al plazo: ")

    # Asignar el plazo según la opción seleccionada
    if plazo_seleccionado == '1':
        plazo = "1 mes"
    elif plazo_seleccionado == '2':
        plazo = "3 meses"
    elif plazo_seleccionado == '3':
        plazo = "6 meses"
    else:
        print("Opción inválida. Se asignará el plazo por defecto de 1 mes.")
        plazo = "1 mes"

    nueva_solicitud = {
        "id": len(solicitudes) + 1,
        "tema": tema,
        "fecha_solicitud": "2023-09-30",  # Aquí puedes cambiar por la fecha actual con datetime si lo prefieres
        "prioridad": None,
        "plazo": plazo
    }
    
    solicitudes.append(nueva_solicitud)
    print(f"Petición de boletín para el tema '{tema}' con plazo {plazo} ha sido registrada.")

# Función para manejar el flujo del programa
def iniciar_sistema():
    while True:
        realizar_peticion_boletin()
        
        # Preguntar si quiere realizar otra petición
        continuar = input("¿Desea realizar otra petición? (S/N): ").strip().lower()
        if continuar != 's':
            break
    
    # Imprimir las solicitudes registradas
    print("\nSolicitudes registradas:")
    for solicitud in solicitudes:
        print(solicitud)

# Iniciar el sistema
iniciar_sistema()

