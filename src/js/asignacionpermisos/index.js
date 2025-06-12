import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formAsignacionPermiso = document.getElementById('formAsignacionPermiso');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarAsignaciones = document.getElementById('BtnBuscarAsignaciones');
const SelectUsuario = document.getElementById('asignacion_usuario_id');
const SelectAplicacion = document.getElementById('asignacion_app_id');
const SelectPermiso = document.getElementById('asignacion_permiso_id');
const SelectUsuarioAsigno = document.getElementById('asignacion_usuario_asigno');
const seccionTabla = document.getElementById('seccionTabla');

// Verificar que los elementos existen
console.log('🔍 Verificando elementos del DOM:', {
    formAsignacionPermiso: !!formAsignacionPermiso,
    SelectUsuario: !!SelectUsuario,
    SelectAplicacion: !!SelectAplicacion,
    SelectPermiso: !!SelectPermiso,
    SelectUsuarioAsigno: !!SelectUsuarioAsigno
});

// Función para mostrar mensajes de error amigables
const mostrarError = (titulo, mensaje) => {
    Swal.fire({
        position: "center",
        icon: "error",
        title: titulo,
        text: mensaje,
        showConfirmButton: true,
    });
};

// Cargar usuarios
const cargarUsuarios = async () => {
    console.log('🔄 Iniciando carga de usuarios...');
    const url = `/clementeperez/asignacionpermisos/buscarUsuariosAPI`;
    
    try {
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📊 Datos de usuarios recibidos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1 && data && Array.isArray(data)) {
            console.log('✅ Cargando', data.length, 'usuarios');
            
            // Limpiar selects
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            SelectUsuarioAsigno.innerHTML = '<option value="">Seleccione quién asigna</option>';
            
            // Poblar selects
            data.forEach((usuario, index) => {
                const nombreCompleto = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                
                // Select principal de usuario
                const option1 = new Option(nombreCompleto, usuario.usuario_id);
                SelectUsuario.add(option1);
                
                // Select de quien asigna
                const option2 = new Option(nombreCompleto, usuario.usuario_id);
                SelectUsuarioAsigno.add(option2);
            });
            
            console.log('✅ Selects de usuarios poblados exitosamente');
        } else {
            console.error('❌ Error en datos de usuarios:', mensaje);
            mostrarError('Error al cargar usuarios', mensaje || 'No se pudieron cargar los usuarios');
        }

    } catch (error) {
        console.error('🚨 Error en cargarUsuarios:', error);
        mostrarError('Error de conexión', 'No se pudo conectar con el servidor para cargar usuarios');
    }
};

// Cargar aplicaciones
const cargarAplicaciones = async () => {
    console.log('🔄 Iniciando carga de aplicaciones...');
    const url = `/clementeperez/asignacionpermisos/buscarAplicacionesAPI`;
    
    try {
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📊 Datos de aplicaciones recibidos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1 && data && Array.isArray(data)) {
            console.log('✅ Cargando', data.length, 'aplicaciones');
            
            // Limpiar select
            SelectAplicacion.innerHTML = '<option value="">Seleccione una aplicación</option>';
            
            // Poblar select
            data.forEach((app) => {
                const textoApp = `${app.app_nombre_corto} - ${app.app_nombre_medium}`;
                const option = new Option(textoApp, app.app_id);
                SelectAplicacion.add(option);
            });
            
            console.log('✅ Select de aplicaciones poblado exitosamente');
        } else {
            console.error('❌ Error en datos de aplicaciones:', mensaje);
            mostrarError('Error al cargar aplicaciones', mensaje || 'No se pudieron cargar las aplicaciones');
        }

    } catch (error) {
        console.error('🚨 Error en cargarAplicaciones:', error);
        mostrarError('Error de conexión', 'No se pudo conectar con el servidor para cargar aplicaciones');
    }
};

// Cargar permisos por aplicación
const cargarPermisosPorAplicacion = async (app_id) => {
    console.log('🔄 Cargando permisos para aplicación ID:', app_id);
    const url = `/clementeperez/asignacionpermisos/buscarPermisosAPI?app_id=${app_id}`;
    
    try {
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📊 Datos de permisos recibidos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1 && data && Array.isArray(data)) {
            console.log('✅ Cargando', data.length, 'permisos');
            
            // Limpiar select
            SelectPermiso.innerHTML = '<option value="">Seleccione un permiso</option>';
            
            if (data.length > 0) {
                // Poblar select
                data.forEach((permiso) => {
                    const textoPermiso = `${permiso.permiso_nombre} (${permiso.permiso_tipo})`;
                    const option = new Option(textoPermiso, permiso.permiso_id);
                    SelectPermiso.add(option);
                });
                console.log('✅ Select de permisos poblado exitosamente');
            } else {
                SelectPermiso.innerHTML = '<option value="">No hay permisos disponibles para esta aplicación</option>';
                console.log('ℹ️ No hay permisos disponibles para esta aplicación');
            }
        } else {
            console.error('❌ Error en datos de permisos:', mensaje);
            SelectPermiso.innerHTML = '<option value="">Error al cargar permisos</option>';
        }

    } catch (error) {
        console.error('🚨 Error en cargarPermisosPorAplicacion:', error);
        SelectPermiso.innerHTML = '<option value="">Error al cargar permisos</option>';
    }
};

