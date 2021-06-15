<template>
    <v-dialog v-model="active" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de personal</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <!-- <xlsx-workbook>
                                <xlsx-sheet :collection="excelPrestamos" :key="sheets.name" :sheet-name="sheets.name" />
                                <xlsx-download filename="Reporte de personal.xlsx">
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

                sheets: [{ name: 'Personal' }],
            }
        },

        computed: {
            ...mapGetters('personal', {
                personal: 'getPersonal',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            excelPrestamos() {
                var excel = [
                    ['REPORTE DE PERSONAL'],
                    ['Código personal', 'Nombre', 'Préstamos realizados', 'Préstamos pendientes'],
                ]

                this.personal.forEach((persona) => {
                    excel.push([
                        persona.codigo_personal,
                        persona.nombre_personal,
                        this.getPrestamosRealizados(persona.id_personal),
                        this.getPrestamosPendientes(persona.id_personal),
                    ])
                })

                return excel
            },
        },

        methods: {
            getPrestamosRealizados: function (id_personal) {
                return this.prestamos.filter((prestamo) => {
                    return prestamo.id_personal == id_personal
                }).length
            },

            getPrestamosPendientes: function (id_personal) {
                return this.prestamos.filter((prestamo) => {
                    return prestamo.id_personal == id_personal && prestamo.recibido == 0
                }).length
            },

            exportExcel() {
                const jsonWorkSheet = XLSX.utils.aoa_to_sheet(this.excelPrestamos)
                const workBook = {
                    SheetNames: ['Personal'], // sheet name
                    Sheets: {
                        Personal: jsonWorkSheet,
                    },
                }

                XLSX.writeFile(workBook, 'reporte de personal.xlsx')
            },
        },
    }
</script>
