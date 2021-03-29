<template>
    <v-dialog v-model="nuevoSalidaDialog" width="100%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar salida</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo salida</v-card-title>
            <v-card-text>
                <v-container>
                    <v-row justify="center">
                        <v-col cols="5">
                            <v-autocomplete
                                v-model="equiposElegidos"
                                label="Buscar equipo por nombre o código"
                                :items="equiposSalida"
                                return-object
                                item-text="nombre_equipo"
                                rounded
                                filled
                                multiple
                                :filter="filterEquipo"
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
                                :headers="equiposSalidaHeaders"
                                :items="equiposElegidos"
                                item-key="id_equipo"
                                hide-default-footer
                            >
                                <template v-slot:item.cantidad="{ item }">
                                    <v-text-field
                                        v-model.number="item.cantidad"
                                        type="number"
                                        label="Cantidad"
                                        :hint="'Actualmente en stock: ' + item.stock_equipo"
                                        rounded
                                        filled
                                        :max="item.stock_equipo"
                                        :rules="required"
                                    ></v-text-field>
                                </template>
                                <template v-slot:item.comentario="{ item }">
                                    <v-text-field
                                        v-model="item.comentario"
                                        label="Comentario"
                                        rounded
                                        filled
                                        :rules="required"
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
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoSalida()" :disabled="!validSalida" color="accent"
                    >Registrar salida</v-btn
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
                nuevoSalidaDialog: false,
                nuevoSalidaForm: false,
                loading: false,

                equiposSalidaHeaders: [
                    { text: 'Eliminar', value: 'eliminar' },
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Equipo', value: 'nombre_equipo' },
                    { text: 'Marca', value: 'marca_equipo' },
                    { text: 'Categoria', value: 'categoria_equipo' },
                    { text: 'Unidad de medida', value: 'unidad_medida_equipo' },
                    { text: 'Cantidad', value: 'cantidad' },
                    { text: 'Comentario', value: 'comentario' },
                ],

                equiposElegidos: [],

                required: [v => !!v || 'Este campo es requerido.'],

                buscadorCodigo: '',
                codigoNoEncontrado: false,
            }
        },

        computed: {
            ...mapGetters('equipo', {
                equiposSalida: 'getEquiposSalida',
            }),

            costoTotal: function() {
                var total = 0
                this.equiposElegidos.forEach(equipo => {
                    total = total + +(equipo.costo_unitario * equipo.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            validSalida: function() {
                if (this.equiposElegidos.length > 0) {
                    if (
                        this.equiposElegidos.some(equipo => equipo.cantidad == 0) ||
                        this.equiposElegidos.some(equipo => equipo.comentario.length == 0) ||
                        this.equiposElegidos.some(equipo => equipo.cantidad > equipo.stock_equipo)
                    ) {
                        return false
                    } else return true
                } else {
                    return false
                }
            },
        },

        methods: {
            ...mapActions('salida', ['saveSalida', 'fetchSalidas']),

            triggerNuevoSalida: async function() {
                this.loading = true

                await this.saveSalida({
                    salidas: this.equiposElegidos,
                })

                window.location.reload()
            },

            closeDialog: function() {
                this.equiposElegidos = []
                this.nuevoSalidaDialog = false
            },

            quitaEntrada: function(item) {
                var index = this.equiposElegidos.indexOf(item)
                this.equiposElegidos.splice(index, 1)
            },

            filterEquipo(item, queryText, itemText) {
                return (
                    item.nombre_equipo.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1 ||
                    item.codigo_barras_equipo.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
                )
            },

            agregaEquipo(event) {
                if (this.equiposSalida.some(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)) {
                    if (!this.equiposElegidos.some(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)) {
                        var x = this.equiposSalida.find(equipo => equipo.codigo_barras_equipo == this.buscadorCodigo)
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
