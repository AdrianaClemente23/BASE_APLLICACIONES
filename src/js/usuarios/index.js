import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data } from "jquery";

const formUsuario = document.getElementById('formUsuario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarUsuarios = document.getElementById('BtnBuscarUsuarios');
const InputUsuarioTel = document.getElementById('usuario_tel');
const InputUsuarioDpi = document.getElementById('usuario_dpi');
const seccionTabla = document.getElementById('seccionTabla');

const ValidarTelefono = () => {
    const CantidadDigitos = InputUsuarioTel.value;

    if (CantidadDigitos.length < 1) {
        InputUsuarioTel.classList.remove('is-valid', 'is-invalid');
    } else {
        if (CantidadDigitos.length != 8) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Revise el numero de telefono",
                text: "La cantidad de digitos debe ser exactamente 8 digitos",
                showConfirmButton: true,
            });

            InputUsuarioTel.classList.remove('is-valid');
            InputUsuarioTel.classList.add('is-invalid');
        } else {
            InputUsuarioTel.classList.remove('is-invalid');
            InputUsuarioTel.classList.add('is-valid');
        }
    }
}

const ValidarDpi = () => {
    const dpi = InputUsuarioDpi.value.trim();

    if (dpi.length < 1) {
        InputUsuarioDpi.classList.remove('is-valid', 'is-invalid');
    } else {
        if (dpi.length < 13) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "DPI INVALIDO",
                text: "El DPI debe tener al menos 13 caracteres",
                showConfirmButton: true,
            });

            InputUsuarioDpi.classList.remove('is-valid');
            InputUsuarioDpi.classList.add('is-invalid');
        } else {
            InputUsuarioDpi.classList.remove('is-invalid');
            InputUsuarioDpi.classList.add('is-valid');
        }
    }
}

const guardarUsuario = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formUsuario, ['usuario_id', 'usuario_token', 'usuario_fecha_creacion', 'usuario_fecha_contra', 'usuario_situacion', 'usuario_fotografia'])) {
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

    const body = new FormData(formUsuario);
    const url = "/clementeperez/usuarios/guardarAPI";
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
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarUsuarios();
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
    BtnGuardar.disabled = false;
}

const BuscarUsuarios = async () => {
    const url = `/clementeperez/usuarios/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Usuarios encontrados:', data);

            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(data).draw();
            }
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

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarUsuarios();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableUsuarios', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'usuario_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Primer Nombre', 
            data: 'usuario_nom1',
            width: '10%'
        },
        { 
            title: 'Segundo Nombre', 
            data: 'usuario_nom2',
            width: '10%'
        },
        { 
            title: 'Primer Apellido', 
            data: 'usuario_ape1',
            width: '10%'
        },
        { 
            title: 'Segundo Apellido', 
            data: 'usuario_ape2',
            width: '10%'
        },
        { 
            title: 'Correo', 
            data: 'usuario_correo',
            width: '15%'
        },
        { 
            title: 'Telefono', 
            data: 'usuario_tel',
            width: '8%'
        },
        { 
            title: 'DPI', 
            data: 'usuario_dpi',
            width: '10%'
        },
        { 
            title: 'Dirección', 
            data: 'usuario_direc',
            width: '12%'
        },
        {
            title: 'Fotografía',
            data: 'usuario_fotografia',
            width: '8%',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (data && data.trim() !== '') {
                    return `<img src="${data}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">`;
                } else {
                    return '<span class="text-muted">Sin foto</span>';
                }
            }
        },
        {
            title: 'Situación',
            data: 'usuario_situacion',
            width: '7%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'usuario_id',
            width: '5%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nom1="${row.usuario_nom1 || ''}"  
                         data-nom2="${row.usuario_nom2 || ''}"  
                         data-ape1="${row.usuario_ape1 || ''}"  
                         data-ape2="${row.usuario_ape2 || ''}"  
                         data-tel="${row.usuario_tel || ''}"  
                         data-dpi="${row.usuario_dpi || ''}"  
                         data-direc="${row.usuario_direc || ''}"  
                         data-correo="${row.usuario_correo || ''}"
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

    document.getElementById('usuario_id').value = datos.id;
    document.getElementById('usuario_nom1').value = datos.nom1;
    document.getElementById('usuario_nom2').value = datos.nom2;
    document.getElementById('usuario_ape1').value = datos.ape1;
    document.getElementById('usuario_ape2').value = datos.ape2;
    document.getElementById('usuario_tel').value = datos.tel;
    document.getElementById('usuario_dpi').value = datos.dpi;
    document.getElementById('usuario_direc').value = datos.direc;
    document.getElementById('usuario_correo').value = datos.correo;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formUsuario.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formUsuario, ['usuario_id', 'usuario_token', 'usuario_fecha_creacion', 'usuario_fecha_contra', 'usuario_situacion', 'usuario_fotografia', 'confirmar_contra'])) {
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

    const body = new FormData(formUsuario);
    const url = '/clementeperez/usuarios/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarUsuarios();
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
    BtnModificar.disabled = false;
}

const EliminarUsuarios = async (e) => {
    const idUsuario = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/clementeperez/usuarios/eliminar?id=${idUsuario}`;
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
                
                BuscarUsuarios();
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

datatable.on('click', '.eliminar', EliminarUsuarios);
datatable.on('click', '.modificar', llenarFormulario);
formUsuario.addEventListener('submit', guardarUsuario);

InputUsuarioTel.addEventListener('change', ValidarTelefono);
InputUsuarioDpi.addEventListener('change', ValidarDpi);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnBuscarUsuarios.addEventListener('click', MostrarTabla);