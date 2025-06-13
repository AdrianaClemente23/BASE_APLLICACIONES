import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formPermiso = document.getElementById('formPermiso');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarPermisos = document.getElementById('BtnBuscarPermisos');
const SelectUsuario = document.getElementById('usuario_id');
const SelectAplicacion = document.getElementById('app_id');
const SelectUsuarioAsigno = document.getElementById('permiso_usuario_asigno');
const seccionTabla = document.getElementById('seccionTabla');

const cargarUsuarios = async () => {
    const url = `/clementeperez/permisos/buscarUsuariosAPI`;
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
                const option = document.createElement('option');
                option.value = usuario.usuario_id;
                option.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                SelectUsuario.appendChild(option);
                
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
    const url = `/clementeperez/permisos/buscarAplicacionesAPI`;
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
                option.textContent = app.app_nombre_corto;
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

const guardarPermiso = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formPermiso, ['permiso_id', 'permiso_fecha', 'permiso_situacion'])) {
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

    const body = new FormData(formPermiso);
    const url = "/clementeperez/permisos/guardarAPI";
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
            formPermiso.reset();
            BuscarPermisos();
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

const BuscarPermisos = async () => {
    const url = `/clementeperez/permisos/buscarAPI`;
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
    BuscarPermisos();
}

const datatable = new DataTable('#tablaPermisos', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            data: 'permiso_id',
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
            title: 'Clave',
            data: 'permiso_clave',
            width: '15%'
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
            title: 'Situación',
            data: 'permiso_situacion',
            width: '5%',
            render: (data, type, row, meta) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'permiso_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.usuario_id || ''}"  
                         data-app="${row.app_id || ''}"  
                         data-nombre="${row.permiso_nombre || ''}"  
                         data-clave="${row.permiso_clave || ''}"  
                         data-desc="${row.permiso_desc || ''}"  
                         data-tipo="${row.permiso_tipo || ''}"  
                         data-asigno="${row.permiso_usuario_asigno || ''}"  
                         data-motivo="${row.permiso_motivo || ''}"
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

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('permiso_id').value = datos.id;
    document.getElementById('usuario_id').value = datos.usuario;
    document.getElementById('app_id').value = datos.app;
    document.getElementById('permiso_nombre').value = datos.nombre;
    document.getElementById('permiso_clave').value = datos.clave;
    document.getElementById('permiso_desc').value = datos.desc;
    document.getElementById('permiso_tipo').value = datos.tipo;
    document.getElementById('permiso_usuario_asigno').value = datos.asigno;
    document.getElementById('permiso_motivo').value = datos.motivo;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formPermiso.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarPermiso = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formPermiso, ['permiso_id', 'permiso_fecha', 'permiso_situacion'])) {
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

    const body = new FormData(formPermiso);
    const url = "/clementeperez/permisos/modificarAPI";
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
            BuscarPermisos();
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

const EliminarPermisos = async (e) => {
    const idPermiso = e.currentTarget.dataset.id;

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
        const url = `/clementeperez/permisos/eliminar?id=${idPermiso}`;
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
                
                BuscarPermisos();
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


    datatable.on('click', '.eliminar', EliminarPermisos);
    datatable.on('click', '.modificar', llenarFormulario);
    formPermiso.addEventListener('submit', guardarPermiso);

    BtnLimpiar.addEventListener('click', limpiarTodo);
    BtnModificar.addEventListener('click', ModificarPermiso);
    BtnBuscarPermisos.addEventListener('click', MostrarTabla);
});