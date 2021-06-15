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
                            <v-btn @click="exportExcel()" color="primary">Descargar</v-btn>
                            <!-- <xlsx-workbook>
                                <xlsx-sheet :collection="excelEquipos" key="equipos" sheet-name="equipos" />
                                <xlsx-download filename="Reporte de equipos.xlsx">
                                    <v-btn color="primary">Descargar reporte</v-btn>
                                </xlsx-download>
                            </xlsx-workbook> -->
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
    import XLSX from 'xlsx'

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
                var excel = [
                    ['REPORTE DE EQUIPOS'],
                    [
                        'Código de barras',
                        'Código de producto',
                        'Nombre',
                        'Categoría',
                        'Tipo',
                        'Marca',
                        'Stock',
                        'Unidad de medida',
                        'Costo unitario',
                        'Costo total',
                    ],
                ]

                this.equipos.forEach((equipo) => {
                    excel.push([
                        equipo.codigo_barras_equipo,
                        equipo.codigo_producto_equipo,
                        equipo.nombre_equipo,
                        equipo.categoria.nombre_categoria,
                        equipo.tipo_equipo.nombre_tipo_equipo,
                        equipo.marca.nombre_marca,
                        equipo.stock_equipo,
                        equipo.unidad_medida.nombre_unidad_medida,
                        this.getCostoUnitario(equipo.id_equipo),
                        this.getCostoTotalEquipo(equipo),
                    ])
                })

                excel.push(['', '', '', '', '', '', '', '', 'TOTAL', this.activos])

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

            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelEquipos)
                const workBook = {
                    SheetNames: ['Equipos'], // sheet name
                    Sheets: {
                        Equipos: jsonWorkSheet,
                    },
                }

                const range = XLSX.utils.decode_range(jsonWorkSheet['!ref'])
                for (let row = range.s.r + 2; row <= range.e.r; ++row) {
                    const ref = XLSX.utils.encode_cell({ r: row, c: 9 })
                    jsonWorkSheet[ref].t = 'n'
                    jsonWorkSheet[ref].z = '0.00'
                }

                XLSX.writeFile(workBook, 'reporte de equipos.xlsx')
            },
        },
    }
</script>
