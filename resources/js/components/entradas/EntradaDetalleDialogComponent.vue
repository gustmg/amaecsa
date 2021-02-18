<template>
    <v-dialog v-model="detalleEntradaDialog" width="100%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Ver detalles</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo entrada</v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="6" offset-md="3">
                            <v-text-field
                                v-model="buscarEquipo"
                                label="Filtrar entradas..."
                                rounded
                                filled
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-data-table
                                :headers="equiposEntradaHeaders"
                                :items="entrada.equipos"
                                :search="buscarEquipo"
                                item-key="id_equipo"
                                hide-default-footer
                            >
                                <template v-slot:item.subtotal="{ item }">
                                    <v-text-field
                                        :value="(item.pivot.costo_unitario * item.pivot.cantidad).toFixed(2)"
                                        type="number"
                                        label="Subtotal"
                                        rounded
                                        filled
                                        prepend-icon="mdi-currency-usd"
                                        readonly
                                    ></v-text-field>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="2" offset-md="10">
                            <v-text-field
                                label="Costo total"
                                readonly
                                rounded
                                filled
                                :value="costoTotal"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
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
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-data-table
                                        :headers="equiposEntradaHeaders"
                                        :items="entrada.equipos"
                                        :search="buscarEquipo"
                                        item-key="id_equipo"
                                        hide-default-footer
                                    >
                                        <template v-slot:item.subtotal="{ item }">
                                            <v-text-field
                                                :value="(item.pivot.costo_unitario * item.pivot.cantidad).toFixed(2)"
                                                type="number"
                                                label="Subtotal"
                                                rounded
                                                filled
                                                prepend-icon="mdi-currency-usd"
                                                readonly
                                            ></v-text-field>
                                        </template>
                                    </v-data-table>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="2" offset-md="10">
                                    <v-text-field
                                        label="Total"
                                        readonly
                                        rounded
                                        filled
                                        :value="costoTotal"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </section>
                </vue-html2pdf>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
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
            entrada: {
                type: Object,
            },
        },

        data() {
            return {
                detalleEntradaDialog: false,
                buscarEquipo: '',

                equiposEntradaHeaders: [
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Equipo', value: 'nombre_equipo' },
                    { text: 'Marca', value: 'marca.nombre_marca' },
                    { text: 'Categoria', value: 'categoria.nombre_categoria' },
                    { text: 'Unidad de medida', value: 'unidad_medida.nombre_unidad_medida' },
                    { text: 'Cantidad', value: 'pivot.cantidad' },
                    { text: 'Costo unitario', value: 'pivot.costo_unitario' },
                    { text: 'Subtotal', value: 'subtotal' },
                ],
            }
        },

        computed: {
            costoTotal: function() {
                var total = 0
                this.entrada.equipos.forEach(equipo => {
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
