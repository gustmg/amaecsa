<template>
    <v-container>
        <v-row align="center">
            <v-col>
                <div class="text-h6">Catálogo de entrada ({{ entradas.length }} registradas)</div>
            </v-col>
            <v-col class="d-inline-flex">
                <v-btn v-on:click="download()" class="mx-2" color="accent">Descargar tabla</v-btn>
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
                <vue-html2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="true"
                    :preview-modal="false"
                    :paginate-elements-by-height="1400"
                    filename="entradas"
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-format="a4"
                    pdf-orientation="landscape"
                    pdf-content-width="800px"
                    ref="html2Pdf"
                >
                    <section slot="pdf-content">
                        <v-data-table
                            :headers="entradaHeaders"
                            :items="entradas"
                            :search="searchEntrada"
                            item-key="id_entrada"
                        >
                            <template v-slot:item.costo_total="{ item }"> {{ getCostoTotal(item) }} </template>
                            <template v-slot:item.opciones="{ item }">
                                <entrada-detalle-dialog-component :entrada="item"></entrada-detalle-dialog-component>
                            </template>
                        </v-data-table>
                    </section>
                </vue-html2pdf>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import VueHtml2pdf from 'vue-html2pdf'

    export default {
        async mounted() {
            await this.fetchEntradas()
            await this.fetchEquipos()
        },

        data() {
            return {
                entradaHeaders: [
                    { text: 'Folio', value: 'id_entrada', width: '20%' },
                    { text: 'Fecha', value: 'created_at', width: '30%' },
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

            ...mapActions('equipo', ['fetchEquipos']),

            getCostoTotal: function(entrada) {
                var total = 0
                entrada.equipos.forEach(equipo => {
                    total = total + +(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            download: function() {
                this.$refs.html2Pdf.generatePdf()
            },
        },
    }
</script>
