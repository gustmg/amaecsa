<template>
    <v-container>
        <v-row align="center">
            <v-col cols="5">
                <div class="text-h6">Catálogo de salida ({{ salidas.length }} registradas)</div>
            </v-col>
            <v-col cols="3" align="right">
                <v-btn v-on:click="download()" class="mx-2" color="accent">Descargar tabla</v-btn>
                <new-salida-dialog-component></new-salida-dialog-component>
            </v-col>
            <v-col cols="4">
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
                <v-data-table :headers="salidaHeaders" :items="salidas" :search="searchSalida" item-key="id_salida">
                    <template v-slot:item.opciones="{ item }">
                        <salida-detalle-dialog-component :salida="item"></salida-detalle-dialog-component>
                    </template>
                </v-data-table>
                <vue-html2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="true"
                    :preview-modal="false"
                    :paginate-elements-by-height="1400"
                    filename="salidas"
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-format="a4"
                    pdf-orientation="landscape"
                    pdf-content-width="800px"
                    ref="html2Pdf"
                >
                    <section slot="pdf-content">
                        <v-data-table
                            :headers="salidaHeaders"
                            :items="salidas"
                            :search="searchSalida"
                            item-key="id_salida"
                        >
                            <template v-slot:item.opciones="{ item }">
                                <salida-detalle-dialog-component :salida="item"></salida-detalle-dialog-component>
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
            await this.fetchSalidas()
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

            ...mapActions('equipo', ['fetchEquipos']),

            getCostoTotal: function(salida) {
                var total = 0
                salida.equipos.forEach(equipo => {
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
