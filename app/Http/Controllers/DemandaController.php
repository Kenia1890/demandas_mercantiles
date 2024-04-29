<?php

namespace App\Http\Controllers;

use App\Models\Demanda;
use App\Models\Banco;
use App\Models\TipoImporte;
use App\Models\TipoPago;
use DataTables;
use App\Http\Requests\UpdateDemandaRequest;
use App\Models\Trabajador;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateDemandaRequest;

class DemandaController extends Controller
{
    public function index()
    {
        return view('demanda.index');
    }
    
    public function listado()
    {
            $query = Demanda::query()->with(['trabajador', 'tipoImporte', 'tipoPago', 'banco']);
            
            return DataTables::of($query)
                ->addColumn('action', function($row){
                    return '<a href="#" class="edit btn btn-primary btn-sm" data-id="'.$row->id.'">Edit</a>'
                        . ' <a href="#" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                })
                ->filterColumn('trabajador.nombre', function($query, $keyword) {
                    $query->whereHas('trabajador', function($query) use ($keyword) {
                        $query->whereRaw("CONCAT(nombre, ' ', apaterno, ' ', amaterno) LIKE ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('tipo_importe.tipo', function($query, $keyword) {
                    $query->whereHas('tipoImporte', function($query) use ($keyword) {
                        $query->where('tipo', 'LIKE', "%{$keyword}%");
                    });
                })
                ->filterColumn('tipo_pago.pago', function($query, $keyword) {
                    $query->whereHas('tipoPago', function($query) use ($keyword) {
                        $query->where('pago', 'LIKE', "%{$keyword}%");
                    });
                })
                ->filterColumn('banco.banco', function($query, $keyword) {
                    $query->whereHas('banco', function($query) use ($keyword) {
                        $query->where('banco', 'LIKE', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson(); 
    }
    public function store(CreateDemandaRequest $request)
    {
       
        $demanda = new Demanda($request->validated());
        $demanda->save();

        return response()->json(['message' => 'Demanda creada con éxito.', 'demanda' => $demanda]);
    }

    public function updateDemanda(UpdateDemandaRequest $request)
    {
        // Obtenemos los IDs necesarios una sola vez para evitar múltiples consultas
        $porcentajeTipoImporteId = TipoImporte::where('tipo', 'Porcentaje')->value('id');
        $efectivoTipoPagoId = TipoPago::where('pago', 'Efectivo')->value('id');
        $efectivoBancoId = Banco::where('banco', 'Efectivo')->value('id');

        DB::beginTransaction();
        try {
        
            $demanda = Demanda::findOrFail($request->demanda_id);

            if ($request->tipo_importe_id == $porcentajeTipoImporteId && $request->monto_descontar > 100) {
                return response()->json(['message' => 'El importe no debe ser mayor a 100 si es porcentaje.'], 422);
            }

            if ($request->tipo_pago_id == $efectivoTipoPagoId && $request->banco_id != $efectivoBancoId) {
                return response()->json(['message' => 'Se debe seleccionar el banco "Efectivo" si el tipo de pago es efectivo.'], 422);
            }

            if ($request->tipo_pago_id != $efectivoTipoPagoId) {
                $banco = Banco::find($request->banco_id);
                if (!str_starts_with($request->clabe, $banco->clabe_banco)) {
                    return response()->json(['message' => 'La clabe debe iniciar con la clave del banco.'], 422);
                }
            }

            $demanda->update($request->only([
                'fecha', 
                'oficio', 
                'monto_descontar', 
                'tipo_importe_id', 
                'tipo_pago_id', 
                'banco_id', 
                'clabe'
            ]));

            DB::commit();

            return response()->json(['message' => 'Demanda actualizada con éxito.', 'demanda' => $demanda]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar la demanda.'], 500);
        }
    }
    public function delete($id)
    {
        $demanda = Demanda::findOrFail($id);

        $demanda->delete();

        return response()->json(['message' => 'Demanda eliminada con éxito.']);
    }
    public function demanda($id)
    {
            return Demanda::query()->with(['trabajador', 'tipoImporte', 'tipoPago', 'banco'])->where('id',$id)->first();
           
    }
    public function TiposImporte()
    {
            return TipoImporte::all();
    }
    public function TiposPagos()
    {
            return TipoPago::all();
    }
    public function Bancos()
    {
            return Banco::all();
    }
    public function Trabajadores()
    {
            return Trabajador::all();
    }

  
}
