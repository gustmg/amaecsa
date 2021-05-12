<template>
    <v-dialog v-model="nuevoUnidadMedidaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Editar unidad de medida</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoUnidadMedidaForm" ref="nuevoUnidadMedidaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editUnidadMedida.nombre_unidad_medida"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoUnidadMedida()" :disabled="!nuevoUnidadMedidaForm" color="accent"
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
            unidadMedida: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoUnidadMedidaDialog: false,
                nuevoUnidadMedidaForm: false,
                loading: false,

                editUnidadMedida: _.cloneDeep(this.unidadMedida),

                required: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeUnidadMedida(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('unidad_medida', {
                unidadesMedida: 'getUnidadesMedida',
            }),
        },

        methods: {
            ...mapActions('unidad_medida', ['updateUnidadMedida', 'fetchUnidadesMedida']),

            triggerNuevoUnidadMedida: async function () {
                this.loading = true

                await this.updateUnidadMedida(this.editUnidadMedida)
                await this.fetchUnidadesMedida()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function () {
                this.$refs.nuevoUnidadMedidaForm.resetValidation()
                this.nuevoUnidadMedidaDialog = false
            },

            existeUnidadMedida: function (codigo) {
                if (this.unidadesMedida.some((unidadMedida) => unidadMedida.nombre_unidad_medida == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
