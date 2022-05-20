<?php

namespace Services;

use App\Models\Deudas;

class PagoCredito implements Pago
{
    public function pago($data): void
    {
        $deuda = new Deudas();
        $deuda->mantenimiento = $data->mantenimiento;
        $deuda->valor = $data->costo;
        $deuda->estado = 'PENDIENTE';
        $deuda->save();
    }
}
