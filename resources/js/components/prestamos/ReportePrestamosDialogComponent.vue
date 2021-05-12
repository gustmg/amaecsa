<template>
    <v-dialog v-model="active" width="95%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de préstamos</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <div id="printMe">
                                <vue-excel-editor v-model="prestamosDetalle" ref="report" filter-row readonly>
                                    <vue-excel-column
                                        width="64px"
                                        field="folioPrestamo"
                                        label="Folio de préstamo"
                                    ></vue-excel-column>
                                    <vue-excel-column
                                        width="160px"
                                        field="fechaEntrega"
                                        label="Fecha de entrega"
                                        type="datetime"
                                    ></vue-excel-column>
                                    <vue-excel-column
                                        width="160px"
                                        field="fechaRecepcion"
                                        label="Fecha de recepción"
                                        type="datetime"
                                    ></vue-excel-column>
                                    <vue-excel-column width="80px" field="recibido" label="Recibido"></vue-excel-column>
                                    <vue-excel-column
                                        width="160px"
                                        field="personal"
                                        label="Personal"
                                    ></vue-excel-column>
                                    <vue-excel-column width="160px" field="destino" label="Destino"></vue-excel-column>
                                    <vue-excel-column width="160px" field="equipo" label="Equipo"></vue-excel-column>
                                    <vue-excel-column width="80px" field="marca" label="Marca"></vue-excel-column>
                                    <vue-excel-column
                                        width="80px"
                                        field="categoria"
                                        label="Categoría"
                                    ></vue-excel-column>
                                    <vue-excel-column width="80px" field="tipo" label="Tipo"></vue-excel-column>
                                    <vue-excel-column
                                        width="160px"
                                        field="codigoBarras"
                                        label="Código de barras"
                                    ></vue-excel-column>
                                    <vue-excel-column
                                        width="160px"
                                        field="codigoProducto"
                                        label="Código de producto"
                                    ></vue-excel-column>
                                    <vue-excel-column width="64px" field="cantidad" label="Cant"></vue-excel-column>
                                    <vue-excel-column width="64px" field="unidadMedida" label="UM"></vue-excel-column>
                                    <vue-excel-column
                                        width="64px"
                                        field="costoUnitario"
                                        label="Costo unitario ($)"
                                    ></vue-excel-column>
                                    <vue-excel-column
                                        width="80px"
                                        field="valorPrestamo"
                                        label="Valor de préstamo ($)"
                                        summary="sum"
                                    ></vue-excel-column>
                                </vue-excel-editor>
                            </div>
                            <v-btn @click="descargaReporte()" class="mt-4" color="accent">Descargar</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                active: false,

                searchFechaMenu: false,
                date: [],
            }
        },

        computed: {
            ...mapGetters('salida', {
                salidas: 'getSalidas',
            }),

            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            ...mapGetters('prestamo', {
                prestamos: 'getPrestamos',
            }),

            prestamosDetalle: function () {
                var prestamos = []

                if (this.prestamos.length > 0) {
                    this.prestamos.forEach((prestamo) => {
                        prestamos.push({
                            folioPrestamo: prestamo.id_prestamo,
                            fechaEntrega: prestamo.created_at,
                            fechaRecepcion: this.getFechaRecibido(prestamo),
                            recibido: this.getPrestamoRecibido(prestamo),
                            personal: prestamo.personal.nombre_personal,
                            destino: prestamo.destino.nombre_destino,
                            equipo: prestamo.equipo.nombre_equipo,
                            marca: prestamo.equipo.marca.nombre_marca,
                            categoria: prestamo.equipo.categoria.nombre_categoria,
                            tipo: prestamo.equipo.tipo_equipo.nombre_tipo_equipo,
                            codigoBarras: prestamo.equipo.codigo_barras_equipo,
                            codigoProducto: prestamo.equipo.codigo_producto_equipo,
                            cantidad: prestamo.cantidad,
                            unidadMedida: prestamo.equipo.unidad_medida.nombre_unidad_medida,
                            costoUnitario: this.getCostoUnitario(prestamo.equipo.id_equipo),
                            valorPrestamo: parseFloat(
                                this.getCostoUnitario(prestamo.equipo.id_equipo) * prestamo.cantidad
                            ).toFixed(2),
                        })
                    })
                }

                return prestamos
            },
        },

        methods: {
            descargaReporte: function () {
                const format = 'xlsx'
                const exportSelectedOnly = false
                const filename = 'test'
                this.$refs.report.exportTable(format, exportSelectedOnly, filename)
            },

            getCostoUnitario(id_equipo) {
                var entradasPorEquipo = []
                var salidasPorEquipo = []
                var totalCantidad = 0
                var totalCantidadSalidas = 0
                var totalCosto = 0

                this.entradas.forEach((entrada) => {
                    entrada.equipos.forEach((equipo) => {
                        if (equipo.id_equipo == id_equipo) {
                            entradasPorEquipo.push(equipo)
                        }
                    })
                })

                this.salidas.forEach((salida) => {
                    salida.equipos.forEach((equipo) => {
                        if (equipo.id_equipo == id_equipo) {
                            salidasPorEquipo.push(equipo)
                        }
                    })
                })

                entradasPorEquipo.forEach((equipo) => {
                    totalCantidad = equipo.pivot.cantidad + totalCantidad
                    totalCosto += equipo.pivot.costo_unitario * equipo.pivot.cantidad
                })

                salidasPorEquipo.forEach((equipo) => {
                    totalCantidadSalidas = equipo.pivot.cantidad + totalCantidadSalidas
                })

                return (totalCosto / totalCantidad).toFixed(2)
            },

            getFechaRecibido: function (prestamo) {
                if (prestamo.equipo.desechable) {
                    return 'N/A'
                } else if (prestamo.created_at == prestamo.updated_at) {
                    return 'Pendiente de recibir'
                } else {
                    return prestamo.updated_at
                }
            },

            getPrestamoRecibido: function (prestamo) {
                if (prestamo.recibido) {
                    return 'SI'
                } else if (prestamo.recibido == 0) {
                    return 'NO'
                } else return 'N/A'
            },
        },
    }
</script>
