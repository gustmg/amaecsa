<template>
    <v-dialog v-model="nuevoPersonalDialog" width="480">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Editar</v-btn>
        </template>
        <v-card>
            <v-card-title>Editar personal</v-card-title>
            <v-card-text>
                <v-form v-model="nuevoPersonalForm" ref="nuevoPersonalForm">
                    <v-text-field
                        label="Nombre"
                        rounded
                        filled
                        v-model="editPersonal.nombre_personal"
                        :rules="required"
                        autofocus
                    ></v-text-field>
                    <v-text-field
                        label="Código"
                        rounded
                        filled
                        v-model="editPersonal.codigo_personal"
                        :rules="codigoRules"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoPersonal()" :disabled="!nuevoPersonalForm" color="accent">Editar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            personal: {
                type: Object,
            },
        },

        data() {
            return {
                nuevoPersonalDialog: false,
                nuevoPersonalForm: false,
                loading: false,

                editPersonal: _.cloneDeep(this.personal),

                required: [v => !!v || 'Este campo es requerido.'],
                codigoRules: [
                    v => !!v || 'Este campo es requerido.',
                    v => !this.existeCodigoPersonal(v) || 'Este código ya está registrado.',
                ],
            }
        },

        computed: {
            ...mapGetters('personal', {
                listaPersonal: 'getPersonal',
            }),
        },

        methods: {
            ...mapActions('personal', ['updatePersonal', 'fetchPersonal']),

            triggerNuevoPersonal: async function() {
                this.loading = true

                await this.updatePersonal(this.editPersonal)
                await this.fetchPersonal()

                this.loading = false

                this.closeDialog()
            },

            closeDialog: function() {
                this.$refs.nuevoPersonalForm.resetValidation()
                this.nuevoPersonalDialog = false
            },

            existeCodigoPersonal: function(codigo) {
                if (this.personal.codigo_personal == codigo) {
                    return false
                } else {
                    if (this.listaPersonal.some(personal => personal.codigo_personal == codigo)) {
                        return true
                    } else return false
                }
            },
        },
    }
</script>
