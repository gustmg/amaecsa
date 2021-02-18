<template>
    <v-dialog v-model="nuevoCategoriaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo categoria</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoCategoriaForm" ref="nuevoCategoriaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editCategoria.nombre_categoria"
                        :rules="nombreCategoriaRules"
                    ></v-text-field>
                    <v-text-field
                        label="Código"
                        rounded
                        filled
                        v-model="editCategoria.codigo_categoria"
                        :rules="codigoCategoriaRules"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoCategoria()" :disabled="!nuevoCategoriaForm" color="accent"
                    >Editar</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            categoria: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoCategoriaDialog: false,
                nuevoCategoriaForm: false,
                loading: false,

                editCategoria: _.cloneDeep(this.categoria),

                nombreCategoriaRules: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeNombreCategoria(v) || 'Este nombre ya existe.',
                ],
                codigoCategoriaRules: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeCodigoCategoria(v) || 'Este nombre ya existe.',
                ],
            }
        },

        computed: {
            ...mapGetters('categoria', {
                categorias: 'getCategorias',
            }),
        },

        methods: {
            ...mapActions('categoria', ['updateCategoria', 'fetchCategorias']),

            triggerNuevoCategoria: async function() {
                this.loading = true

                await this.updateCategoria(this.editCategoria)
                await this.fetchCategorias()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function() {
                this.$refs.nuevoCategoriaForm.resetValidation()
                this.nuevoCategoriaDialog = false
            },

            existeNombreCategoria: function(nombre) {
                if (this.categorias.some(categoria => categoria.nombre_categoria == nombre)) {
                    return true
                } else return false
            },

            existeCodigoCategoria: function(codigo) {
                if (this.categorias.some(categoria => categoria.codigo_categoria == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
