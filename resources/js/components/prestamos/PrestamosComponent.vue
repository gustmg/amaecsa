<template>
    <v-container>
        <v-row align="center">
            <v-col>
                <div class="text-h6">
                    Préstamos<br />
                    ({{ prestamos.length }} registrados)
                </div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-prestamos-dialog-component></reporte-prestamos-dialog-component>
                <new-prestamo-dialog-component></new-prestamo-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchPrestamo"
                    label="Buscar prestamo por nombre o código"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
            <v-col>
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
                        <template v-slot:item.valor_prestamo="{ item }">
                            {{ parseFloat(getCostoUnitario(item.equipo.id_equipo) * item.cantidad).toFixed(2) }}
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
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReportePrestamosDialogComponent from './ReportePrestamosDialogComponent.vue'

    export default {
        components: {
            ReportePrestamosDialogComponent,
        },

        async mounted() {
            await this.fetchPrestamos()
            await this.fetchEquipos()
            await this.fetchPersonal()
            await this.fetchDestinos()
            await this.fetchEntradas()
            await this.fetchSalidas()
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
                    { text: 'Valor de préstamo', value: 'valor_prestamo' },
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
                    { id: 4, text: 'Consumibles' },
                ],

                prestamosStatusSeries: [],
                prestamosStatusOptions: {
                    chart: {
                        type: 'pie',
                        toolbar: {
                            show: true,
                        },
                    },
                    labels: ['Finalizados', 'Pendientes', 'Consumibles'],
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

            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),

            filteredPrestamos: function () {
                if (this.selectFiltro == 1) {
                    return this.prestamos
                } else if (this.selectFiltro == 2) {
                    return this.prestamos.filter((prestamo) => {
                        return prestamo.recibido == 1
                    })
                } else if (this.selectFiltro == 3) {
                    return this.prestamos.filter((prestamo) => {
                        return prestamo.recibido == 0
                    })
                } else {
                    return this.prestamos.filter((prestamo) => {
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
            ...mapActions('entrada', ['fetchEntradas']),
            ...mapActions('salida', ['fetchSalidas']),

            getFechaRecibido: function (prestamo) {
                if (prestamo.equipo.desechable) {
                    return 'N/A'
                } else if (prestamo.created_at == prestamo.updated_at) {
                    return 'Pendiente de recibir'
                } else {
                    return prestamo.updated_at
                }
            },

            download: function () {
                this.$refs.html2Pdf.generatePdf()
            },

            setPrestamosEstatusChartData: function () {
                var listaPrestamosFinalizados = this.prestamos.filter((prestamo) => {
                    return prestamo.recibido == 1
                })
                var listaPrestamosPendientes = this.prestamos.filter((prestamo) => {
                    return prestamo.recibido == 0
                })
                var listaPrestamosDesechables = this.prestamos.filter((prestamo) => {
                    return prestamo.recibido == null
                })
                this.prestamosStatusSeries.push(listaPrestamosFinalizados.length)
                this.prestamosStatusSeries.push(listaPrestamosPendientes.length)
                this.prestamosStatusSeries.push(listaPrestamosDesechables.length)
            },

            triggerUpdate: async function (id_prestamo) {
                await this.updatePrestamo(id_prestamo)
                await this.fetchPrestamos()
            },

            getValorPrestamo: function (prestamo) {
                return prestamo.cantidad * prestamo.equipo.valor_unitario
            },

            getCostoUnitario(id_equipo) {
                var entradasPorEquipo = []
                var salidasPorEquipo = []
                var totalCantidad = 0
                var totalCantidadSalidas = 0
                var totalCosto = 0

                this.entradas.forEach((entrada) => {
                    entrada.equipos.forEach((equipo) => {
                        if (equipo.id_equipo == id_equipo) {
                            entradasPorEquipo.push(equipo)
                        }
                    })
                })

                this.salidas.forEach((salida) => {
                    salida.equipos.forEach((equipo) => {
                        if (equipo.id_equipo == id_equipo) {
                            salidasPorEquipo.push(equipo)
                        }
                    })
                })

                entradasPorEquipo.forEach((equipo) => {
                    totalCantidad = equipo.pivot.cantidad + totalCantidad
                    totalCosto += equipo.pivot.costo_unitario * equipo.pivot.cantidad
                })

                salidasPorEquipo.forEach((equipo) => {
                    totalCantidadSalidas = equipo.pivot.cantidad + totalCantidadSalidas
                })

                return (totalCosto / totalCantidad).toFixed(2)
            },
        },
    }
</script>
