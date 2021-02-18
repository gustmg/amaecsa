<template>
    <v-dialog v-model="detalleSalidaDialog" width="100%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Ver detalles</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo salida</v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="6" offset-md="3">
                            <v-text-field
                                v-model="buscarEquipo"
                                label="Filtrar salidas..."
                                rounded
                                filled
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-data-table
                                :headers="equiposSalidaHeaders"
                                :items="salida.equipos"
                                :search="buscarEquipo"
                                item-key="id_equipo"
                                hide-default-footer
                            >
                            </v-data-table>
                        </v-col>
                        <vue-html2pdf
                            :show-layout="false"
                            :float-layout="true"
                            :enable-download="true"
                            :preview-modal="false"
                            :paginate-elements-by-height="1400"
                            filename="detalle_salida"
                            :pdf-quality="2"
                            :manual-pagination="false"
                            pdf-format="a4"
                            pdf-orientation="landscape"
                            pdf-content-width="800px"
                            ref="html2Pdf"
                        >
                            <section slot="pdf-content">
                                <v-data-table
                                    :headers="equiposSalidaHeaders"
                                    :items="salida.equipos"
                                    :search="buscarEquipo"
                                    item-key="id_equipo"
                                    hide-default-footer
                                >
                                </v-data-table>
                            </section>
                        </vue-html2pdf>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="detalleSalidaDialog = false">Cancelar</v-btn>
                <v-btn v-on:click="download()" class="mx-2" color="accent">Descargar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import VueHtml2pdf from 'vue-html2pdf'

    export default {
        props: {
            salida: {
                type: Object,
            },
        },

        data() {
            return {
                detalleSalidaDialog: false,
                buscarEquipo: '',

                equiposSalidaHeaders: [
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Equipo', value: 'nombre_equipo' },
                    { text: 'Marca', value: 'marca.nombre_marca' },
                    { text: 'Categoria', value: 'categoria.nombre_categoria' },
                    { text: 'Unidad de medida', value: 'unidad_medida.nombre_unidad_medida' },
                    { text: 'Cantidad', value: 'pivot.cantidad' },
                    { text: 'Comentario', value: 'pivot.comentario' },
                ],
            }
        },

        computed: {
            costoTotal: function() {
                var total = 0
                this.salida.equipos.forEach(equipo => {
                    total = total + +(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },
        },

        methods: {
            download: function() {
                this.$refs.html2Pdf.generatePdf()
            },
        },
    }
</script>
