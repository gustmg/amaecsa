<template>
    <v-dialog v-model="nuevoTipoEquipoDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Ediatr tipo de equipo</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoTipoEquipoForm" ref="nuevoTipoEquipoForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editTipoEquipo.nombre_tipo_equipo"
                        :rules="required"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoTipoEquipo()" :disabled="!nuevoTipoEquipoForm" color="accent"
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
            tipoEquipo: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoTipoEquipoDialog: false,
                nuevoTipoEquipoForm: false,
                loading: false,

                editTipoEquipo: _.cloneDeep(this.tipoEquipo),

                required: [
                    (v) => !!v || 'Este campo es requerido.',
                    (v) => !this.existeTipoEquipo(v) || 'Este destino ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('tipo_equipo', {
                tipo_equipos: 'getTipoEquipo',
            }),
        },

        methods: {
            ...mapActions('tipo_equipo', ['updateTipoEquipo', 'fetchTipoEquipo']),

            triggerNuevoTipoEquipo: async function () {
                this.loading = true

                await this.updateTipoEquipo(this.editTipoEquipo)
                await this.fetchTipoEquipo()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function () {
                this.$refs.nuevoTipoEquipoForm.resetValidation()
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
