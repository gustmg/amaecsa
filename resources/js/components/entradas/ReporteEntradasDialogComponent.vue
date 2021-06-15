<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de entradas</v-card-title>
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
                                        <xlsx-sheet :collection="excelEntradas" key="Entradas" sheet-name="Entradas" />
                                        <xlsx-download filename="Reporte de entradas.xlsx">
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

            filteredEntradas() {
                if (this.fechaUnica) {
                    return this.entradas.filter((entrada) => {
                        if (this.rango) {
                            if (moment(entrada.created_at).isBetween(this.fechaDesde, this.fechaHasta)) {
                                return entrada
                            }
                        } else {
                            if (moment(entrada.created_at).diff(moment(this.fechaUnica), 'days') == 0) {
                                return entrada
                            }
                        }
                    })
                }
            },

            excelEntradas() {
                var excel = [
                    ['REPORTE DE ENTRADAS'],
                    [
                        'Folio de entrada',
                        'Número de vale',
                        'Fecha',
                        'Destino',
                        'Equipo',
                        'Marca',
                        'Categoría',
                        'Tipo',
                        'Código de barras',
                        'Código de producto',
                        'Cantidad',
                        'Unidad de medida',
                        'Costo unitario',
                        'Importe',
                    ],
                ]

                this.filteredEntradas.forEach((entrada) => {
                    entrada.equipos.forEach((equipo) => {
                        excel.push([
                            entrada.id_entrada,
                            entrada.numero_vale_entrada,
                            entrada.created_at,
                            entrada.destino.nombre_destino,
                            equipo.nombre_equipo,
                            equipo.marca.nombre_marca,
                            equipo.categoria.nombre_categoria,
                            equipo.tipo_equipo.nombre_tipo_equipo,
                            equipo.codigo_barras_equipo,
                            equipo.codigo_producto_equipo,
                            equipo.pivot.cantidad,
                            equipo.unidad_medida.nombre_unidad_medida,
                            parseFloat(equipo.pivot.costo_unitario).toFixed(2),
                            parseFloat(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2),
                        ])
                    })
                })

                excel.push(['', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL', this.totalGeneral])

                return excel
            },

            totalGeneral() {
                var total = 0

                this.filteredEntradas.forEach((entrada) => {
                    entrada.equipos.forEach((equipo) => {
                        total += +parseFloat(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2)
                    })
                })

                return total
            },
        },

        methods: {
            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelEntradas)
                const workBook = {
                    SheetNames: ['Entradas'], // sheet name
                    Sheets: {
                        Entradas: jsonWorkSheet,
                    },
                }

                const range = XLSX.utils.decode_range(jsonWorkSheet['!ref'])
                for (let row = range.s.r + 2; row <= range.e.r; ++row) {
                    const ref = XLSX.utils.encode_cell({ r: row, c: 13 })
                    jsonWorkSheet[ref].t = 'n'
                    jsonWorkSheet[ref].z = '0.00'
                }

                XLSX.writeFile(workBook, 'reporte de entradas.xlsx')
            },
        },
    }
</script>
