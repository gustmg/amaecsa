<template>
    <v-dialog v-model="active" width="560">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de destinos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <vue-excel-editor v-model="destinoReporte" ref="report" filter-row readonly>
                                <vue-excel-column
                                    width="160px"
                                    field="nombreDestino"
                                    label="Destino"
                                ></vue-excel-column>
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
            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            destinoReporte: function () {
                var destinoReporte = []

                if (this.destinos.length > 0) {
                    this.destinos.forEach((destino) => {
                        destinoReporte.push({
                                nombreDestino: destino.nombre_destino,
                                prestamosRealizados: this.getPrestamosRealizados(destino.id_destino),
                                prestamosPendientes: this.getPrestamosPendientes(destino.id_destino),
                            })
                    })
                }

                return destinoReporte
            },
        },

        methods: {
            descargaReporte: function () {
                const format = 'xlsx'
                const exportSelectedOnly = false
                const filename = 'reporte_destinos'
                this.$refs.report.exportTable(format, exportSelectedOnly, filename)
            },

            getPrestamosRealizados: function(id_destino) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_destino == id_destino
                }).length
            },

            getPrestamosPendientes: function(id_destino) {
                return this.prestamos.filter(prestamo => {
                    return prestamo.id_destino == id_destino && prestamo.recibido == 0
                }).length
            },
        },
    }
</script>
