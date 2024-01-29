<div
    class="py-12"
    x-data="{

    showEventsModal: false,
    createModal: false,
    updateModal: false,
    destroyModal: false,

    titleModal: null,
    Day: null,

    event: {
        title: null,
        start: null,
        end: null,
        description: null,
    },

    create() {
        this.event = {
            id: null,
            title: null, //.slice(0,16)
            start: this.Day.toISOString().slice(0, 16),
            end: this.Day.toISOString().slice(0, 16),
            description: null,
        };

        this.closeShowEventsModal();
        this.openCreateModal();
    },

    editByEventClick(eventData){

        const formatDateTime = (date) => {
            const pad = (n) => (n < 10 ? `0${n}` : n);
            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1);
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };

        // Convertir las fechas a objetos Date
        const startDate = new Date(eventData.start);
        const endDate = new Date(eventData.end);

        // Formatear las fechas en el formato deseado
        const formattedStart = formatDateTime(startDate);
        const formattedEnd = formatDateTime(endDate);

        this.event = {
            id: eventData.id,
            title: eventData.title,
            start: formattedStart,
            end: formattedEnd,
            description: eventData.description,
        };

        this.openUpdateModal();
    },

    editByEventTable(eventData) {
        this.event = {
            id: eventData.id,
            title: eventData.title,
            start: eventData.start,
            end: eventData.end,
            description: eventData.description,
        };
        this.openUpdateModal();
    },

    store() {

        $wire.store(this.event, this.details, this.payments, this.customer).then(() => {

            this.event = {
                title: null,
                start: null,
                end: null,
                description: null,
            };

            this.closeCreateModal();

        })

    },

    update(eventData){
        $wire.update(this.event).then(() => {

            this.event = {
                title: null,
                start: null,
                end: null,
                description: null,
            };

            this.closeCreateModal();

        })

    },

    findToDestroy(eventData) {

        this.event = {
            id: eventData.id,
        };

        this.openShowEventsModal();
        this.openDestroyModal();

    },

    destroy(eventData){
        $wire.destroy(this.event).then(() => {

            this.event = {
                title: null,
                start: null,
                end: null,
                description: null,
            };

            this.closeCreateModal();

        })
    },


    //OPEN MODALS FUNCTIONS

    openShowEventsModal() {
        this.showEventsModal = true;
    },
    openCreateModal() {
        this.createModal = true;
    },
    openUpdateModal() {
        this.updateModal = true;
    },
    openDestroyModal() {
        this.destroyModal = true;
    },

    //CLOSE MODALS FUNCTIONS
    closeShowEventsModal() {
        this.showEventsModal = false;
    },
    closeCreateModal() {
        this.createModal = false;
        this.openShowEventsModal();
    },
    closeUpdateModal() {
        this.updateModal = false;
    },
    closeDestroyModal() {
        this.destroyModal = false;
    },

}"
    x-init="document.addEventListener('DOMContentLoaded', function() {

const calendarEl = document.getElementById('calendar');

const all_events = JSON.parse($wire.get('all_events'));

const events = [];

all_events.forEach(event => {
        events.push({

            id: event.id,
            title: event.title,
            start: event.start,
            end: event.end,

            extendedProps: {
                description: event.description,
            }

        })

    })

const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: events,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listWeek'
        //right: 'dayGridMonth,listWeek'
    },

    eventColor: '#1E0DDB',

    eventClick: function(info) {

        const eventData = {
            id: info.event.id,
            title: info.event.title,
            start: info.event.start,
            end: info.event.end,
            description: info.event.extendedProps.description,
        };

        editByEventClick(eventData);

    },

    dateClick: function(info) {

        const selectedDay = info.date;
        Livewire.emit('show', selectedDay);

        const formattedDay = selectedDay.toLocaleDateString('es-ES', {
            year: 'numeric',
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });

        titleModal = formattedDay;
        Day = selectedDay;

        openShowEventsModal();

    },

    eventClick: function(info) {

        const eventData = {

            id: info.event.id,
            title: info.event.title,
            start: info.event.start,
            end: info.event.end,
            description: info.event.extendedProps.description,

        };

        editByEventClick(eventData);

    },

});

