<template>
    <v-container fluid>
        <v-row align="center">
            <v-col cols="4">
                <div class="text-h6">Tipos de equipo ({{ tipoEquipo.length }} registrados)</div>
            </v-col>
            <v-col cols="4" align="right">
                <new-tipo-equipo-dialog-component></new-tipo-equipo-dialog-component>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="searchTipoEquipo"
                    label="Buscar"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="headers"
                        :items="tipoEquipo"
                        :search="searchTipoEquipo"
                        item-key="id_tipo_equipo"
                    >
                        <template v-slot:item.total_equipos="{ item }">
                            {{ getProductosTotal(item.id_tipo_equipo) }}
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <edit-tipo-equipo-dialog-component :tipo-equipo="item"></edit-tipo-equipo-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'
    import EditTipoEquipoDialogComponent from './EditTipoEquipoDialogComponent.vue'
    import NewTipoEquipoDialogComponent from './NewTipoEquipoDialogComponent.vue'

    export default {
        components: {
            NewTipoEquipoDialogComponent,
            EditTipoEquipoDialogComponent,
        },

        async mounted() {
            await this.fetchTipoEquipo()
            await this.fetchEquipos()
        },

        data() {
            return {
                headers: [
                    { text: 'Tipo de equipo', value: 'nombre_tipo_equipo', width: '30%' },
                    { text: 'Productos registrados', value: 'total_equipos', width: '30%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchTipoEquipo: '',
            }
        },

        computed: {
            ...mapGetters('tipo_equipo', {
                tipoEquipo: 'getTipoEquipo',
            }),

            ...mapGetters('equipo', {
                equipos: 'getEquipos',
            }),
        },

        methods: {
            ...mapActions('tipo_equipo', ['fetchTipoEquipo']),
            ...mapActions('equipo', ['fetchEquipos']),

            getProductosTotal: function (id_tipo_equipo) {
                return this.equipos.filter((equipo) => {
                    return equipo.id_tipo_equipo == id_tipo_equipo
                }).length
            },
        },
    }
</script>
