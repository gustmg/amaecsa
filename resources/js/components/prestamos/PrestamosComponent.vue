<template>
    <v-container>
        <v-row align="center">
            <v-col cols="2">
                <div class="text-h6">
                    Préstamos<br />
                    ({{ prestamos.length }} registrados)
                </div>
            </v-col>
            <v-col cols="4" align="right">
                <v-btn v-on:click="download()" class="mx-2" color="accent">Descargar tabla</v-btn>
                <new-prestamo-dialog-component></new-prestamo-dialog-component>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="searchPrestamo"
                    label="Buscar prestamo por nombre o código"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
            <v-col cols="2">
                <v-select
                    label="Mostrar por"
                    :items="tipoFiltro"
                    v-model="selectFiltro"
                    item-value="id"
                    item-text="text"
                    solo
                    class="mt-6"
                >
                </v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="4" offset="4">
                <v-card>
                    <apexchart
                        ref="apex"
                        type="pie"
                        width="80%"
                        :options="prestamosStatusOptions"
                        :series="prestamosStatusSeries"
                    ></apexchart>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        id="table"
                        :headers="prestamoHeaders"
                        :items="filteredPrestamos"
                        :search="searchPrestamo"
                        item-key="id_prestamo"
                    >
                        <template v-slot:item.updated_at="{ item }">
                            {{ getFechaRecibido(item) }}
                        </template>
                        <template v-slot:item.recibido="{ item }">
                            <v-icon v-if="item.recibido" color="success">mdi-check</v-icon>
                            <v-icon v-else-if="item.recibido == 0" color="error">mdi-minus</v-icon>
                            <div v-else class="text-overline">N/A</div>
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <v-btn
                                color="accent"
                                v-if="item.recibido == 0"
                                small
                                v-on:click="triggerUpdate(item.id_prestamo)"
                                >Recibir</v-btn
                            >
                        </template>
                    </v-data-table>
                    <vue-html2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="false"
                        :paginate-elements-by-height="1400"
                        filename="personal"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="800px"
                        ref="html2Pdf"
                    >
                        <section slot="pdf-content">
                            <v-data-table
                                :headers="prestamoHeaders"
                                :items="prestamos"
                                :search="searchPrestamo"
                                item-key="id_prestamo"
                                disable-pagination
                                hide-default-footer
                            >
                                <template v-slot:item.updated_at="{ item }">
                                    {{ getFechaRecibido(item) }}
                                </template>
                                <template v-slot:item.recibido="{ item }">
                                    <v-icon v-if="item.recibido" color="success">mdi-check</v-icon>
                                    <v-icon v-else-if="item.recibido == 0" color="error">mdi-minus</v-icon>
                                    <div v-else class="text-overline">N/A</div>
                                </template>
                            </v-data-table>
                        </section>
                    </vue-html2pdf>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import VueHtml2pdf from 'vue-html2pdf'

    export default {
        async mounted() {
            await this.fetchPrestamos()
            await this.fetchEquipos()
            await this.fetchPersonal()
            await this.fetchDestinos()
            this.setPrestamosEstatusChartData()
        },

        data() {
            return {
                prestamoHeaders: [
                    { text: 'Folio', value: 'id_prestamo' },
                    { text: 'Fecha de entrega', value: 'created_at' },
                    { text: 'Fecha de recepción', value: 'updated_at' },
                    { text: 'Personal', value: 'personal.nombre_personal' },
                    { text: 'Equipo', value: 'equipo.nombre_equipo' },
                    { text: 'Cantidad', value: 'cantidad' },
                    { text: 'Destino', value: 'destino.nombre_destino' },
                    { text: 'Recibido', value: 'recibido', sortable: false },
                    { text: 'Opciones', value: 'opciones', sortable: false },
                ],

                searchPrestamo: '',
                selectFiltro: 1,
                tipoFiltro: [
                    { id: 1, text: 'Todos' },
                    { id: 2, text: 'Recibidos' },
                    { id: 3, text: 'Pendientes por recibir' },
                    { id: 4, text: 'Desechables' },
                ],

                prestamosStatusSeries: [],
                prestamosStatusOptions: {
                    chart: {
                        type: 'pie',
                        toolbar: {
                            show: true,
                        },
                    },
                    labels: ['Finalizados', 'Pendientes', 'Desechables'],
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                legend: {
                                    position: 'bottom',
                                },
                            },
                        },
                    ],
                },
            }
        },

        computed: {
            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            filteredPrestamos: function() {
                if (this.selectFiltro == 1) {
                    return this.prestamos
                } else if (this.selectFiltro == 2) {
                    return this.prestamos.filter(prestamo => {
                        return prestamo.recibido == 1
                    })
                } else if (this.selectFiltro == 3) {
                    return this.prestamos.filter(prestamo => {
                        return prestamo.recibido == 0
                    })
                } else {
                    return this.prestamos.filter(prestamo => {
                        return prestamo.recibido == null
                    })
                }
            },
        },

        methods: {
            ...mapActions('prestamo', ['fetchPrestamos', 'updatePrestamo']),
            ...mapActions('personal', ['fetchPersonal']),
            ...mapActions('equipo', ['fetchEquipos']),
            ...mapActions('destino', ['fetchDestinos']),

            getFechaRecibido: function(prestamo) {
                if (prestamo.equipo.desechable) {
                    return 'N/A'
                } else if (prestamo.created_at == prestamo.updated_at) {
                    return 'Pendiente de recibir'
                } else {
                    return prestamo.updated_at
                }
            },

            download: function() {
                this.$refs.html2Pdf.generatePdf()
            },

            setPrestamosEstatusChartData: function() {
                var listaPrestamosFinalizados = this.prestamos.filter(prestamo => {
                    return prestamo.recibido == 1
                })
                var listaPrestamosPendientes = this.prestamos.filter(prestamo => {
                    return prestamo.recibido == 0
                })
                var listaPrestamosDesechables = this.prestamos.filter(prestamo => {
                    return prestamo.recibido == null
                })
                this.prestamosStatusSeries.push(listaPrestamosFinalizados.length)
                this.prestamosStatusSeries.push(listaPrestamosPendientes.length)
                this.prestamosStatusSeries.push(listaPrestamosDesechables.length)
            },

            triggerUpdate: async function(id_prestamo) {
                await this.updatePrestamo(id_prestamo)
                await this.fetchPrestamos()
            },
        },
    }
</script>