calendar.render();

Livewire.on('closeModal', function() {
        calendar.destroy();
        calendar.refetchEvents();
        Livewire.emit('refreshLivewireStyles');
        location.reload();
        calendar.render();
    });



});"
>
    <!-- SHOW EVENTS-->
    <div
        style="display: none"
        class="fixed z-10 inset-0 overflow-y-auto ease-out duration-1000"
        x-show="showEventsModal"
        x-cloak
        x-transition:enter="opacity-0"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-click.away="closeShowEventsModal()"
    >
        <div
            class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
            ></span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full md:w-3/5 lg:w-3/5 xl:w-3/5"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
            >
                <div
                    class="bg-gradient-to-br from-gray-800 to-blue-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-lg text-white"
                >
                    <h3
                        class="text-2xl font-semibold text-white mb-4"
                        x-text="titleModal"
                    ></h3>

                    <br />

                    <div class="mb-4 px-10">
                        <main class="mb-4 px-10">
                            <br />

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y">
                                    <thead>
                                        <tr
                                            class="bg-gradient-to-br from-gray-700 to-gray-800 font-bold"
                                        >
                                            <th
                                                class="px-2 py-1 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider"
                                            >
                                                Titulo
                                            </th>
                                            <th
                                                class="px-2 py-1 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider"
                                            >
                                                Inicio
                                            </th>
                                            <th
                                                class="px-2 py-1 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider"
                                            >
                                                Cierre
                                            </th>

                                            <th
                                                class="px-2 py-1 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider"
                                            >
                                                Configurar
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($EventsDay as $event)
                                        <tr
                                            class="odd:bg-white even:bg-gray-100"
                                        >
                                            <td
                                                class="px-2 py-1 whitespace-no-wrap border-b text-black"
                                            >
                                                {{ $event->title }}
                                            </td>
                                            <td
                                                class="px-2 py-1 whitespace-no-wrap border-b text-black"
                                            >
                                                {{ $event->start }}
                                            </td>
                                            <td
                                                class="px-2 py-1 whitespace-no-wrap border-b text-black"
                                            >
                                                {{ $event->end }}
                                            </td>

                                            <td
                                                class="px-2 py-1 whitespace-no-wrap border-b"
                                            >
                                                <button
                                                    x-on:click="editByEventTable( {{
                                                        json_encode($event)
                                                    }})"
                                                    class="bi bi-pencil-square text-black font-bold py-1 px-1"
                                                ></button>

                                                <button
                                                    x-on:click="findToDestroy( {{
                                                        json_encode($event)
                                                    }})"
                                                    class="bi bi-trash3-fill text-black font-bold py-1 px-1"
                                                ></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <br />
                        </main>

                        <!-- Botones y acciones del modal -->
                        <footer
                            class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                        >
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    x-on:click="create()"
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-300 text-base leading-6 font-medium text-black shadow-sm hover:bg-white focus:outline-none focus:border-blue-900 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                >
                                    Nuevo evento
                                </button>
                            </span>

                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:text-white hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                    x-on:click="closeShowEventsModal()"
                                >
                                    Cancelar
                                </button>
                            </span>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--CREATE EVENT MODAL-->
    <div
        style="display: none"
        class="fixed z-10 inset-0 overflow-y-auto ease-out duration-1000"
        x-show="createModal"
        x-cloak
        x-transition:enter="opacity-0"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-click.away="closeCreateModal()"
    >
        <div
            class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
            ></span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full md:w-3/5 lg:w-3/5 xl:w-3/5"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
            >
                <div
                    class="bg-gradient-to-br from-gray-800 to-blue-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-lg text-white"
                >
                    <h3 class="text-2xl font-semibold text-white mb-4">
                        Nuevo evento
                    </h3>

                    <br />

                    <div class="mb-4 px-10">
                        <main class="mb-4 px-10">
                            <br />

                            <div class="mb-4 mt-4">
                                <label
                                    for="text"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Titulo:</label
                                >
                                <input
                                    type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.title"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="text"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Descripcion:</label
                                >
                                <input
                                    type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.description"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="start"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Fecha de Inicio:</label
                                >
                                {{--
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.start"
                                />
                                --}}
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.start"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="end"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Fecha de Cierre:</label
                                >
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.end"
                                    style="font-size: 1em"
                                />
                            </div>

                            <br />
                        </main>

                        <!-- Botones y acciones del modal -->
                        <footer
                            class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                        >
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    x-on:click="store()"
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-300 text-base leading-6 font-medium text-black shadow-sm hover:bg-white focus:outline-none focus:border-blue-900 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                >
                                    Guardar
                                </button>
                            </span>

                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:text-white hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                    x-on:click="closeCreateModal()"
                                >
                                    Cancelar
                                </button>
                            </span>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--UPDATE EVENT-->
    <div
        style="display: none"
        class="fixed z-10 inset-0 overflow-y-auto ease-out duration-1000"
        x-show="updateModal"
        x-cloak
        x-transition:enter="opacity-0"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-click.away="closeUpdateModal()"
    >
        <div
            class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
            ></span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full md:w-3/5 lg:w-3/5 xl:w-3/5"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
            >
                <div
                    class="bg-gradient-to-br from-gray-800 to-blue-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-lg text-white"
                >
                    <h3 class="text-2xl font-semibold text-white mb-4">
                        Actualizar evento
                    </h3>

                    <br />

                    <div class="mb-4 px-10">
                        <main class="mb-4 px-10">
                            <br />

                            <div class="mb-4 mt-4">
                                <label
                                    for="text"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Titulo:</label
                                >
                                <input
                                    type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.title"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="text"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Descripcion:</label
                                >
                                <input
                                    type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.description"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="start"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Fecha de Inicio:</label
                                >
                                {{--
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.start"
                                />
                                --}}
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.start"
                                    style="font-size: 1em"
                                />
                            </div>

                            <div class="mb-4 mt-4">
                                <label
                                    for="end"
                                    class="block text-white text-xs font-bold mb-1"
                                    >Fecha de Cierre:</label
                                >
                                <input
                                    type="datetime-local"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-xs text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="start"
                                    x-model="event.end"
                                    style="font-size: 1em"
                                />
                            </div>

                            <br />
                        </main>

                        <!-- Botones y acciones del modal -->
                        <footer
                            class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                        >
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    x-on:click="update()"
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-300 text-base leading-6 font-medium text-black shadow-sm hover:bg-white focus:outline-none focus:border-blue-900 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                >
                                    Actualizar
                                </button>
                            </span>

                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    x-on:click="openDestroyModal()"
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-red-900 px-4 py-2 bg-orange-900 text-base leading-6 font-medium text-white shadow-sm hover:text-white hover:bg-orange-600 focus:outline-none focus:border-red-600 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                >
                                    Eliminar
                                </button>
                            </span>

                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:text-white hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                    x-on:click="closeUpdateModal()"
                                >
                                    Cancelar
                                </button>
                            </span>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--DESTROY MODAL-->
    <div
        style="display: none"
        class="fixed z-10 inset-0 overflow-y-auto ease-out duration-1000"
        x-show="destroyModal"
        x-cloak
        x-transition:enter="opacity-0"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-click.away="destroyModal = false"
    >
        <div
            class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
            ></span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full md:w-3/5 lg:w-3/5 xl:w-3/5"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
            >
                <div
                    class="bg-gradient-to-br from-gray-800 to-blue-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-lg text-black"
                >
                    <h3 class="text-2xl font-semibold text-white mb-4">
                        Eliminar Evento
                    </h3>

                    <br />

                    <div class="mb-4 px-10">
                        <main class="mb-4 px-10">
                            <br />
                            <label
                                class="text-2xl font-semibold text-white mb-4"
                                >Seguro de eliminar este evento?</label
                            >
                            <br />
                        </main>

                        <!-- Botones y acciones del modal -->
                        <footer
                            class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                        >
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    x-on:click="destroy()"
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-800 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                >
                                    Eliminar
                                </button>
                            </span>

                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:text-white hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                    x-on:click="closeDestroyModal()"
                                >
                                    Cancelar
                                </button>
                            </span>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            <br />
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div id="calendar" wire:ignore></div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </div>
        <br />
    </div>
</div>

@stack('modals') @yield('scripts')
