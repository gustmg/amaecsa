<template>
    <v-container fluid>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de equipo</div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-equipos-dialog-component></reporte-equipos-dialog-component>
                <new-equipo-dialog-component></new-equipo-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchEquipo"
                    label="Buscar equipo por nombre, código o categoría"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                    v-on:input="setChartData"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="8">
                <v-card>
                    <v-card-title>Stock por equipo</v-card-title>
                    <v-card-text>
                        <apexchart ref="apex" height="200" type="bar" :options="options" :series="series"></apexchart>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4">
                <v-card align="center" height="100%">
                    <v-card-title class="justify-center">Valor actual de inventario</v-card-title>
                    <v-card-text>
                        <div class="text-h5 font-weight-black">$ {{ activos }}</div>
                        <v-alert type="info" class="my-8"
                            >El valor actual de inventario es calculado por medio de la suma de todas las entradas de
                            inventario menos el 10% del total.</v-alert
                        >
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-data-table :headers="equipoHeaders" :items="filteredEquipos" item-key="id_equipo">
                        <template v-slot:item.stock_equipo="{ item }">
                            <div align="center">
                                <div>{{ item.stock_equipo }}</div>
                                <div v-if="!item.desechable" class="text-caption">
                                    ({{ getCantidadPrestados(item.id_equipo) }} prestados)
                                </div>
                            </div>
                        </template>
                        <template v-slot:item.costo_unitario="{ item }">
                            <div class="text-caption">{{ getCostoUnitario(item.id_equipo) }}</div>
                        </template>
                        <template v-slot:item.valor_total="{ item }">
                            <div class="text-caption">{{ getCostoTotalEquipo(item) }}</div>
                        </template>
                        <template v-slot:item.desechable="{ item }">
                            <div v-if="item.desechable">Consumible</div>
                            <div v-else>Normal</div>
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <edit-equipo-dialog-component :equipo="item"></edit-equipo-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReporteEquiposDialogComponent from './ReporteEquiposDialogComponent.vue'

    export default {
        components:{
            ReporteEquiposDialogComponent
        },

        async mounted() {
            await this.fetchEquipos()
            await this.fetchMarcas()
            await this.fetchTipoEquipo()
            await this.fetchCategorias()
            await this.fetchUnidadesMedida()
            await this.fetchPrestamos()
            await this.fetchEntradas()
            await this.fetchSalidas()
            this.setChartData()
        },

        data() {
            return {
                equipoHeaders: [
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Código de producto', value: 'codigo_producto_equipo' },
                    { text: 'Nombre', value: 'nombre_equipo' },
                    { text: 'Categoría', value: 'categoria.nombre_categoria' },
                    { text: 'Marca', value: 'marca.nombre_marca' },
                    { text: 'Stock', value: 'stock_equipo', align: 'center' },
                    { text: 'Costo unitario ($)', value: 'costo_unitario', align: 'center' },
                    { text: 'Valor total ($)', value: 'valor_total', align: 'center' },
                    { text: 'Unidad de medida', value: 'unidad_medida.nombre_unidad_medida' },
                    { text: 'Opciones', value: 'opciones' },
                ],

                searchEquipo: '',

                options: {
                    chart: {
                        id: 'vuechart-example',
                    },
                    xaxis: {
                        name: 'Equipo',
                        categories: [],
                    },
                },
                series: [
                    {
                        name: 'Stock',
                        data: [],
                    },
                ],
            }
        },

        computed: {
            ...mapGetters('equipo', {
                equipos: 'getEquipos',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),

            activos: function () {
                var activos = 0
                // this.entradas.forEach((entrada) => {
                //     activos = activos + +this.getCostoTotal(entrada)
                // })

                // this.salidas.forEach((salida) => {
                //     salida.equipos.forEach((equipo) => {
                //         activos = activos - this.getCostoUnitario(equipo.id_equipo) * equipo.pivot.cantidad
                //     })
                // })
                this.equipos.forEach(equipo=>{
                    activos+= +this.getCostoTotalEquipo(equipo)
                })

                return activos.toFixed(2)
            },

            filteredEquipos: function () {
                return this.equipos.filter((equipo) => {
                    return (
                        equipo.nombre_equipo.toLowerCase().indexOf(this.searchEquipo.toLowerCase()) >= 0 ||
                        equipo.codigo_barras_equipo.toLowerCase().indexOf(this.searchEquipo.toLowerCase()) >= 0 ||
                        equipo.marca.nombre_marca.toLowerCase().indexOf(this.searchEquipo.toLowerCase()) >= 0 ||
                        equipo.categoria.nombre_categoria.toLowerCase().indexOf(this.searchEquipo.toLowerCase()) >= 0 ||
                        equipo.categoria.codigo_categoria.toLowerCase().indexOf(this.searchEquipo.toLowerCase()) >= 0
                    )
                })
            },
        },

        methods: {
            ...mapActions('equipo', ['fetchEquipos']),

            ...mapActions('marca', ['fetchMarcas']),

            ...mapActions('categoria', ['fetchCategorias']),

            ...mapActions('unidad_medida', ['fetchUnidadesMedida']),

            ...mapActions('prestamo', ['fetchPrestamos']),

            ...mapActions('entrada', ['fetchEntradas']),

            ...mapActions('salida', ['fetchSalidas']),

            ...mapActions('tipo_equipo', ['fetchTipoEquipo']),

            getCantidadPrestados: function (id_equipo) {
                var prestados = 0
                this.prestamos.forEach((prestamo) => {
                    if (prestamo.id_equipo == id_equipo && !prestamo.recibido) {
                        prestados++
                    }
                })

                return prestados
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

            setChartData: function () {
                var listaNombres = []
                var stocks = []

                this.filteredEquipos.forEach((equipo) => {
                    listaNombres.push(equipo.nombre_equipo)
                })

                this.filteredEquipos.forEach((equipo) => {
                    stocks.push(equipo.stock_equipo)
                })

                this.options = {
                    ...this.options,
                    ...{
                        xaxis: {
                            categories: listaNombres,
                        },
                    },
                }

                this.$refs.apex.updateSeries([{ name: 'Stock', data: stocks }])
            },

            download: function () {
                this.$refs.html2Pdf.generatePdf()
            },

            getCostoTotal: function (entrada) {
                var total = 0
                entrada.equipos.forEach((equipo) => {
                    total = total + +(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            getCostoTotalEquipo: function (equipo) {
                return parseFloat(this.getCostoUnitario(equipo.id_equipo) * equipo.stock_equipo).toFixed(2)
            },
        },
    }
</script>
