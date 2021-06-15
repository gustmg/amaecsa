<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de destinos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <!-- <div class="text-subtitle-1">Reporte generado</div>
                            <xlsx-workbook>
                                <xlsx-sheet :collection="excelDestinos" :key="sheets.name" :sheet-name="sheets.name" />
                                <xlsx-download filename="Reporte de destinos.xlsx">
                                    <v-btn color="primary">Descargar reporte</v-btn>
                                </xlsx-download>
                            </xlsx-workbook> -->
                            <v-btn @click="exportExcel()" color="primary">Descargar</v-btn>
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

                sheets: [{ name: 'Destinos' }],
            }
        },

        computed: {
            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            excelDestinos() {
                var excel = [['REPORTE DE DESTINOS'], ['Destino', 'Préstamos realizados', 'Préstamos pendientes']]

                this.destinos.forEach((destino) => {
                    excel.push([
                        destino.nombre_destino,
                        this.getPrestamosRealizados(destino.id_destino),
                        this.getPrestamosPendientes(destino.id_destino),
                    ])
                })

                return excel
            },
        },

        methods: {
            getPrestamosRealizados: function (id_destino) {
                return this.prestamos.filter((prestamo) => {
                    return prestamo.id_destino == id_destino
                }).length
            },

            getPrestamosPendientes: function (id_destino) {
                return this.prestamos.filter((prestamo) => {
                    return prestamo.id_destino == id_destino && prestamo.recibido == 0
                }).length
            },

            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelDestinos)
                const workBook = {
                    SheetNames: ['Destinos'], // sheet name
                    Sheets: {
                        Destinos: jsonWorkSheet,
                    },
                }

                XLSX.writeFile(workBook, 'reporte de destinos.xlsx')
            },
        },
    }
</script>
