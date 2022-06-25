<?php

namespace Services;

use App\Models\Abonos;
use App\Models\DetalleAbonos;
use App\Models\Deudas;

class PagoParcial implements Pago
{
    public function pago($data): void
    {
        $deuda = new Deudas();
        $deuda->mantenimiento = $data->mantenimiento;
        $deuda->valor = $data->costo;
        $deuda->estado = 'PENDIENTE';
        $deuda->save();

        $abono = new Abonos();
        $abono->proveedor = $data->proveedor;
        $abono->valor = $data->abono;
        $abono->save();

        $detalle = new DetalleAbonos();
        $detalle->deuda = $deuda->id;
        $detalle->valor = $data->abono;
        $detalle->save();
    }
}
