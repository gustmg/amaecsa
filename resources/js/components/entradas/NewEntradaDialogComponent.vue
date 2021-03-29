<template>
    <v-dialog v-model="nuevoEntradaDialog" width="100%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar entrada</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo entrada</v-card-title>
            <v-card-text>
                <v-container>
                    <v-row justify="center">
                        <v-col cols="5">
                            <v-autocomplete
                                v-model="equiposElegidos"
                                label="Buscar equipo por nombre o código"
                                :items="equiposEntrada"
                                return-object
                                item-text="nombre_equipo"
                                rounded
                                filled
                                multiple
                                hide-selected
                            ></v-autocomplete>
                            <v-text-field
                                label="Insertar equipo por código"
                                v-model="buscadorCodigo"
                                v-on:keyup.enter="agregaEquipo($event)"
                                rounded
                                filled
                                append-icon="mdi-barcode"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-data-table
                                :headers="equiposEntradaHeaders"
                                :items="equiposElegidos"
                                item-key="id_equipo"
                                hide-default-footer
                            >
                                <template v-slot:item.cantidad="{ item }">
                                    <v-text-field
                                        v-model.number="item.cantidad"
                                        type="number"
                                        label="Cantidad"
                                        rounded
                                        filled
                                        :rules="required"
                                    ></v-text-field>
                                </template>
                                <template v-slot:item.costo_unitario="{ item }">
                                    <v-text-field
                                        v-model.number="item.costo_unitario"
                                        type="number"
                                        label="Costo unitario"
                                        rounded
                                        filled
                                        prepend-icon="mdi-currency-usd"
                                        :rules="required"
                                    ></v-text-field>
                                </template>
                                <template v-slot:item.subtotal="{ item }">
                                    <v-text-field
                                        :value="(item.costo_unitario * item.cantidad).toFixed(2)"
                                        type="number"
                                        label="Subtotal"
                                        rounded
                                        filled
                                        prepend-icon="mdi-currency-usd"
                                        readonly
                                    ></v-text-field>
                                </template>
                                <template v-slot:item.eliminar="{ item }">
                                    <v-btn icon v-on:click="quitaEntrada(item)"
                                        ><v-icon color="error">mdi-minus-circle-outline</v-icon></v-btn
                                    >
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="2" offset-md="10">
                            <v-text-field
                                label="Costo total"
                                readonly
                                rounded
                                filled
                                :value="costoTotal"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoEntrada()" :disabled="!validEntrada" color="accent"
                    >Registrar entrada</v-btn
                >
            </v-card-actions>
        </v-card>
        <v-snackbar v-model="codigoNoEncontrado"
            >El código ingresado no ha sido encontrado en la lista de equipos.</v-snackbar
        >
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                nuevoEntradaDialog: false,
                entradaForm: false,
                loading: false,

                equiposEntradaHeaders: [
                    { text: 'Eliminar', value: 'eliminar' },
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Equipo', value: 'nombre_equipo' },
                    { text: 'Marca', value: 'marca_equipo' },
                    { text: 'Categoria', value: 'categoria_equipo' },
                    { text: 'Unidad de medida', value: 'unidad_medida_equipo' },
                    { text: 'Cantidad', value: 'cantidad' },
                    { text: 'Costo unitario', value: 'costo_unitario' },
                    { text: 'Subtotal', value: 'subtotal' },
                ],

                equiposElegidos: [],

                required: [v => !!v || 'Este campo es requerido.'],

                buscadorCodigo: '',
                codigoNoEncontrado: false,
            }
        },

        computed: {
            ...mapGetters('equipo', {
                equiposEntrada: 'getEquiposEntrada',
            }),

            costoTotal: function() {
                var total = 0
                this.equiposElegidos.forEach(equipo => {
                    total = total + +(equipo.costo_unitario * equipo.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            validEntrada: function() {
                if (this.equiposElegidos.length > 0) {
                    if (this.equiposElegidos.some(equipo => equipo.cantidad == 0)) {
                        return false
                    } else return true
                } else {
                    return false
                }
            },
        },

        methods: {
            ...mapActions('entrada', ['saveEntrada', 'fetchEntradas']),

            triggerNuevoEntrada: async function() {
                this.loading = true

                await this.saveEntrada({
                    entradas: this.equiposElegidos,
                })

                window.location.reload()
            },

            closeDialog: function() {
                this.equiposElegidos = []
                this.nuevoEntradaDialog = false
            },

            quitaEntrada: function(item) {
                var index = this.equiposElegidos.indexOf(item)
                this.equiposElegidos.splice(index, 1)
            },

            agregaEquipo(event) {
                if (this.equiposEntrada.some(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)) {
                    if (!this.equiposElegidos.some(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)) {
                        var x = this.equiposEntrada.find(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)
                        this.equiposElegidos.push(x)
                    }
                } else {
                    this.codigoNoEncontrado = true
                }
                this.buscadorCodigo = ''
            },
        },
    }
</script>
