<template>
    <v-dialog v-model="nuevoMarcaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo marca</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoMarcaForm" ref="nuevoMarcaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editMarca.nombre_marca"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoMarca()" :disabled="!nuevoMarcaForm" color="accent">Editar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            marca: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoMarcaDialog: false,
                nuevoMarcaForm: false,
                loading: false,

                editMarca: _.cloneDeep(this.marca),

                required: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeMarca(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('marca', {
                marcas: 'getMarcas',
            }),
        },

        methods: {
            ...mapActions('marca', ['updateMarca', 'fetchMarcas']),

            triggerNuevoMarca: async function() {
                this.loading = true

                await this.updateMarca(this.editMarca)
                await this.fetchMarcas()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function() {
                this.$refs.nuevoMarcaForm.resetValidation()
                this.nuevoMarcaDialog = false
            },

            existeMarca: function(codigo) {
                if (this.marcas.some(marca => marca.nombre_marca == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
