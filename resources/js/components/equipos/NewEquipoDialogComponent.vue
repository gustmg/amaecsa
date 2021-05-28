<template>
    <v-dialog v-model="nuevoEquipoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar equipo</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo equipo</v-card-title>
            <v-card-text align="center">
                <v-form v-model="nuevoEquipoForm" ref="nuevoEquipoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="equipo.nombre_equipo"
                        :rules="required"
                    ></v-text-field>
                    <v-text-field
                        label="Código de producto"
                        rounded
                        filled
                        v-model="equipo.codigo_producto_equipo"
                        :rules="codigoRules"
                        counter="12"
                        maxlength="12"
                    ></v-text-field>
                    <v-text-field
                        label="Código de barras"
                        rounded
                        filled
                        v-model="equipo.codigo_barras_equipo"
                        :rules="codigoRules"
                        append-icon="mdi-barcode"
                        counter="12"
                        maxlength="12"
                    ></v-text-field>
                    <barcode :value="equipo.codigo_barras_equipo"> Ingrese código de barras. </barcode>
                    <v-btn text class="mb-2" @click="generateReport()">Descargar código</v-btn>
                    <vue-html2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="false"
                        :paginate-elements-by-height="1400"
                        filename="hee hee"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="800px"
                        ref="html2Pdf"
                    >
                        <section slot="pdf-content">
                            <barcode :value="equipo.codigo_barras_equipo"> Ingrese código de barras. </barcode>
                        </section>
                    </vue-html2pdf>
                    <v-select
                        v-model="equipo.desechable"
                        rounded
                        filled
                        :items="consumible"
                        item-text="text"
                        item-value="id"
                        label="Modo de préstamo"
                    ></v-select>
                    <v-autocomplete
                        v-model="equipo.id_marca_equipo"
                        rounded
                        filled
                        :items="marcas"
                        item-text="nombre_marca"
                        item-value="id_marca"
                        label="Elige una marca"
                        :rules="required"
                    ></v-autocomplete>
                    <v-select
                        v-model="equipo.id_categoria_equipo"
                        rounded
                        filled
                        :items="categorias"
                        item-text="nombre_categoria"
                        item-value="id_categoria"
                        label="Elige una categoría"
                        :rules="required"
                    ></v-select>
                    <v-select
                        v-model="equipo.id_tipo_equipo"
                        rounded
                        filled
                        :items="tipoEquipo"
                        item-text="nombre_tipo_equipo"
                        item-value="id_tipo_equipo"
                        label="Tipo de equipo"
                        :rules="required"
                    ></v-select>
                    <v-select
                        v-model="equipo.id_unidad_medida_equipo"
                        rounded
                        filled
                        :items="unidades_medida"
                        item-text="nombre_unidad_medida"
                        item-value="id_unidad_medida"
                        label="Elige una unidad de medida"
                        :rules="required"
                    ></v-select>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoEquipo()" :disabled="!nuevoEquipoForm" color="accent">Registrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import VueHtml2pdf from 'vue-html2pdf'

    export default {
        components: {
            VueHtml2pdf,
        },

        data() {
            return {
                nuevoEquipoDialog: false,
                nuevoEquipoForm: false,
                loading: false,

                equipo: {
                    nombre_equipo: '',
                    codigo_producto_equipo: '',
                    codigo_barras_equipo: '',
                    desechable: 0,
                    id_marca_equipo: null,
                    id_tipo_equipo: null,
                    id_categoria_equipo: null,
                    id_unidad_medida_equipo: null,
                },

                consumible: [
                    { id: 0, text: 'Normal' },
                    { id: 1, text: 'Consumible' },
                ],

                required: [(v) => !!v || 'Este campo es requerido.'],
                codigoRules: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => v.length == 12 || 'Ingrese el código a 12 caracteres.',
                    (v) => !this.existeCodigoEquipo(v) || 'Este código ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('marca', {
                marcas: 'getMarcas',
            }),

            ...mapGetters('categoria', {
                categorias: 'getCategorias',
            }),

            ...mapGetters('unidad_medida', {
                unidades_medida: 'getUnidadesMedida',
            }),

            ...mapGetters('equipo', {
                equipos: 'getEquipos',
            }),

            ...mapGetters('tipo_equipo', {
                tipoEquipo: 'getTipoEquipo',
            }),
        },

        methods: {
            ...mapActions('equipo', ['saveEquipo', 'fetchEquipos']),

            triggerNuevoEquipo: async function () {
                this.loading = true

                await this.saveEquipo(this.equipo)
                await this.fetchEquipos()

                this.loading = false

                this.equipo.nombre_equipo = ''
                this.equipo.codigo_producto_equipo = ''
                this.equipo.codigo_barras_equipo = ''
                this.equipo.desechable = 0
                this.equipo.id_marca_equipo = null
                this.equipo.id_tipo_equipo = null
                this.equipo.id_categoria_equipo = null
                this.equipo.id_unidad_medida_equipo = null
                this.$refs.nuevoEquipoForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoEquipoDialog = false
            },

            existeCodigoEquipo: function (codigo) {
                if (this.equipos.some((equipo) => equipo.codigo_barras_equipo == codigo)) {
                    return true
                } else return false
            },

            generateReport() {
                this.$refs.html2Pdf.generatePdf()
            },
        },
    }
</script>
