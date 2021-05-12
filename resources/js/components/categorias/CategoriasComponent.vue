<template>
    <v-container fluid>
        <v-row align="center">
            <v-col cols="4">
                <div class="text-h6">Catálogo de categorías ({{ categorias.length }} registradas)</div>
            </v-col>
            <v-col cols="4" align="right">
                <new-categoria-dialog-component></new-categoria-dialog-component>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="searchCategoria"
                    label="Buscar categoria por nombre o código"
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
                        :headers="categoriaHeaders"
                        :items="categorias"
                        :search="searchCategoria"
                        item-key="id_categoria"
                    >
                        <template v-slot:item.equipo_categoria="{ item }">
                            {{ getProductosTotal(item.id_categoria) }}
                        </template>
                        <template v-slot:item.opciones="{ item }">
                            <edit-categoria-dialog-component :categoria="item"></edit-categoria-dialog-component>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        async mounted() {
            await this.fetchCategorias()
            await this.fetchEquipos()
        },

        data() {
            return {
                categoriaHeaders: [
                    { text: 'Código', value: 'codigo_categoria', width: '20%' },
                    { text: 'Categoría', value: 'nombre_categoria', width: '30%' },
                    { text: 'Productos registrados', value: 'equipo_categoria', width: '30%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchCategoria: '',
            }
        },

        computed: {
            ...mapGetters('categoria', {
                categorias: 'getCategorias',
            }),

            ...mapGetters('equipo', {
                equipos: 'getEquipos',
            }),
        },

        methods: {
            ...mapActions('categoria', ['fetchCategorias']),
            ...mapActions('equipo', ['fetchEquipos']),

            getProductosTotal: function (id_categoria) {
                return this.equipos.filter((equipo) => {
                    return equipo.id_categoria_equipo == id_categoria
                }).length
            },
        },
    }
</script>
