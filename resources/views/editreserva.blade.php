<x-dash>

<div class="container mx-auto mt-20 my-20 px-5 py-8 bg-white rounded-lg shadow-lg max-w-xl">

    <h2 class="text-2xl font-bold mb-6 text-center text-black">MODIFICAR RESERVA</h2>
    <form method="POST" action="{{ route('updatereserva',  $reserva->id) }}" class="space-y-4" id="formreserva">
        @csrf
     

        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="nombre">
                Nombre
            </label>
            <input class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="nombre" name="nombre" type="text" placeholder="" value="{{ old('nombre', $reserva->nombre) }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="n_telefono">
                Número Teléfono
            </label>
            <input class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="n_telefono" name="n_telefono" type="tel" placeholder="" value="{{ old('n_telefono', $reserva->n_telefono) }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="n_personas">
                Número de Personas
            </label>
            <input class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="n_personas" name="n_personas" type="number" min="1" max="18" placeholder="" value="{{ old('n_personas', $reserva->n_personas) }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="fecha">
                Fecha
            </label>
            <input class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="fecha" name="fecha" type="date" value="{{ old('fecha', $reserva->fecha) }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="hora">
                Hora
            </label>
            <input class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="hora" name="hora" type="time" value="{{ old('hora', substr($reserva->hora, 0, 5)) }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-black text-sm font-bold mb-2" for="zona">
                Zona
            </label>
            <select class="shadow appearance-none border border-gray-300 w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="zona" name="zona" required>
                <option value="Terraza" @selected(old('zona', $reserva->zona) == 'Terraza')>Terraza</option>
                <option value="Salón" @selected(old('zona', $reserva->zona) == 'Salón')>Salón</option>
            </select>
        </div>
        <div class="flex items-center justify-center pt-10">
            <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-black dark:hover:border-gray-600 dark:focus:ring-black">
                MODIFICAR RESERVA
            </button>
        </div>
    </form>
</div>

                
            



        <!-- Modal de aviso (aforo completo o datos no validos) -->
        @if (session('aforo_completo') || $errors->any())
        <div id="popup-modal" tabindex="-1" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center">
                <div class="relative bg-white rounded-lg shadow-lg p-6">
                    <div class="p-4 md:p-5 text-center">
                        <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x text-green-500 mb-4"></i>
                        <h3 class="mb-5 text-lg font-normal text-black">
                            @if (session('aforo_completo'))
                                No se ha podido modificar la reserva: Aforo Completo <br>Prueba a cambiar de zona u hora
                            @else
                                @foreach ($errors->all() as $error)
                                    {{ $error }} <br>
                                @endforeach
                            @endif
                        </h3>
                        <button type="button" class="text-white bg-green-500 hover:bg-green-600 focus:outline-none font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" data-modal-hide="popup-modal">Cerrar</button>
                    </div>
                </div>
        </div>

        <script>
            document.addEventListener('click', function(event) {
                var modal = document.getElementById('popup-modal');
                if (event.target.matches('[data-modal-hide="popup-modal"]') || event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        </script>
        @endif

 <!--SCRIPT para comprobar fecha y hora -->
 <script>
            let fechaInput = document.getElementById('fecha');
            let horaInput = document.getElementById('hora');

            let hoy = new Date().toISOString().split('T')[0];
            fechaInput.min = hoy ; 

            let horamin="16:00";
            let horamax="01:00";

            horaInput.min=horamin;
            horaInput.max=horamax;

        </script>









</x-dash>