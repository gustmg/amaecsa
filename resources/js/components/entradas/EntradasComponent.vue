<template>
    <v-container fluid>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de entrada ({{ entradas.length }} registradas)</div>
            </v-col>
            <v-col class="d-inline-flex">
                <reporte-entradas-dialog-component></reporte-entradas-dialog-component>
                <new-entrada-dialog-component></new-entrada-dialog-component>
            </v-col>
            <v-col>
                <v-text-field
                    v-model="searchEntrada"
                    label="Buscar entrada"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="entradaHeaders"
                        :items="entradas"
                        :search="searchEntrada"
                        item-key="id_entrada"
                    >
                        <template v-slot:item.costo_total="{ item }"> $ {{ getCostoTotal(item) }} </template>
                        <template v-slot:item.opciones="{ item }">
                            <entrada-detalle-dialog-component :entrada="item"></entrada-detalle-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import ReporteEntradasDialogComponent from './ReporteEntradasDialogComponent.vue'

    export default {
        components: {
            ReporteEntradasDialogComponent,
        },

        async mounted() {
            await this.fetchEntradas()
            await this.fetchEquipos()
            await this.fetchDestinos()
        },

        data() {
            return {
                entradaHeaders: [
                    { text: 'Folio', value: 'id_entrada', width: '20%' },
                    { text: 'Fecha', value: 'created_at', width: '30%' },
                    { text: 'Destino', value: 'destino.nombre_destino', width: '30%' },
                    { text: 'Costo total de entrada', value: 'costo_total', width: '30%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchEntrada: '',
            }
        },

        computed: {
            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),
        },

        methods: {
            ...mapActions('entrada', ['fetchEntradas']),
            ...mapActions('destino', ['fetchDestinos']),
            ...mapActions('equipo', ['fetchEquipos']),

            getCostoTotal: function (entrada) {
                var total = 0
                entrada.equipos.forEach((equipo) => {
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
