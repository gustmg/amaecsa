<template>
    <v-dialog v-model="active" width="95%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de salidas</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <vue-excel-editor v-model="salidasEquipo" ref="report" filter-row readonly>
                                <vue-excel-column
                                    width="64px"
                                    field="folioSalida"
                                    label="Folio de salida"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="160px"
                                    field="fecha"
                                    label="Fecha"
                                    type="datetime"
                                ></vue-excel-column>
                                <vue-excel-column width="160px" field="equipo" label="Equipo"></vue-excel-column>
                                <vue-excel-column width="80px" field="marca" label="Marca"></vue-excel-column>
                                <vue-excel-column width="80px" field="categoria" label="Categoría"></vue-excel-column>
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
                                <vue-excel-column
                                    width="160px"
                                    field="motivo"
                                    label="Motivo de salida"
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
                                    field="importe"
                                    label="Importe ($)"
                                    summary="sum"
                                ></vue-excel-column>
                            </vue-excel-editor>
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

            salidasEquipo: function () {
                var salidasEquipo = []

                if (this.salidas.length > 0) {
                    this.salidas.forEach((salida) => {
                        salida.equipos.forEach((equipo) => {
                            salidasEquipo.push({
                                folioSalida: salida.id_salida,
                                fecha: salida.created_at,
                                equipo: equipo.nombre_equipo,
                                marca: equipo.marca.nombre_marca,
                                categoria: equipo.categoria.nombre_categoria,
                                tipo: equipo.tipo_equipo.nombre_tipo_equipo,
                                codigoBarras: equipo.codigo_barras_equipo,
                                codigoProducto: equipo.codigo_producto_equipo,
                                motivo: equipo.pivot.comentario,
                                cantidad: equipo.pivot.cantidad,
                                unidadMedida: equipo.unidad_medida.nombre_unidad_medida,
                                costoUnitario: this.getCostoUnitario(equipo.id_equipo),
                                importe: parseFloat(
                                    this.getCostoUnitario(equipo.id_equipo) * equipo.pivot.cantidad
                                ).toFixed(2),
                            })
                        })
                    })
                }

                return salidasEquipo
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
        },
    }
</script>