// Event listener para cambio de aplicación
if (SelectAplicacion) {
    SelectAplicacion.addEventListener('change', (e) => {
        const app_id = e.target.value;
        console.log('📝 Aplicación seleccionada:', app_id);
        
        if (app_id && app_id.trim() !== '') {
            cargarPermisosPorAplicacion(app_id);
        } else {
            SelectPermiso.innerHTML = '<option value="">Primero seleccione una aplicación</option>';
        }
    });
}

// Guardar asignación
const guardarAsignacionPermiso = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formAsignacionPermiso, ['asignacion_id', 'asignacion_fecha', 'asignacion_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formAsignacionPermiso);
    const url = "/clementeperez/asignacionpermisos/guardarAPI";

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();
        console.log('📤 Respuesta del servidor:', datos);
        
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
                timer: 3000
            });

            limpiarTodo();
            if (seccionTabla.style.display !== 'none') {
                BuscarAsignaciones();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('🚨 Error en guardarAsignacionPermiso:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnGuardar.disabled = false;
};

// Buscar asignaciones
const BuscarAsignaciones = async () => {
    const url = `/clementeperez/asignacionpermisos/buscarAPI`;

    try {
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('📊 Asignaciones encontradas:', data?.length || 0);

            if (datatable) {
                datatable.clear().draw();
                if (data && data.length > 0) {
                    datatable.rows.add(data).draw();
                }
            }
        } else {
            console.error('❌ Error al buscar asignaciones:', mensaje);
        }

    } catch (error) {
        console.error('🚨 Error en BuscarAsignaciones:', error);
    }
};

// Mostrar/ocultar tabla
const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none' || seccionTabla.style.display === '') {
        seccionTabla.style.display = 'block';
        BuscarAsignaciones();
        BtnBuscarAsignaciones.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Asignaciones';
    } else {
        seccionTabla.style.display = 'none';
        BtnBuscarAsignaciones.innerHTML = '<i class="bi bi-search me-2"></i>Ver Asignaciones';
    }
};

// Configuración del DataTable
const datatable = new DataTable('#TableAsignaciones', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col-auto" l> 
            <"col-auto" B> 
            <"col-auto" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-auto d-flex align-items-center" i> 
            <"col-md-auto d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[6, 'desc']], // Ordenar por fecha descendente
    pageLength: 10,
    responsive: true,
    columns: [
        {
            title: 'No.',
            data: 'asignacion_id',
            width: '5%',
            className: 'text-center',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario', 
            data: 'usuario_nom1',
            width: '12%',
            render: (data, type, row) => {
                return `<strong>${row.usuario_nom1} ${row.usuario_ape1}</strong>`;
            }
        },
        { 
            title: 'Aplicación', 
            data: 'app_nombre_corto',
            width: '10%',
            className: 'text-center'
        },
        { 
            title: 'Permiso', 
            data: 'permiso_nombre',
            width: '15%'
        },
        { 
            title: 'Tipo', 
            data: 'permiso_tipo',
            width: '8%',
            className: 'text-center',
            render: (data, type, row) => {
                const colorClass = {
                    'ADMIN': 'bg-danger',
                    'FUNCIONAL': 'bg-primary',
                    'LECTURA': 'bg-info',
                    'ESCRITURA': 'bg-warning'
                }[data] || 'bg-secondary';
                return `<span class="badge ${colorClass}">${data}</span>`;
            }
        },
        {
            title: 'Asignado por',
            data: 'asigno_nom1',
            width: '12%',
            render: (data, type, row) => {
                return `${row.asigno_nom1} ${row.asigno_ape1}`;
            }
        },
        {
            title: 'Fecha',
            data: 'asignacion_fecha',
            width: '10%',
            className: 'text-center',
            render: (data, type, row) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT');
                }
                return '<span class="text-muted">N/A</span>';
            }
        },
        { 
            title: 'Motivo', 
            data: 'asignacion_motivo',
            width: '15%',
            render: (data, type, row) => {
                return data || '<span class="text-muted">Sin motivo</span>';
            }
        },
        {
            title: 'Estado',
            data: 'asignacion_situacion',
            width: '8%',
            className: 'text-center',
            render: (data, type, row) => {
                return data == 1 
                    ? '<span class="badge bg-success">ACTIVO</span>' 
                    : '<span class="badge bg-secondary">INACTIVO</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'asignacion_id',
            width: '5%',
            className: 'text-center',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                    <div class='btn-group' role='group'>
                        <button class='btn btn-sm btn-outline-warning modificar' 
                            data-id="${data}" 
                            data-usuario="${row.asignacion_usuario_id || ''}"  
                            data-app="${row.asignacion_app_id || ''}"  
                            data-permiso="${row.asignacion_permiso_id || ''}"  
                            data-asigno="${row.asignacion_usuario_asigno || ''}"  
                            data-motivo="${row.asignacion_motivo || ''}"
                            title="Modificar">
                            <i class='bi bi-pencil'></i>
                        </button>
                        <button class='btn btn-sm btn-outline-danger eliminar' 
                            data-id="${data}"
                            title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
            }
        }
    ]
});

