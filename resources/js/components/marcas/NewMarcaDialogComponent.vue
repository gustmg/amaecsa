<template>
    <v-dialog v-model="nuevoMarcaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar marca</v-btn>
        </template>
        <v-card>
            <v-card-title>Nueva marca</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoMarcaForm" ref="nuevoMarcaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="marca.nombre_marca"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoMarca()" :disabled="!nuevoMarcaForm" color="accent">Registrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                nuevoMarcaDialog: false,
                nuevoMarcaForm: false,
                loading: false,

                marca: {
                    nombre_marca: '',
                },

                required: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeMarca(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('marca', {
                marcas: 'getMarcas',
            }),
        },

        methods: {
            ...mapActions('marca', ['saveMarca', 'fetchMarcas']),

            triggerNuevoMarca: async function () {
                this.loading = true

                await this.saveMarca(this.marca)
                await this.fetchMarcas()

                this.loading = false

                this.marca.nombre_marca = ''
                this.$refs.nuevoMarcaForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoMarcaDialog = false
            },

            existeMarca: function (codigo) {
                if (this.marcas.some((marca) => marca.nombre_marca == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
