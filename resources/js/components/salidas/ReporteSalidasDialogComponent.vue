<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de salidas</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <v-checkbox label="Rango de fechas" v-model="rango"></v-checkbox>

                            <v-row v-if="!rango">
                                <v-col cols="12">
                                    <v-date-picker v-model="fechaUnica"></v-date-picker>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col cols="12">
                                    <v-date-picker v-model="fechaRango" range></v-date-picker>
                                </v-col>
                                <v-col>
                                    <v-text-field label="Desde" v-model="fechaDesde" solo readonly></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field label="Hasta" v-model="fechaHasta" solo readonly></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <!-- <xlsx-workbook>
                                        <xlsx-sheet :collection="excelSalidas" key="Salidas" sheet-name="Salidas" />
                                        <xlsx-download filename="Reporte de salidas.xlsx">
                                            <v-btn color="primary">Descargar reporte</v-btn>
                                        </xlsx-download>
                                    </xlsx-workbook> -->
                                    <v-btn @click="exportExcel()" color="primary">Descargar</v-btn>
                                </v-col>
                            </v-row>
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
    import moment from 'moment'
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
                fechaUnica: moment().format('YYYY-MM-DD'),
                fechaRango: [],
                rango: false,
            }
        },

        computed: {
            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),

            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            fechaDesde() {
                if (this.fechaRango.length == 2) {
                    if (moment(this.fechaRango[0]).isBefore(moment(this.fechaRango[1]))) {
                        return this.fechaRango[0]
                    } else {
                        return this.fechaRango[1]
                    }
                } else {
                    return null
                }
            },

            fechaHasta() {
                if (this.fechaRango.length == 2) {
                    if (moment(this.fechaRango[0]).isBefore(moment(this.fechaRango[1]))) {
                        return this.fechaRango[1]
                    } else {
                        return this.fechaRango[0]
                    }
                } else {
                    return null
                }
            },

            filteredSalidas() {
                if (this.fechaUnica) {
                    return this.salidas.filter((salida) => {
                        if (this.rango) {
                            if (moment(salida.created_at).isBetween(this.fechaDesde, this.fechaHasta)) {
                                return salida
                            }
                        } else {
                            if (moment(salida.created_at).diff(moment(this.fechaUnica), 'days') == 0) {
                                return salida
                            }
                        }
                    })
                }
            },

            excelSalidas() {
                var excel = [
                    ['REPORTE DE SALIDAS'],
                    [
                        'Folio de salida',
                        'Fecha',
                        'Equipo',
                        'Marca',
                        'Categoría',
                        'Tipo',
                        'Código de barras',
                        'Código de producto',
                        'Motivo',
                        'Cantidad',
                        'Unidad de medida',
                        'Costo unitario',
                        'Importe',
                    ],
                ]

                this.filteredSalidas.forEach((salida) => {
                    salida.equipos.forEach((equipo) => {
                        excel.push([
                            salida.id_salida,
                            salida.created_at,
                            equipo.nombre_equipo,
                            equipo.marca.nombre_marca,
                            equipo.categoria.nombre_categoria,
                            equipo.tipo_equipo.nombre_tipo_equipo,
                            equipo.codigo_barras_equipo,
                            equipo.codigo_producto_equipo,
                            equipo.pivot.comentario,
                            equipo.pivot.cantidad,
                            equipo.unidad_medida.nombre_unidad_medida,
                            this.getCostoUnitario(equipo.id_equipo),
                            parseFloat(this.getCostoUnitario(equipo.id_equipo) * equipo.pivot.cantidad).toFixed(2),
                        ])
                    })
                })

                excel.push(['', '', '', '', '', '', '', '', '', '', '', 'TOTAL', this.totalGeneral])

                return excel
            },

            totalGeneral() {
                var total = 0

                this.filteredSalidas.forEach((salida) => {
                    salida.equipos.forEach((equipo) => {
                        total += +parseFloat(this.getCostoUnitario(equipo.id_equipo) * equipo.pivot.cantidad).toFixed(2)
                    })
                })

                return total
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

                return (totalCosto / totalCantidad).toFixed(2)
            },

            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelSalidas)
                const workBook = {
                    SheetNames: ['Salidas'], // sheet name
                    Sheets: {
                        Salidas: jsonWorkSheet,
                    },
                }

                const range = XLSX.utils.decode_range(jsonWorkSheet['!ref'])
                for (let row = range.s.r + 2; row <= range.e.r; ++row) {
                    const ref = XLSX.utils.encode_cell({ r: row, c: 12 })
                    jsonWorkSheet[ref].t = 'n'
                    jsonWorkSheet[ref].z = '0.00'
                }

                XLSX.writeFile(workBook, 'reporte de salidas.xlsx')
            },
        },
    }
</script>
