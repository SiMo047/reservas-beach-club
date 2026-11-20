<x-dash>

<x-tabla>
                    <x-slot name="cabecera">
                    
                    <x-tabla.th>Nombre</x-tabla.th>
                    <x-tabla.th>Contacto</x-tabla.th>
                    <x-tabla.th>Nº Personas</x-tabla.th>
                    <x-tabla.th>Fecha</x-tabla.th>
                    <x-tabla.th>Hora</x-tabla.th>
                    <x-tabla.th>Zona</x-tabla.th>
                    <x-tabla.th>...</x-tabla.th>
                    
                    </x-slot>

                    <x-slot name="tbody">

                    @foreach($reservas as $reserva)
                        <tr>
                        <x-tabla.td>{{ $reserva->nombre }}</x-tabla.td>
                        <x-tabla.td>{{ $reserva->n_telefono }}</x-tabla.td>
                        <x-tabla.td>{{ $reserva->n_personas }}</x-tabla.td>
                        <x-tabla.td>{{ $reserva->fecha }}</x-tabla.td>
                        <x-tabla.td>{{ $reserva->hora }}</x-tabla.td>
                        <x-tabla.td>{{ $reserva->zona }}</x-tabla.td>
                        <x-tabla.td>   
                       <a href="{{ route('modreserva',['id'=>$reserva->id]) }}"><i class="zmdi zmdi-edit"></i></a>
                       <a href="#" data-borrar="{{ route('delreserva',['id'=>$reserva->id]) }}" data-nombre="{{ $reserva->nombre }}"><i class="zmdi zmdi-delete"></i></a>
                   </x-tabla.td>
                        </tr>
                    @endforeach    

                    <x-slot name="tlink">
                        {{$reservas->links()}}
                    </x-slot>
                    </x-slot>
</x-tabla>

        <!-- Modal confirmar borrado -->
        <div id="popup-borrar" tabindex="-1" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center">
                <div class="relative bg-white rounded-lg shadow-lg p-6">
                    <!-- Contenido del modal -->
                    <div class="p-4 md:p-5 text-center">
                        <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x text-green-500 mb-4"></i>
                        <h3 class="mb-5 text-lg font-normal text-black">¿Seguro que quieres borrar la reserva de <span id="borrar-nombre"></span>? <br>Esta acción no se puede deshacer</h3>
                        <form id="form-borrar" method="POST" action="" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Borrar
                            </button>
                        </form>
                        <!-- Botón para cerrar el modal -->
                        <button type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-100" data-modal-hide="popup-borrar">Cancelar</button>
                    </div>
                </div>
        </div>

        <!--SCRIPT para abrir el modal de borrado con la reserva elegida -->
        <script>
            document.addEventListener('click', function(event) {
                var enlace = event.target.closest('[data-borrar]');
                var modal = document.getElementById('popup-borrar');

                if (enlace) {
                    event.preventDefault();
                    document.getElementById('form-borrar').action = enlace.dataset.borrar;
                    document.getElementById('borrar-nombre').textContent = enlace.dataset.nombre;
                    modal.classList.remove('hidden');
                }

                // cerrar al pulsar Cancelar o fuera del recuadro
                if (event.target.matches('[data-modal-hide="popup-borrar"]') || event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        </script>

            













</x-dash>