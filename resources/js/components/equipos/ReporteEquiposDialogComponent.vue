<template>
    <v-dialog v-model="active" width="95%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de equipos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <vue-excel-editor v-model="equipoReporte" ref="report" filter-row readonly>
                                <vue-excel-column
                                    width="64px"
                                    field="codigoBarras"
                                    label="Código de barras"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="80px"
                                    field="codigoProducto"
                                    label="Código de producto"
                                ></vue-excel-column>
                                <vue-excel-column width="160px" field="nombreEquipo" label="Equipo"></vue-excel-column>
                                <vue-excel-column width="80px" field="categoria" label="Categoría"></vue-excel-column>
                                <vue-excel-column width="80px" field="tipo" label="Tipo"></vue-excel-column>
                                <vue-excel-column width="160px" field="marca" label="Marca"></vue-excel-column>
                                <vue-excel-column width="80px" field="stock" label="Stock"></vue-excel-column>
                                <vue-excel-column
                                    width="160px"
                                    field="unidadMedida"
                                    label="Unidad de medida"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="80px"
                                    field="costoUnitario"
                                    label="Costo Unitario"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="160px"
                                    field="valorTotal"
                                    label="Valor total"
                                ></vue-excel-column>
                            </vue-excel-editor>
                        </v-col>
                    </v-row>
                    <v-row justify="center">
                        <v-col align="center">
                            <v-btn @click="descargaReporte()" class="mt-4" color="accent">Descargar</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                active: false,

                searchFechaMenu: false,
                date: [],
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

            equipoReporte: function () {
                var equipoReporte = []

                if (this.equipos.length > 0) {
                    this.equipos.forEach((equipo) => {
                        equipoReporte.push({
                            codigoBarras: equipo.codigo_barras_equipo,
                            codigoProducto: equipo.codigo_producto_equipo,
                            nombreEquipo: equipo.nombre_equipo,
                            categoria: equipo.categoria.nombre_categoria,
                            tipo: equipo.tipo_equipo.nombre_tipo_equipo,
                            marca: equipo.marca.nombre_marca,
                            stock: equipo.stock_equipo,
                            unidadMedida: equipo.unidad_medida.nombre_unidad_medida,
                            stockPrestados: '',
                            costoUnitario: this.getCostoUnitario(equipo.id_equipo),
                            valorTotal: this.getCostoTotalEquipo(equipo),
                        })
                    })
                }

                return equipoReporte
            },
        },

        methods: {
            descargaReporte: function () {
                const format = 'xlsx'
                const exportSelectedOnly = false
                const filename = 'reporte_equipos'
                this.$refs.report.exportTable(format, exportSelectedOnly, filename)
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

            getCostoTotalEquipo: function (equipo) {
                return parseFloat(this.getCostoUnitario(equipo.id_equipo) * equipo.stock_equipo).toFixed(2)
            },

        },
    }
</script>
