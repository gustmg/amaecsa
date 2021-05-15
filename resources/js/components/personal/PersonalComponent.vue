<template>
    <v-container>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de personal ({{ personal.length }} registrados)</div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-personal-dialog-component></reporte-personal-dialog-component>
                <new-personal-dialog-component></new-personal-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchPersonal"
                    label="Buscar personal por nombre o código"
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
                    <v-card-title>Préstamos realizados por personal</v-card-title>
                    <v-card-text>
                        <apexchart ref="apex" height="200" type="bar" :options="options" :series="series"></apexchart>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="personalHeaders"
                        :items="personal"
                        :search="searchPersonal"
                        item-key="id_personal"
                    >
                        <template v-slot:item.prestamos_realizados="{ item }">
                            {{ getPrestamosRealizados(item.id_personal) }}
                        </template>
                        <template v-slot:item.prestamos_pendientes="{ item }">
                            {{ getPrestamosPendientes(item.id_personal) }}
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <edit-personal-dialog-component :personal="item"></edit-personal-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReportePersonalDialogComponent from './ReportePersonalDialogComponent.vue'

    export default {
        components: {
            ReportePersonalDialogComponent
        },

        async mounted() {
            await this.fetchPersonal()
            await this.fetchPrestamos()
            this.setChartData()
        },

        data() {
            return {
                personalHeaders: [
                    { text: 'Código de personal', value: 'codigo_personal', width: '20%', align: 'center' },
                    { text: 'Nombre', value: 'nombre_personal', width: '30%' },
                    { text: 'Préstamos realizados', value: 'prestamos_realizados', width: '15%', align: 'center' },
                    { text: 'Préstamos pendientes', value: 'prestamos_pendientes', width: '15%', align: 'center' },
                    { text: 'Opciones', value: 'opciones', width: '20%', align: 'center', sortable: false },
                ],

                searchPersonal: '',

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
            ...mapGetters('personal', {
                personal: 'getPersonal',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            filteredPersonal: function() {
                return this.personal.filter(persona => {
                    return (
                        persona.nombre_personal.toLowerCase().indexOf(this.searchPersonal.toLowerCase()) >= 0 ||
                        persona.codigo_personal.toLowerCase().indexOf(this.searchPersonal.toLowerCase()) >= 0
                    )
                })
            },
        },

        methods: {
            ...mapActions('personal', ['fetchPersonal']),
            ...mapActions('prestamo', ['fetchPrestamos']),

            getPrestamosRealizados: function(id_personal) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_personal == id_personal
                }).length
            },

            getPrestamosPendientes: function(id_personal) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_personal == id_personal && prestamo.recibido == 0
                }).length
            },

            setChartData: function() {
                var listaNombres = []
                var listaPrestamos = []
                var listaPrestamosPendientes = []

                this.filteredPersonal.forEach(personal => {
                    listaNombres.push(personal.nombre_personal)
                })

                this.filteredPersonal.forEach(personal => {
                    listaPrestamos.push(this.getPrestamosRealizados(personal.id_personal))
                })

                this.filteredPersonal.forEach(personal => {
                    listaPrestamosPendientes.push(this.getPrestamosPendientes(personal.id_personal))
                })

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
