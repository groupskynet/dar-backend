<?php

namespace services;

use App\Models\Abonos;
use App\Models\DetalleAbonos;
use App\Models\Deudas;

interface Pago {
   public function pago($data) : void;
}


class PagoParcial implements Pago {
  public function pago($data) : void {
        $deuda = new Deudas();
        $deuda->mantenimiento = $data->id;
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

class PagoEfectivo implements Pago {
  public function pago($data) : void {}
}


class PagoCredito implements Pago {
    public function pago($data) : void {
        $deuda = new Deudas();
        $deuda->mantenimiento = $data->id;
        $deuda->valor = $data->costo;
        $deuda->estado = 'PENDIENTE';
        $deuda->save();
    }
}
