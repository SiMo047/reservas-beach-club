<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class ReservaController extends Controller
{
    //aforo maximo de cada zona
    private const AFORO = [
        'Salón'   => 28,
        'Terraza' => 20,
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::orderBy('fecha')->orderBy('hora')->paginate(10);

        return  view('dashboard',compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reservation');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //si hay algun error se vuelve siempre al formulario de reserva
        $datos = $this->validar($request, route('reserva.create'));

        //si no queda sitio en esa zona y franja horaria no se guarda
        if (!$this->hayAforo($datos['fecha'], $datos['hora'], $datos['zona'], $datos['n_personas'])) {
            return view('aforo_completo');
        }

        Reserva::create($datos);

        return view('exito');
    }



    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reserva=Reserva::findOrFail($id);

        return view('editreserva',compact( 'reserva'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reserva=Reserva::findOrFail($id);
        $datos = $this->validar($request);

        //al modificar tambien se comprueba el aforo (sin contar la propia reserva)
        if (!$this->hayAforo($datos['fecha'], $datos['hora'], $datos['zona'], $datos['n_personas'], $reserva->id)) {
            return back()->withInput()->with('aforo_completo', true);
        }

        $reserva->update($datos);

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reserva=Reserva::findOrFail($id);
        $reserva->delete();

        return redirect()->route('dashboard');
    }

    //validacion de los datos del formulario (la misma para crear y modificar)
    private function validar(Request $request, ?string $volverA = null): array
    {
        $validador = Validator::make($request->all(), [
            'nombre'     => 'required|string|max:100',
            'n_telefono' => ['required', 'regex:/^[0-9+\s]{9,15}$/'],
            'n_personas' => 'required|integer|min:1|max:18',
            'fecha'      => 'required|date|after_or_equal:today',
            'hora'       => 'required|date_format:H:i,H:i:s',
            'zona'       => 'required|in:Terraza,Salón',
        ], [
            'required'             => 'Rellena el campo :attribute.',
            'max'                  => 'El campo :attribute es demasiado largo.',
            'n_telefono.regex'     => 'El número de teléfono no es válido.',
            'n_personas.integer'   => 'El número de personas no es válido.',
            'n_personas.min'       => 'La reserva tiene que ser de al menos 1 persona.',
            'n_personas.max'       => 'El máximo es de 18 personas por reserva.',
            'fecha.date'           => 'La fecha no es válida.',
            'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'hora.date_format'     => 'La hora no es válida.',
            'zona.in'              => 'Elige Terraza o Salón.',
        ], [
            'nombre'     => 'nombre',
            'n_telefono' => 'número de teléfono',
            'n_personas' => 'número de personas',
            'fecha'      => 'fecha',
            'hora'       => 'hora',
            'zona'       => 'zona',
        ]);

        if ($validador->fails()) {
            $redireccion = $volverA ? redirect($volverA) : back();

            throw new ValidationException(
                $validador,
                $redireccion->withErrors($validador)->withInput()
            );
        }

        return $validador->validated();
    }

    //comprueba si caben n_personas mas en esa zona dentro de la franja de una hora
    private function hayAforo($fecha, $hora, $zona, $n_personas, $excluirId = null): bool
    {
        $aforo_max = self::AFORO[$zona];

        //rango de la franja: de en punto a y 59
        $hora_inicio = Carbon::parse($hora)->startOfHour();
        $hora_fin = $hora_inicio->copy()->addHour()->subMinute();

        $ocupadas = Reserva::where('fecha', $fecha)
            ->where('zona', $zona)
            ->whereBetween('hora', [$hora_inicio->format('H:i:s'), $hora_fin->format('H:i:59')])
            ->when($excluirId, fn ($consulta) => $consulta->where('id', '!=', $excluirId))
            ->sum('n_personas');

        return ($ocupadas + $n_personas) <= $aforo_max;
    }
}
