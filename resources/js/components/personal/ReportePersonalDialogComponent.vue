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
                            <vue-excel-editor v-model="personalReporte" ref="report" filter-row readonly>
                                <vue-excel-column
                                    width="64px"
                                    field="codigoPersonal"
                                    label="Código de personal"
                                ></vue-excel-column>
                                <vue-excel-column width="80px" field="nombrePersonal" label="Nombre"></vue-excel-column>
                                <vue-excel-column
                                    width="80px"
                                    field="prestamosRealizados"
                                    label="Préstamos realizados"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="80px"
                                    field="prestamosPendientes"
                                    label="Préstamos pendientes"
                                ></vue-excel-column>
                            </vue-excel-editor>
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
            ...mapGetters('personal', {
                personal: 'getPersonal',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            personalReporte: function () {
                var personalReporte = []

                if (this.personal.length > 0) {
                    this.personal.forEach((personal) => {
                        personalReporte.push({
                                codigoPersonal: personal.codigo_personal,
                                nombrePersonal: personal.nombre_personal,
                                prestamosRealizados: this.getPrestamosRealizados(personal.id_personal),
                                prestamosPendientes: this.getPrestamosPendientes(personal.id_personal),
                            })
                    })
                }

                return personalReporte
            },
        },

        methods: {
            descargaReporte: function () {
                const format = 'xlsx'
                const exportSelectedOnly = false
                const filename = 'reporte_personal'
                this.$refs.report.exportTable(format, exportSelectedOnly, filename)
            },

            getPrestamosRealizados: function(id_personal) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_personal == id_personal
                }).length
            },

            getPrestamosPendientes: function(id_personal) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_personal == id_personal && prestamo.recibido == 0
                }).length
            },
        },
    }
</script>
