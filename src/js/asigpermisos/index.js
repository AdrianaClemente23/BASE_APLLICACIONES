import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formAsigPermiso = document.getElementById('formAsigPermiso');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarAsignaciones = document.getElementById('BtnBuscarAsignaciones');
const SelectUsuario = document.getElementById('asignacion_usuario_id');
const SelectAplicacion = document.getElementById('asignacion_app_id');
const SelectPermiso = document.getElementById('asignacion_permiso_id');
const SelectUsuarioAsigno = document.getElementById('asignacion_usuario_asigno');
const seccionTabla = document.getElementById('seccionTabla');

const cargarUsuarios = async () => {
    const url = `/clementeperez/asignacionpermisos/buscarUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
      
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            SelectUsuarioAsigno.innerHTML = '<option value="">Seleccione quién asigna</option>';
            
      
            data.forEach(usuario => {
                const option1 = document.createElement('option');
                option1.value = usuario.usuario_id;
                option1.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                SelectUsuario.appendChild(option1);
                
                const option2 = document.createElement('option');
                option2.value = usuario.usuario_id;
                option2.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                SelectUsuarioAsigno.appendChild(option2);
            });
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}


const cargarAplicaciones = async () => {
    const url = `/clementeperez/asignacionpermisos/buscarAplicacionesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectAplicacion.innerHTML = '<option value="">Seleccione una aplicación</option>';
            
            data.forEach(app => {
                const option = document.createElement('option');
                option.value = app.app_id;
                option.textContent = `${app.app_nombre_corto} - ${app.app_nombre_medium}`;
                SelectAplicacion.appendChild(option);
            });
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}


const cargarPermisosPorAplicacion = async (app_id) => {
    const url = `/clementeperez/asignacionpermisos/buscarPermisosAPI?app_id=${app_id}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectPermiso.innerHTML = '<option value="">Seleccione un permiso</option>';
            
            if (data && data.length > 0) {
                data.forEach(permiso => {
                    const option = document.createElement('option');
                    option.value = permiso.permiso_id;
                    option.textContent = `${permiso.permiso_nombre} (${permiso.permiso_tipo})`;
                    SelectPermiso.appendChild(option);
                });
            } else {
                SelectPermiso.innerHTML = '<option value="">No hay permisos disponibles para esta aplicación</option>';
            }
        } else {
            SelectPermiso.innerHTML = '<option value="">Error al cargar permisos</option>';
        }

    } catch (error) {
        console.log(error);
        SelectPermiso.innerHTML = '<option value="">Error al cargar permisos</option>';
    }
}


const guardarAsignacionPermiso = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formAsigPermiso, ['asignacion_id', 'asignacion_fecha_asignar', 'asignacion_fecha_quitar', 'asignacion_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formAsigPermiso);
    const url = "/clementeperez/asignacionpermisos/guardarAPI";
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos);
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });
            formAsigPermiso.reset();
            SelectPermiso.innerHTML = '<option value="">Primero seleccione una aplicación</option>';
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
        console.log(error);
    }

    BtnGuardar.disabled = false;
}


const BuscarAsignaciones = async () => {
    const url = `/clementeperez/asignacionpermisos/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            datatable.clear().draw();
            
            if (data.length > 0) {
                datatable.rows.add(data).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Sin datos",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}


const MostrarTabla = () => {
    seccionTabla.classList.remove('d-none');
    BuscarAsignaciones();
}


const datatable = new DataTable('#tablaAsignaciones', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            data: 'asignacion_id',
            width: '5%',
            render: (data, type, row, meta) => {
                return meta.row + 1;
            }
        },
        {
            title: 'Usuario',
            data: 'usuario_nom1',
            width: '15%',
            render: (data, type, row, meta) => {
                return `${row.usuario_nom1} ${row.usuario_ape1}`;
            }
        },
        {
            title: 'Aplicación',
            data: 'app_nombre_corto',
            width: '15%'
        },
        {
            title: 'Permiso',
            data: 'permiso_nombre',
            width: '20%'
        },
        {
            title: 'Tipo',
            data: 'permiso_tipo',
            width: '10%'
        },
        {
            title: 'Asignado por',
            data: 'asigno_nom1',
            width: '15%',
            render: (data, type, row, meta) => {
                return `${row.asigno_nom1} ${row.asigno_ape1}`;
            }
        },
        {
            title: 'Fecha',
            data: 'asignacion_fecha_asignar',
            width: '10%'
        },
        {
            title: 'Situación',
            data: 'asignacion_situacion',
            width: '5%',
            render: (data, type, row, meta) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'asignacion_id',
            width: '5%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.asignacion_usuario_id || ''}"  
                         data-app="${row.asignacion_app_id || ''}"  
                         data-permiso="${row.asignacion_permiso_id || ''}"  
                         data-asigno="${row.asignacion_usuario_asigno || ''}"  
                         data-motivo="${row.asignacion_motivo || ''}"
                         title="Modificar">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         title="Eliminar">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});


const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;

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

    window.scrollTo({
        top: 0,
    });
}


const limpiarTodo = () => {
    formAsigPermiso.reset();
    SelectPermiso.innerHTML = '<option value="">Primero seleccione una aplicación</option>';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}


const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formAsigPermiso, ['asignacion_id', 'asignacion_fecha_asignar', 'asignacion_fecha_quitar', 'asignacion_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formAsigPermiso);
    const url = "/clementeperez/asignacionpermisos/modificarAPI";
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos);
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
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
        console.log(error);
    }

    BtnModificar.disabled = false;
}


const EliminarAsignaciones = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "Confirmación",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/clementeperez/asignacionpermisos/eliminar?id=${idAsignacion}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
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
            console.log(error);
        }
    }
}


document.addEventListener('DOMContentLoaded', function() {

    cargarUsuarios();
    cargarAplicaciones();


    SelectAplicacion.addEventListener('change', (e) => {
        const app_id = e.target.value;
        if (app_id && app_id.trim() !== '') {
            cargarPermisosPorAplicacion(app_id);
        } else {
            SelectPermiso.innerHTML = '<option value="">Primero seleccione una aplicación</option>';
        }
    });


    datatable.on('click', '.eliminar', EliminarAsignaciones);
    datatable.on('click', '.modificar', llenarFormulario);
    formAsigPermiso.addEventListener('submit', guardarAsignacionPermiso);

    BtnLimpiar.addEventListener('click', limpiarTodo);
    BtnModificar.addEventListener('click', ModificarAsignacion);
    BtnBuscarAsignaciones.addEventListener('click', MostrarTabla);
});