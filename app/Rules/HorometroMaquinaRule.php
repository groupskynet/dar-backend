<?php

namespace App\Rules;

use App\Models\Maquinas;
use App\Models\Tickets;
use Illuminate\Contracts\Validation\Rule;

class HorometroMaquinaRule implements Rule
{
    public int $maquina;

    public function __construct($maquina)
    {
        $this->maquina = $maquina;
    }

    public function passes($attribute, $value)
    {
        $maquina = Maquinas::find($this->maquina);
        $ticket =  Tickets::where([['estado', 'CONFIRMADO'], ['maquina', $maquina->id]])->last();

        $flag = true;
        if ($ticket > 0 && $value < $ticket->horometroFinal) {
            $flag = false;
        } else if ($value < $maquina->horometro) {
            $flag = false;
        }

        return $flag;
    }

    public function message()
    {
        return 'El horometro no puede ser menor al horometro de la maquina.';
    }
}