// Funciones auxiliares
const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;
    console.log('📝 Llenando formulario con:', datos);

    document.getElementById('asignacion_id').value = datos.id;
    document.getElementById('asignacion_usuario_id').value = datos.usuario;
    document.getElementById('asignacion_app_id').value = datos.app;
    document.getElementById('asignacion_usuario_asigno').value = datos.asigno;
    document.getElementById('asignacion_motivo').value = datos.motivo;

    if (datos.app) {
        await cargarPermisosPorAplicacion(datos.app);
        setTimeout(() => {
            document.getElementById('asignacion_permiso_id').value = datos.permiso;
        }, 300);
    }

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const limpiarTodo = () => {
    formAsignacionPermiso.reset();
    SelectPermiso.innerHTML = '<option value="">Primero seleccione una aplicación</option>';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
};

const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formAsignacionPermiso, ['asignacion_id', 'asignacion_fecha', 'asignacion_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formAsignacionPermiso);
    const url = '/clementeperez/asignacionpermisos/modificarAPI';

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Actualizado!",
                text: mensaje,
                showConfirmButton: true,
                timer: 3000
            });

            limpiarTodo();
            BuscarAsignaciones();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('🚨 Error en ModificarAsignacion:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnModificar.disabled = false;
};

const EliminarAsignaciones = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const resultado = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Confirmar eliminación?",
        text: '¿Está seguro que desea eliminar esta asignación de permiso?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        reverseButtons: true
    });

    if (resultado.isConfirmed) {
        const url = `/clementeperez/asignacionpermisos/eliminar?id=${idAsignacion}`;

        try {
            const respuesta = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const datos = await respuesta.json();
            const { codigo, mensaje } = datos;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Eliminado!",
                    text: mensaje,
                    showConfirmButton: true,
                    timer: 3000
                });
                
                BuscarAsignaciones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.error('🚨 Error en EliminarAsignaciones:', error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
};

// Inicialización
console.log('🚀 Iniciando aplicación de asignación de permisos...');

// Cargar datos iniciales
document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();
    cargarAplicaciones();
});

// Event listeners
if (datatable) {
    datatable.on('click', '.eliminar', EliminarAsignaciones);
    datatable.on('click', '.modificar', llenarFormulario);
}

if (formAsignacionPermiso) {
    formAsignacionPermiso.addEventListener('submit', guardarAsignacionPermiso);
}

if (BtnLimpiar) {
    BtnLimpiar.addEventListener('click', limpiarTodo);
}

if (BtnModificar) {
    BtnModificar.addEventListener('click', ModificarAsignacion);
}

if (BtnBuscarAsignaciones) {
    BtnBuscarAsignaciones.addEventListener('click', MostrarTabla);
}

// Ejecutar carga inicial
cargarUsuarios();
cargarAplicaciones();

console.log('✅ Aplicación inicializada correctamente');




// DEBUG TEMPORAL - Añadir al final del archivo JavaScript
console.log('🔧 INICIANDO DEBUG TEMPORAL...');

// Función de debug para probar APIs directamente
const debugAPIs = async () => {
    console.log('🧪 Probando APIs...');
    
    // Test API de usuarios
    try {
        const urlUsuarios = `/clementeperez/asignacionpermisos/buscarUsuariosAPI`;
        console.log('🔍 Probando URL usuarios:', urlUsuarios);
        const respUsuarios = await fetch(urlUsuarios);
        const dataUsuarios = await respUsuarios.json();
        console.log('👥 Respuesta usuarios:', dataUsuarios);
    } catch (error) {
        console.error('❌ Error en API usuarios:', error);
    }
    
    // Test API de aplicaciones
    try {
        const urlApps = `/clementeperez/asignacionpermisos/buscarAplicacionesAPI`;
        console.log('🔍 Probando URL aplicaciones:', urlApps);
        const respApps = await fetch(urlApps);
        const dataApps = await respApps.json();
        console.log('🏢 Respuesta aplicaciones:', dataApps);
    } catch (error) {
        console.error('❌ Error en API aplicaciones:', error);
    }
    
    // Test API de permisos
    try {
        const urlPermisos = `/clementeperez/asignacionpermisos/buscarPermisosAPI?app_id=1`;
        console.log('🔍 Probando URL permisos:', urlPermisos);
        const respPermisos = await fetch(urlPermisos);
        const dataPermisos = await respPermisos.json();
        console.log('🔐 Respuesta permisos:', dataPermisos);
    } catch (error) {
        console.error('❌ Error en API permisos:', error);
    }
};

// Ejecutar debug después de 3 segundos
setTimeout(() => {
    console.log('⏰ Ejecutando debug de APIs...');
    debugAPIs();
}, 3000);

// Función para cargar manualmente (para probar)
window.cargarManualmente = () => {
    console.log('🔄 Carga manual iniciada...');
    cargarUsuarios();
    cargarAplicaciones();
};

console.log('✅ Debug temporal configurado. Usa cargarManualmente() en consola si es necesario.');