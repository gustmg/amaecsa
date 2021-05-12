<template>
    <v-dialog v-model="nuevoUnidadMedidaDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar unidad de medida</v-btn>
        </template>
        <v-card>
            <v-card-title>Nueva unidad de medida</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoUnidadMedidaForm" ref="nuevoUnidadMedidaForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="unidadMedida.nombre_unidad_medida"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoUnidadMedida()" :disabled="!nuevoUnidadMedidaForm" color="accent"
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
                nuevoUnidadMedidaDialog: false,
                nuevoUnidadMedidaForm: false,
                loading: false,

                unidadMedida: {
                    nombre_unidad_medida: '',
                },

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
            ...mapActions('unidad_medida', ['saveUnidadMedida', 'fetchUnidadesMedida']),

            triggerNuevoUnidadMedida: async function () {
                this.loading = true

                await this.saveUnidadMedida(this.unidadMedida)
                await this.fetchUnidadesMedida()

                this.loading = false

                this.unidadMedida.nombre_unidad_medida = ''
                this.$refs.nuevoUnidadMedidaForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoUnidadMedidaDialog = false
            },

            existeUnidadMedida: function (nombre_unidad_medida) {
                if (
                    this.unidadesMedida.some(
                        (unidadMedida) => unidadMedida.nombre_unidad_medida == nombre_unidad_medida
                    )
                ) {
                    return true
                } else return false
            },
        },
    }
</script>
