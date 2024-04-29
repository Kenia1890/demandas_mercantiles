@extends('layouts.app')

@section('content')
<div class="container">
  
  <button type="button" class="btn btn-primary" id="botonAbrirModal" data-toggle="modal" data-target="#demandaCreateModal">
    Crear demanda
  </button>
  <div id="tableData" data-url="{{ route('demandas.listado') }}">
    <table id="demandaTable" class="display" style="width:100%">
      <thead>
          <tr>
              <th>ID</th>
              <th>Fecha de sentencia</th>
              <th>Oficio</th>
              <th>Trabajador deudor</th>
              <th>Acredor</th>
              <th>Tipo de importe</th>
              <th>Monto a Descontar</th>
              <th>Tipo de pago</th>
              <th>Clabe</th>
              <th>Banco</th>
              <th>Cuenta clabe</th>
          </tr>
      </thead>
    </table>
  </div>
  <!-- Modal para editar demanda -->
  <div class="modal fade" id="demandaEditModal" tabindex="-1" role="dialog" aria-labelledby="demandaEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="demandaEditModalLabel">Editar Demanda</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Formulario de edición -->
          <form id="editDemandaForm">
            <input type="hidden" name="demanda_id" id="demanda_id">
            <!-- Campos para editar inicio -->
            <div class="form-group">
              <label for="fecha">Fecha de sentencia:</label>
              <input type="date" class="form-control" id="fecha">
            </div>
            <div class="form-group">
              <label for="oficio">Oficio:</label>
              <input type="text" class="form-control" id="oficio">
            </div>
            <div class="form-group">
              <label for="monto_descontar">Monto a descontar:</label>
              <input type="text" class="form-control" id="monto_descontar">
            </div>
            <div class="form-group">
              <label for="tipo_importe">Tipo de importe:</label>
              <select class="form-select" id="tipo_importe">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="tipo_pago">Tipo de pago:</label>
              <select class="form-select" id="tipo_pago">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="banco">Banco:</label>
              <select class="form-select" id="banco">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="clabe">Clabe:</label>
              <input type="text" class="form-control" id="clabe">
            </div>
           <!-- Campos para editar fin -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" form="editDemandaForm" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  <!-- Modal para editar demanda -->
  </div>
   <!-- Modal para crear demanda -->
   <div class="modal fade" id="demandaCreateModal" tabindex="-1" role="dialog" aria-labelledby="demandaCreateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="demandaCreateModalLabel">Crear Demanda</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Formulario de creación incio -->
          <form id="createDemandaForm">
            <!-- Campos para creación inicio -->
            <div class="form-group">
              <label for="new_fecha">Fecha de sentencia:</label>
              <input type="date" class="form-control" id="new_fecha">
            </div>
            <div class="form-group">
              <label for="new_oficio">Oficio:</label>
              <input type="text" class="form-control" id="new_oficio">
            </div>
            <div class="form-group">
              <label for="new_trabajador">Lista de trabajadores:</label>
              <select class="form-select" id="new_trabajador">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="new_acredor">Acredor:</label>
              <input type="text" class="form-control" id="new_acredor">
            </div>
            <div class="form-group">
              <label for="new_monto_descontar">Monto a descontar:</label>
              <input type="text" class="form-control" id="new_monto_descontar">
            </div>
            <div class="form-group">
              <label for="new_tipo_importe">Tipo de importe:</label>
              <select class="form-select" id="new_tipo_importe">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="new_tipo_pago">Tipo de pago:</label>
              <select class="form-select" id="new_tipo_pago">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="new_banco">Banco:</label>
              <select class="form-select" id="new_banco">
                <option selected>Opciones</option>
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="form-group">
              <label for="new_clabe">Clabe:</label>
              <input type="text" class="form-control" id="new_clabe">
            </div>
             <!-- Campos para creación fin -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" form="createDemandaForm" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/demandas.js') }}"></script>
@endpush
