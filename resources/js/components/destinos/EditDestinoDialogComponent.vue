<template>
    <v-dialog v-model="nuevoDestinoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo destino</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoDestinoForm" ref="nuevoDestinoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editDestino.nombre_destino"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoDestino()" :disabled="!nuevoDestinoForm" color="accent">Editar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            destino: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoDestinoDialog: false,
                nuevoDestinoForm: false,
                loading: false,

                editDestino: _.cloneDeep(this.destino),

                required: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeDestino(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),
        },

        methods: {
            ...mapActions('destino', ['updateDestino', 'fetchDestinos']),

            triggerNuevoDestino: async function() {
                this.loading = true

                await this.updateDestino(this.editDestino)
                await this.fetchDestinos()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function() {
                this.$refs.nuevoDestinoForm.resetValidation()
                this.nuevoDestinoDialog = false
            },

            existeDestino: function(codigo) {
                if (this.destinos.some(destino => destino.nombre_destino == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
