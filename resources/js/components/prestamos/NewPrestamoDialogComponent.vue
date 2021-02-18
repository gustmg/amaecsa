<template>
    <v-dialog v-model="nuevoEntradaDialog" width="100%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" color="accent">Agregar préstamo</v-btn>
        </template>
        <v-card>
            <v-card-title>Nuevo préstamo</v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="3">
                            <v-autocomplete
                                v-model="personalElegido"
                                label="Buscar personal por nombre o código..."
                                :items="personal"
                                item-value="id_personal"
                                item-text="nombre_personal"
                                rounded
                                filled
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="6" offset-md="3">
                            <v-autocomplete
                                label="Buscar equipo por nombre o código"
                                :items="equiposPrestamo"
                                return-object
                                item-value="id_equipo"
                                item-text="nombre_equipo"
                                rounded
                                filled
                                multiple
                            >
                                <template v-slot:item="{ item, on, attrs }">
                                    <v-list-item v-on:click="insertaPrestamo(item)" v-bind="attrs">
                                        <v-list-item-title>{{ item.nombre_equipo }}</v-list-item-title>
                                    </v-list-item>
                                </template>
                            </v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-data-table
                                :headers="equiposEntradaHeaders"
                                :items="equiposElegidos"
                                item-key="id_prestamo"
                                hide-default-footer
                            >
                                <template v-slot:item.desechable="{ item }">
                                    <div v-if="item.desechable">Desechable</div>
                                    <div v-else>Normal</div>
                                </template>
                                <template v-slot:item.cantidad="{ item }">
                                    <v-text-field
                                        label="Cantidad"
                                        v-model.number="item.cantidad"
                                        type="number"
                                        rounded
                                        filled
                                        dense
                                        :disabled="!item.desechable"
                                        class="mt-6"
                                    ></v-text-field>
                                </template>
                                <template v-slot:item.id_destino="{ item }">
                                    <v-select
                                        v-model="item.id_destino"
                                        :items="destinos"
                                        item-value="id_destino"
                                        item-text="nombre_destino"
                                        rounded
                                        filled
                                        class="mt-6"
                                        label="Elige un destino"
                                    ></v-select>
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
                <v-spacer></v-spacer>
                <v-btn text v-on:click="closeDialog()">Cancelar</v-btn>
                <v-btn v-on:click="triggerNuevoEntrada()" :disabled="!validPrestamo">Registrar préstamo</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                nuevoEntradaDialog: false,
                nuevoEntradaForm: false,
                loading: false,

                personalElegido: null,

                equiposEntradaHeaders: [
                    { text: 'Código de barras', value: 'codigo_barras_equipo' },
                    { text: 'Equipo', value: 'nombre_equipo' },
                    { text: 'Marca', value: 'marca_equipo' },
                    { text: 'Categoria', value: 'categoria_equipo' },
                    { text: 'Unidad de medida', value: 'unidad_medida_equipo' },
                    { text: 'Tipo de equipo', value: 'desechable' },
                    { text: 'Cantidad', value: 'cantidad' },
                    { text: 'Destino', value: 'id_destino' },
                    { text: 'Eliminar', value: 'eliminar' },
                ],

                equiposElegidos: [],

                required: [v => !!v || 'Este campo es requerido.'],
            }
        },

        computed: {
            ...mapGetters('equipo', {
                equiposPrestamo: 'getEquiposPrestamo',
            }),

            ...mapGetters('personal', {
                personal: 'getPersonal',
            }),

            ...mapGetters('destino', {
                destinos: 'getDestinos',
            }),

            costoTotal: function() {
                var total = 0
                this.equiposElegidos.forEach(equipo => {
                    total = total + +(equipo.costo_unitario * equipo.cantidad).toFixed(2)
                })

                return total.toFixed(2)
            },

            validPrestamo: function() {
                if (this.personalElegido) {
                    if (this.equiposElegidos.length > 0) {
                        if (
                            this.equiposElegidos.some(equipo => equipo.cantidad < 1) ||
                            this.equiposElegidos.some(equipo => !equipo.id_destino) ||
                            this.equiposElegidos.some(equipo => equipo.cantidad > equipo.stock_equipo)
                        ) {
                            return false
                        } else return true
                    } else return false
                } else return false
            },
        },

        methods: {
            ...mapActions('prestamo', ['savePrestamo', 'fetchPrestamos']),

            triggerNuevoEntrada: async function() {
                this.loading = true

                var prestamos = []
                this.equiposElegidos.forEach(equipo => {
                    prestamos.push({
                        id_personal: this.personalElegido,
                        id_equipo: equipo.id_equipo,
                        id_destino: equipo.id_destino,
                        cantidad: equipo.cantidad,
                        desechable: equipo.desechable,
                    })
                })

                await this.savePrestamo({
                    prestamos: prestamos,
                })

                window.location.reload()
            },

            closeDialog: function() {
                this.equiposElegidos = []
                this.nuevoEntradaDialog = false
            },

            insertaPrestamo: function(equipo) {
                this.equiposElegidos.push({
                    id_prestamo: this.equiposElegidos.length + 1,
                    id_equipo: equipo.id_equipo,
                    nombre_equipo: equipo.nombre_equipo,
                    codigo_barras_equipo: equipo.codigo_barras_equipo,
                    desechable: equipo.desechable,
                    cantidad: 1,
                    stock_equipo: equipo.stock_equipo,
                    marca_equipo: equipo.marca_equipo,
                    categoria_equipo: equipo.categoria_equipo,
                    unidad_medida_equipo: equipo.unidad_medida_equipo,
                })
            },

            quitaEntrada: function(item) {
                var index = this.equiposElegidos.indexOf(item)
                this.equiposElegidos.splice(index, 1)
            },
        },
    }
</script>
