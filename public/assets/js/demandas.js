var table;
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    table = inicializarDataTable();
    configurarFormularios();
    manejarEventosTable();
    configurarModales();
});

function inicializarDataTable() {
    var url = $('#tableData').data('url');
    return $('#demandaTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'fecha', name: 'fecha' },
            { data: 'oficio', name: 'oficio' },
            { 
                data: 'trabajador.nombre', 
                name: 'trabajador.nombre', 
                defaultContent: '', 
                render: function(data, type, row) {
                    return row.trabajador ? row.trabajador.nombre + ' ' + row.trabajador.apaterno + ' ' + row.trabajador.amaterno : '';
                }
            },
            { data: 'acreedor', name: 'acreedor', orderable: true, searchable: true },
            { data: 'tipo_importe.tipo', name: 'tipo_importe.tipo', orderable: true, searchable: true },
            { data: 'monto_descontar', name: 'monto_descontar', orderable: true, searchable: true },
            { data: 'tipo_pago.pago', name: 'tipo_pago.pago', orderable: true, searchable: true },
            { data: 'clabe', name: 'clabe' },
            { data: 'banco.banco', name: 'banco.banco', defaultContent: 'N/A', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false, orderable: true, searchable: true }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    });
}

function configurarFormularios() {
    $('#editDemandaForm').on('submit', enviarFormularioEdicion);
    $('#createDemandaForm').on('submit', enviarFormularioCreacion);
}

function manejarEventosTable() {
    $('#demandaTable').on('click', '.delete', manejarEliminarDemanda);
    $('#demandaTable').on('click', '.edit', manejarEditarDemanda);
}

function configurarModales() {
    $('#botonAbrirModal').click(function() {
        $('#demandaCreateModal').modal('show');
        cargarSelectsIniciales();
    });
}
function cargarSelects() {
    cargarSelect('#tipo_importe', '/listado/TiposImporte');
    cargarSelect('#tipo_pago', '/listado/TiposPagos');
    cargarSelect('#banco', '/listado/Bancos');
}
function cargarSelect(selector, url) {
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            var select = $(selector);
            select.empty();
            select.append($('<option>').val('').text('Seleccione una opción'));
            $.each(data, function(i, item) {
                if (selector === '#new_trabajador') {
                    var fullName = item.nombre + ' ' + item.apaterno + ' ' + item.amaterno;
                    select.append($('<option>').val(item.id).text(fullName));
                } else {
                    select.append($('<option>').val(item.id).text(item.nombre || item.tipo || item.pago || item.banco));
                }
            });
        },
        error: function(xhr, status, error) {
            console.error(`Error al cargar datos para: ${selector}, ${error}`);
        }
    });
}
function enviarFormularioEdicion(e) {
    e.preventDefault();
    var formData = recogerDatosFormularioEdicion();
    formData['_token'] = $('meta[name="csrf-token"]').attr('content'); // Incluye el token CSRF
    enviarPeticionAjax('/demanda/update', 'POST', formData, 'La demanda ha sido actualizada.');
}

function enviarFormularioCreacion(e) {
    e.preventDefault();
    var formData = recogerDatosFormularioCreacion();
    formData['_token'] = $('meta[name="csrf-token"]').attr('content'); // Incluye el token CSRF
    enviarPeticionAjax('/demanda/create', 'POST', formData, 'La demanda ha sido creada.');
}


function recogerDatosFormularioEdicion() {
    var data = {
        'demanda_id': $('#demanda_id').val(), // Asegúrate de que este input hidden esté correctamente asignado al abrir el formulario de edición
        'fecha': $('#fecha').val(),
        'oficio': $('#oficio').val(),
        'monto_descontar': $('#monto_descontar').val(),
        'tipo_importe_id': $('#tipo_importe').val(),
        'tipo_pago_id': $('#tipo_pago').val(),
        'banco_id': $('#banco').val(),
        'clabe': $('#clabe').val()
    };
    return data;
}
function recogerDatosFormularioCreacion() {
    var data = {
        'fecha': $('#new_fecha').val(),
        'oficio': $('#new_oficio').val(),
        'id_trabajador': $('#new_trabajador').val(),
        'acreedor': $('#new_acredor').val(),  // Asegúrate de corregir la ID en el HTML si es necesario
        'monto_descontar': $('#new_monto_descontar').val(),
        'id_tipo_importe': $('#new_tipo_importe').val(),
        'id_tipo_pago': $('#new_tipo_pago').val(),
        'id_banco': $('#new_banco').val(),
        'clabe': $('#new_clabe').val()
    };
    return data;
}
function enviarPeticionAjax(url, method, data, successMessage) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Confirmar esta operación.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    console.log(response);
                    $('#demandaEditModal, #demandaCreateModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Completado!', successMessage, 'success');
                },
                error: function(xhr) {
                    var errorMessages = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessages = Object.values(xhr.responseJSON.errors).join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessages = xhr.responseJSON.message;
                    } else {
                        errorMessages = 'Ha ocurrido un error desconocido.';
                    }
                    Swal.fire('Error', errorMessages, 'error');
                }
            });
        }
    });
}


function manejarEliminarDemanda() {
    var demandaId = $(this).data('id');
    enviarPeticionAjax('/demanda/' + demandaId + '/delete', 'POST', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'DELETE'
    }, 'La demanda ha sido eliminada.');
}

function manejarEditarDemanda() {
    var demandaId = $(this).data('id'); 
    cargarSelects();
    $.ajax({
        url: '/demanda/' + demandaId,
        method: 'GET',
        success: function(response) {
            console.log(response);
            $('#demanda_id').val(response.id);
            $('#demanda_id').val(response.id);
            $('#fecha').val(response.fecha);
            $('#oficio').val(response.oficio);
            $('#monto_descontar').val(response.monto_descontar);
            $('#tipo_importe').val(response.tipo_importe.id);
            $('#tipo_pago').val(response.tipo_pago.id);
            $('#banco').val(response.banco.id);
            $('#clabe').val(response.clabe);
            $('#demandaEditModal').modal('show');
        }
    });
}

function cargarSelectsIniciales() {
    cargarSelect('#new_trabajador', '/listado/Trabajadores');
    cargarSelect('#new_tipo_importe', '/listado/TiposImporte');
    cargarSelect('#new_tipo_pago', '/listado/TiposPagos');
    cargarSelect('#new_banco', '/listado/Bancos');
}
