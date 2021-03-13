<template>
    <v-dialog v-model="nuevoEquipoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo equipo</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoEquipoForm" ref="nuevoEquipoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editEquipo.nombre_equipo"
                        :rules="required"
                    ></v-text-field>
                    <v-text-field
                        label="Código de barras"
                        rounded
                        filled
                        v-model="editEquipo.codigo_barras_equipo"
                        append-icon="mdi-barcode"
                        readonly
                    ></v-text-field>
                    <v-select
                        v-model="editEquipo.desechable"
                        rounded
                        filled
                        :items="tipoEquipo"
                        item-text="text"
                        item-value="id"
                        label="Tipo de equipo"
                        readonly
                    ></v-select>
                    <v-select
                        v-model="editEquipo.id_marca_equipo"
                        rounded
                        filled
                        :items="marcas"
                        item-text="nombre_marca"
                        item-value="id_marca"
                        label="Marca"
                        readonly
                    ></v-select>
                    <v-select
                        v-model="editEquipo.id_categoria_equipo"
                        rounded
                        filled
                        :items="categorias"
                        item-text="nombre_categoria"
                        item-value="id_categoria"
                        label="Categoría"
                        readonly
                    ></v-select>
                    <v-select
                        v-model="editEquipo.id_unidad_medida_equipo"
                        rounded
                        filled
                        :items="unidades_medida"
                        item-text="nombre_unidad_medida"
                        item-value="id_unidad_medida"
                        label="Unidad de medida"
                        readonly
                    ></v-select>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoEquipo()" color="accent">Editar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            equipo: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoEquipoDialog: false,
                nuevoEquipoForm: false,
                loading: false,

                editEquipo: _.cloneDeep(this.equipo),

                tipoEquipo: [
                    { id: 0, text: 'Normal' },
                    { id: 1, text: 'Desechable' },
                ],

                required: [v => !!v || 'Este campo es requerido.'],
                codigoRules: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeCodigoEquipo(v) || 'Este código ya está registrado.',
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
        },

        methods: {
            ...mapActions('equipo', ['updateEquipo', 'fetchEquipos']),

            triggerNuevoEquipo: async function() {
                this.loading = true

                await this.updateEquipo(this.editEquipo)
                await this.fetchEquipos()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function() {
                this.$refs.nuevoEquipoForm.resetValidation()
                this.nuevoEquipoDialog = false
            },

            existeCodigoEquipo: function(codigo) {
                if (this.equipos.some(equipo => equipo.codigo_barras_equipo == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
