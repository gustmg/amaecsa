<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de préstamos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <v-select
                                label="Filtrar por"
                                :items="tipoFiltro"
                                v-model="selectFiltro"
                                item-value="id"
                                item-text="text"
                                solo
                                class="mt-6"
                            >
                            </v-select>
                            <v-divider></v-divider>
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
                                        <xlsx-sheet
                                            :collection="excelPrestamos"
                                            key="Prestamos"
                                            sheet-name="Prestamos"
                                        />
                                        <xlsx-download filename="Reporte de prestamos.xlsx">
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
                selectFiltro: 1,

                tipoFiltro: [
                    { id: 1, text: 'Todos' },
                    { id: 2, text: 'Recibidos' },
                    { id: 3, text: 'Pendientes por recibir' },
                    { id: 4, text: 'Consumibles' },
                ],
            }
        },

        computed: {
            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),

            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
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

            filteredPrestamos() {
                if (this.fechaUnica) {
                    return this.prestamos
                        .filter((prestamo) => {
                            if (this.rango) {
                                if (moment(prestamo.created_at).isBetween(this.fechaDesde, this.fechaHasta)) {
                                    return prestamo
                                }
                            } else {
                                if (moment(prestamo.created_at).diff(moment(this.fechaUnica), 'days') == 0) {
                                    return prestamo
                                }
                            }
                        })
                        .filter((prestamo) => {
                            if (this.selectFiltro == 1) {
                                return prestamo
                            } else if (this.selectFiltro == 2) {
                                return prestamo.recibido == 1
                            } else if (this.selectFiltro == 3) {
                                return prestamo.recibido == 0
                            } else {
                                return prestamo.recibido == null
                            }
                        })
                }
            },

            excelPrestamos() {
                var excel = [
                    ['REPORTE DE PRESTAMOS'],
                    [
                        'Folio de préstamo',
                        'Fecha de entrega',
                        'Fecha de recepción',
                        'Recibido',
                        'Personal',
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
                        'Valor de préstamo',
                    ],
                ]

                this.filteredPrestamos.forEach((prestamo) => {
                    excel.push([
                        prestamo.id_prestamo,
                        prestamo.created_at,
                        this.getFechaRecibido(prestamo),
                        this.getPrestamoRecibido(prestamo),
                        prestamo.personal.nombre_personal,
                        prestamo.destino.nombre_destino,
                        prestamo.equipo.nombre_equipo,
                        prestamo.equipo.marca.nombre_marca,
                        prestamo.equipo.categoria.nombre_categoria,
                        prestamo.equipo.tipo_equipo.nombre_tipo_equipo,
                        prestamo.equipo.codigo_barras_equipo,
                        prestamo.equipo.codigo_producto_equipo,
                        prestamo.cantidad,
                        prestamo.equipo.unidad_medida.nombre_unidad_medida,
                        this.getCostoUnitario(prestamo.equipo.id_equipo),
                        parseFloat(this.getCostoUnitario(prestamo.equipo.id_equipo) * prestamo.cantidad).toFixed(2),
                    ])
                })

                excel.push(['', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL', this.totalGeneral])

                return excel
            },

            totalGeneral() {
                var total = 0

                this.filteredPrestamos.forEach((prestamo) => {
                    total += +parseFloat(this.getCostoUnitario(prestamo.equipo.id_equipo) * prestamo.cantidad).toFixed(
                        2
                    )
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

            getFechaRecibido: function (prestamo) {
                if (prestamo.equipo.desechable) {
                    return 'N/A'
                } else if (prestamo.created_at == prestamo.updated_at) {
                    return 'Pendiente de recibir'
                } else {
                    return prestamo.updated_at
                }
            },

            getPrestamoRecibido: function (prestamo) {
                if (prestamo.recibido) {
                    return 'SI'
                } else if (prestamo.recibido == 0) {
                    return 'NO'
                } else return 'N/A'
            },

            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelPrestamos)
                const workBook = {
                    SheetNames: ['Prestamos'], // sheet name
                    Sheets: {
                        Prestamos: jsonWorkSheet,
                    },
                }

                const range = XLSX.utils.decode_range(jsonWorkSheet['!ref'])
                for (let row = range.s.r + 2; row <= range.e.r; ++row) {
                    const ref = XLSX.utils.encode_cell({ r: row, c: 15 })
                    jsonWorkSheet[ref].t = 'n'
                    jsonWorkSheet[ref].z = '0.00'
                }

                XLSX.writeFile(workBook, 'reporte de prestamos.xlsx')
            },
        },
    }
</script>
