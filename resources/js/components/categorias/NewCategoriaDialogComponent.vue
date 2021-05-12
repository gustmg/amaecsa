<template>
    <v-dialog v-model="nuevoCategoriaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar categoria</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo categoria</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoCategoriaForm" ref="nuevoCategoriaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="categoria.nombre_categoria"
                        :rules="nombreCategoriaRules"
                    ></v-text-field>
                    <v-text-field
                        label="Código"
                        rounded
                        filled
                        v-model="categoria.codigo_categoria"
                        :rules="codigoCategoriaRules"
                        counter="10"
                        maxlength="10"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoCategoria()" :disabled="!nuevoCategoriaForm" color="accent"
                    >Registrar</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                nuevoCategoriaDialog: false,
                nuevoCategoriaForm: false,
                loading: false,

                categoria: {
                    nombre_categoria: '',
                    codigo_categoria: '',
                },

                nombreCategoriaRules: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeNombreCategoria(v) || 'Este nombre ya existe.',
                ],
                codigoCategoriaRules: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeCodigoCategoria(v) || 'Este nombre ya existe.',
                ],
            }
        },

        computed: {
            ...mapGetters('categoria', {
                categorias: 'getCategorias',
            }),
        },

        methods: {
            ...mapActions('categoria', ['saveCategoria', 'fetchCategorias']),

            triggerNuevoCategoria: async function () {
                this.loading = true

                await this.saveCategoria(this.categoria)
                await this.fetchCategorias()

                this.loading = false

                this.categoria.nombre_categoria = ''
                this.categoria.codigo_categoria = ''
                this.$refs.nuevoCategoriaForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoCategoriaDialog = false
            },

            existeNombreCategoria: function (nombre) {
                if (this.categorias.some((categoria) => categoria.nombre_categoria == nombre)) {
                    return true
                } else return false
            },

            existeCodigoCategoria: function (codigo) {
                if (this.categorias.some((categoria) => categoria.codigo_categoria == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
