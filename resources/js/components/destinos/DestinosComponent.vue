<template>
    <v-container>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de destinos ({{ destinos.length }} registrados)</div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-destino-dialog-component></reporte-destino-dialog-component>
                <new-destino-dialog-component></new-destino-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchDestino"
                    label="Buscar destino por nombre"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                    v-on:input="setChartData"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-title>Préstamos realizados por destino</v-card-title>
                    <v-card-text>
                        <apexchart ref="apex" height="200" type="bar" :options="options" :series="series"></apexchart>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="destinoHeaders"
                        :items="destinos"
                        :search="searchDestino"
                        item-key="id_destino"
                    >
                        <template v-slot:item.prestamos_realizados="{ item }">
                            {{ getPrestamosRealizados(item.id_destino) }}
                        </template>
                        <template v-slot:item.prestamos_pendientes="{ item }">
                            {{ getPrestamosPendientes(item.id_destino) }}
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <edit-destino-dialog-component :destino="item"></edit-destino-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReporteDestinoDialogComponent from './ReporteDestinoDialogComponent.vue'

    export default {
        components: {
            ReporteDestinoDialogComponent
        },

        async mounted() {
            await this.fetchDestinos()
            await this.fetchPrestamos()
            this.setChartData()
        },

        data() {
            return {
                destinoHeaders: [
                    { text: 'Nombre', value: 'nombre_destino', width: '50%' },
                    { text: 'Préstamos realizados', value: 'prestamos_realizados', width: '15%', align: 'center' },
                    { text: 'Préstamos pendientes', value: 'prestamos_pendientes', width: '15%', align: 'center' },
                    { text: 'Opciones', value: 'opciones', width: '20%', align: 'center' },
                ],

                searchDestino: '',

                options: {
                    chart: {
                        id: 'vuechart-example',
                    },
                    xaxis: {
                        name: 'Personal',
                        categories: [],
                    },
                },
                series: [
                    {
                        name: 'series-1',
                        data: [],
                    },
                ],
            }
        },

        computed: {
            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            filteredDestinos: function() {
                return this.destinos.filter(destino => {
                    return destino.nombre_destino.toLowerCase().indexOf(this.searchDestino.toLowerCase()) >= 0
                })
            },
        },

        methods: {
            ...mapActions('destino', ['fetchDestinos']),
            ...mapActions('prestamo', ['fetchPrestamos']),

            getPrestamosRealizados: function(id_destino) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_destino == id_destino
                }).length
            },

            getPrestamosPendientes: function(id_destino) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_destino == id_destino && prestamo.recibido == 0
                }).length
            },

            setChartData: function() {
                var listaNombres = []
                var listaPrestamos = []
                var listaPrestamosPendientes = []

                if (this.searchDestino.length == 0) {
                    this.destinos.forEach(destino => {
                        listaNombres.push(destino.nombre_destino)
                    })

                    this.destinos.forEach(destino => {
                        listaPrestamos.push(this.getPrestamosRealizados(destino.id_destino))
                    })

                    this.destinos.forEach(destino => {
                        listaPrestamosPendientes.push(this.getPrestamosPendientes(destino.id_destino))
                    })
                } else {
                    this.filteredDestinos.forEach(destino => {
                        listaNombres.push(destino.nombre_destino)
                    })

                    this.filteredDestinos.forEach(destino => {
                        listaPrestamos.push(this.getPrestamosRealizados(destino.id_destino))
                    })

                    this.filteredDestinos.forEach(destino => {
                        listaPrestamosPendientes.push(this.getPrestamosPendientes(destino.id_destino))
                    })
                }

                this.options = {
                    ...this.options,
                    ...{
                        xaxis: {
                            categories: listaNombres,
                        },
                    },
                }

                this.$refs.apex.updateSeries([
                    { name: 'Prestamos', data: listaPrestamos },
                    { name: 'Préstamos pendientes', data: listaPrestamosPendientes },
                ])
            },

            download: function() {
                this.$refs.html2Pdf.generatePdf()
            },
        },
    }
</script>
