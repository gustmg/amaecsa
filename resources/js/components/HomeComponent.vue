<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <div class="text-h6 font-weight-bold">Movimientos realizados el día de hoy</div>
            </v-col>

            <v-col cols="4">
                <v-card>
                    <v-card-title class="d-block" align="center">
                        <div class="text-h5 font-weight-black">{{ filteredPrestamosHoy.length }}</div>
                        <div class="text-subtitle-1">Préstamos realizados</div>
                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="4">
                <v-card>
                    <v-card-title class="d-block" align="center">
                        <div class="text-h5 font-weight-black">{{ filteredEntradasHoy.length }}</div>
                        <div class="text-subtitle-1">Entradas de inventario</div>
                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="4">
                <v-card>
                    <v-card-title class="d-block" align="center">
                        <div class="text-h5 font-weight-black">{{ filteredSalidasHoy.length }}</div>
                        <div class="text-subtitle-1">Salidas de inventario</div>
                    </v-card-title>
                </v-card>
            </v-col>

            <v-col cols="12">
                <v-card>
                    <apexchart
                        ref="apex"
                        height="420"
                        type="bar"
                        :options="todayOptions"
                        :series="todaySeries"
                    ></apexchart>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-btn color="accent" v-on:click="createBackup()">Respaldar base de datos</v-btn>
            </v-col>
        </v-row>
        <v-snackbar v-model="snackbar">Respaldo finalizado.</v-snackbar>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import moment from 'moment'

    export default {
        async mounted() {
            await this.fetchPrestamos()
            await this.fetchEntradas()
            await this.fetchSalidas()
            this.$refs.apex.updateSeries([
                { name: 'Prestamos', data: this.setPrestamos() },
                { name: 'Entradas', data: this.setEntradas() },
                { name: 'Salidas', data: this.setSalidas() },
            ])
        },

        data() {
            return {
                todayOptions: {
                    xaxis: {
                        name: 'Equipo',
                        categories: ['9 am', '10 am', '11 am', '12 pm', '1 pm', '2 pm', '3 pm', '4 pm', '5 pm', '6 pm'],
                    },
                },

                todaySeries: [],
                snackbar: false,
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

            filteredPrestamosHoy: function() {
                return this.prestamos.filter(prestamo => {
                    return moment(prestamo.created_at).isSame(moment(), 'day')
                })
            },

            filteredEntradasHoy: function() {
                return this.entradas.filter(entrada => {
                    return moment(entrada.created_at).isSame(moment(), 'day')
                })
            },

            filteredSalidasHoy: function() {
                return this.salidas.filter(salida => {
                    return moment(salida.created_at).isSame(moment(), 'day')
                })
            },
        },

        methods: {
            ...mapActions('prestamo', ['fetchPrestamos']),
            ...mapActions('entrada', ['fetchEntradas']),
            ...mapActions('salida', ['fetchSalidas']),

            setPrestamos: function() {
                var prestamosSeries = []
                for (var x = 9; x < 18; x++) {
                    var prestamosPorHora = this.prestamos.filter(prestamo => {
                        return moment(prestamo.created_at).hours() == x
                    })

                    prestamosSeries.push(prestamosPorHora.length)
                }
                return prestamosSeries
            },

            setEntradas: function() {
                var entradasSeries = []
                for (var x = 9; x < 18; x++) {
                    var entradasPorHora = this.entradas.filter(entrada => {
                        return moment(entrada.created_at).hours() == x
                    })

                    entradasSeries.push(entradasPorHora.length)
                }
                return entradasSeries
            },

            setSalidas: function() {
                var salidasSeries = []
                for (var x = 9; x < 18; x++) {
                    var salidasPorHora = this.salidas.filter(salida => {
                        return moment(salida.created_at).hours() == x
                    })

                    salidasSeries.push(salidasPorHora.length)
                }
                return salidasSeries
            },

            createBackup: function() {
                axios
                .post('createBackup')
                .then(response=>{
                    if(response.data==1){
                        this.snackbar=true
                    }
                })
                .catch(error=>console.log(error))
            }
        },
    }
</script>
