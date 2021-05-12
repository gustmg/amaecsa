<template>
    <v-container>
        <v-row align="center">
            <v-col cols="4">
                <div class="text-h6">Catálogo de unidades de medida ({{ unidadesMedida.length }} registradas)</div>
            </v-col>
            <v-col cols="4" align="right">
                <new-unidad-medida-dialog-component></new-unidad-medida-dialog-component>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="searchUnidadMedida"
                    label="Buscar unidad de medida"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="unidadesMedida"
                            :search="searchUnidadMedida"
                            item-key="id_unidad_medida"
                        >
                            <template v-slot:item.opciones="{ item }">
                                <edit-unidad-medida-dialog-component
                                    :unidad-medida="item"
                                ></edit-unidad-medida-dialog-component>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import NewUnidadMedidaDialogComponent from '../unidades_medida/NewUnidadMedidaDialogComponent'
    import EditUnidadMedidaDialogComponent from './EditUnidadMedidaDialogComponent.vue'

    export default {
        components: {
            NewUnidadMedidaDialogComponent,
            EditUnidadMedidaDialogComponent,
        },

        async mounted() {
            await this.fetchUnidadesMedida()
        },

        data() {
            return {
                headers: [
                    { text: 'Nombre', value: 'nombre_unidad_medida', width: '50%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchUnidadMedida: '',
            }
        },

        computed: {
            ...mapGetters('unidad_medida', {
                unidadesMedida: 'getUnidadesMedida',
            }),
        },

        methods: {
            ...mapActions('unidad_medida', ['fetchUnidadesMedida']),
        },
    }
</script>
