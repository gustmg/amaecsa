<template>
    <v-dialog v-model="nuevoTipoEquipoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar tipo de equipo</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo tipo de equipo</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoTipoEquipoForm" ref="nuevoTipoEquipoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="tipo_equipo.nombre_tipo_equipo"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoTipoEquipo()" :disabled="!nuevoTipoEquipoForm" color="accent"
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
                nuevoTipoEquipoDialog: false,
                nuevoTipoEquipoForm: false,
                loading: false,

                tipo_equipo: {
                    nombre_tipo_equipo: '',
                },

                required: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeTipoEquipo(v) || 'Este tipo de equipo ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('tipo_equipo', {
                tipo_equipos: 'getTipoEquipo',
            }),
        },

        methods: {
            ...mapActions('tipo_equipo', ['saveTipoEquipo', 'fetchTipoEquipo']),

            triggerNuevoTipoEquipo: async function () {
                this.loading = true

                await this.saveTipoEquipo(this.tipo_equipo)
                await this.fetchTipoEquipo()

                this.loading = false

                this.tipo_equipo.nombre_tipo_equipo = ''
                this.$refs.nuevoTipoEquipoForm.resetValidation()
            },

            closeDialog: function () {
                this.nuevoTipoEquipoDialog = false
            },

            existeTipoEquipo: function (codigo) {
                if (this.tipo_equipos.some((tipo_equipo) => tipo_equipo.nombre_tipo_equipo == codigo)) {
                    return true
                } else return false
            },
        },
    }
</script>
