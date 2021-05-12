<template>
    <v-dialog v-model="active" width="95%">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="accent" class="mx-2" v-on="on" v-bind="attrs">Reportes</v-btn>
        </template>
        <v-card>
            <v-card-title>Reporte de entradas</v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col align="center">
                            <vue-excel-editor v-model="entradasEquipo" ref="report" filter-row readonly>
                                <vue-excel-column
                                    width="64px"
                                    field="folioEntrada"
                                    label="Folio de entrada"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="80px"
                                    field="numeroVale"
                                    label="Número de vale"
                                ></vue-excel-column>
                                <vue-excel-column
                                    width="160px"
                                    field="fecha"
                                    label="Fecha"
                                    type="datetime"
                                ></vue-excel-column>
                                <vue-excel-column width="80px" field="destino" label="Destino"></vue-excel-column>
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
            ...mapGetters('entrada', {
                entradas: 'getEntradas',
            }),

            entradasEquipo: function () {
                var entradasEquipo = []

                if (this.entradas.length > 0) {
                    this.entradas.forEach((entrada) => {
                        entrada.equipos.forEach((equipo) => {
                            entradasEquipo.push({
                                folioEntrada: entrada.id_entrada,
                                numeroVale: entrada.numero_vale_entrada,
                                fecha: entrada.created_at,
                                destino: entrada.destino.nombre_destino,
                                equipo: equipo.nombre_equipo,
                                marca: equipo.marca.nombre_marca,
                                categoria: equipo.categoria.nombre_categoria,
                                tipo: equipo.tipo_equipo.nombre_tipo_equipo,
                                codigoBarras: equipo.codigo_barras_equipo,
                                codigoProducto: equipo.codigo_producto_equipo,
                                cantidad: equipo.pivot.cantidad,
                                unidadMedida: equipo.unidad_medida.nombre_unidad_medida,
                                costoUnitario: parseFloat(equipo.pivot.costo_unitario).toFixed(2),
                                importe: parseFloat(equipo.pivot.costo_unitario * equipo.pivot.cantidad).toFixed(2),
                            })
                        })
                    })
                }

                return entradasEquipo
            },
        },

        methods: {
            descargaReporte: function () {
                const format = 'xlsx'
                const exportSelectedOnly = false
                const filename = 'test'
                this.$refs.report.exportTable(format, exportSelectedOnly, filename)
            },
        },
    }
</script>
