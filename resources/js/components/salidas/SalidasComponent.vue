<template>
    <v-container>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de salida ({{ salidas.length }} registradas)</div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-salidas-dialog-component></reporte-salidas-dialog-component>
                <new-salida-dialog-component></new-salida-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchSalida"
                    label="Buscar salida"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-data-table :headers="salidaHeaders" :items="salidas" :search="searchSalida" item-key="id_salida">
                        <template v-slot:item.opciones="{ item }">
                            <salida-detalle-dialog-component :salida="item"></salida-detalle-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReporteSalidasDialogComponent from './ReporteSalidasDialogComponent.vue'

    export default {
        components: {
            ReporteSalidasDialogComponent,
        },

        async mounted() {
            await this.fetchSalidas()
            await this.fetchEntradas()
            await this.fetchEquipos()
        },

        data() {
            return {
                salidaHeaders: [
                    { text: 'Folio', value: 'id_salida', width: '20%' },
                    { text: 'Fecha', value: 'created_at', width: '60%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchSalida: '',
            }
        },

        computed: {
            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),
        },

        methods: {
            ...mapActions('salida', ['fetchSalidas']),
            ...mapActions('entrada', ['fetchEntradas']),
            ...mapActions('equipo', ['fetchEquipos']),

            getCostoTotal: function (salida) {
                var total = 0
                salida.equipos.forEach((equipo) => {
                    total = total + +(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            download: function () {
                this.$refs.html2Pdf.generatePdf()
            },
        },
    }
</script>
