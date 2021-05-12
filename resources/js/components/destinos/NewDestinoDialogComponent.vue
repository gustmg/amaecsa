<template>
    <v-dialog v-model="nuevoDestinoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar destino</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo destino</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoDestinoForm" ref="nuevoDestinoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="destino.nombre_destino"
                        :rules="required"
                        autofocus
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoDestino()" :disabled="!nuevoDestinoForm" color="accent">Registrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                nuevoDestinoDialog: false,
                nuevoDestinoForm: false,
                loading: false,

                destino: {
                    nombre_destino: '',
                    codigo_destino: '',
                },

                required: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeDestino(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),
        },

        methods: {
            ...mapActions('destino', ['saveDestino', 'fetchDestinos']),

            triggerNuevoDestino: async function () {
                this.loading = true

                await this.saveDestino(this.destino)
                await this.fetchDestinos()

                this.loading = false

                this.destino.nombre_destino = ''
                this.destino.codigo_destino = ''
                this.$refs.nuevoDestinoForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoDestinoDialog = false
            },

            existeDestino: function (codigo) {
                if (this.destinos.some((destino) => destino.nombre_destino == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
