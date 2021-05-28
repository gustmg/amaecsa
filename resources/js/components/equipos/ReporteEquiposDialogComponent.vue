<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de equipos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <div class="text-subtitle-1">Reporte generado</div>
                            <xlsx-workbook>
                                <xlsx-sheet :collection="excelEquipos" key="Equipos" sheet-name="Equipos" />
                                <xlsx-download filename="Reporte de equipos.xlsx">
                                    <v-btn color="primary">Descargar reporte</v-btn>
                                </xlsx-download>
                            </xlsx-workbook>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapGetters } from 'vuex'
    import XlsxWorkbook from 'vue-xlsx/dist/components/XlsxWorkbook'
    import XlsxSheet from 'vue-xlsx/dist/components/XlsxSheet'
    import XlsxDownload from 'vue-xlsx/dist/components/XlsxDownload'

    export default {
        components: {
            XlsxWorkbook,
            XlsxSheet,
            XlsxDownload,
        },

        data() {
            return {
                active: false,

                searchFechaMenu: false,
                date: [],

                sheets: [{ name: 'Equipos' }],
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

            filteredEquipos() {},

            excelEquipos() {
                var excel = []

                this.equipos.forEach((equipo) => {
                    excel.push({
                        'Código de barras': equipo.codigo_barras_equipo,
                        'Código de producto': equipo.codigo_producto_equipo,
                        Nombre: equipo.nombre_equipo,
                        Categoría: equipo.categoria.nombre_categoria,
                        Tipo: equipo.tipo_equipo.nombre_tipo_equipo,
                        Marca: equipo.marca.nombre_marca,
                        Stock: equipo.stock_equipo,
                        'Unidad de medida': equipo.unidad_medida.nombre_unidad_medida,
                        'Costo unitario': this.getCostoUnitario(equipo.id_equipo),
                        'Valor total': this.getCostoTotalEquipo(equipo),
                    })
                })

                excel.push({
                    'Código de barras': '',
                    'Código de producto': '',
                    Nombre: '',
                    Categoría: '',
                    Tipo: '',
                    Marca: '',
                    Stock: '',
                    'Unidad de medida': '',
                    'Costo unitario': 'TOTAL',
                    'Valor total': this.activos,
                })

                return excel
            },

            activos: function () {
                var activos = 0

                this.equipos.forEach((equipo) => {
                    activos += +this.getCostoTotalEquipo(equipo)
                })

                return activos.toFixed(2)
            },
        },

        methods: {
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

                return isNaN((totalCosto / totalCantidad).toFixed(2)) ? 0.0 : (totalCosto / totalCantidad).toFixed(2)
            },

            getCostoTotalEquipo: function (equipo) {
                return parseFloat(this.getCostoUnitario(equipo.id_equipo) * equipo.stock_equipo).toFixed(2)
            },
        },
    }
</script>
