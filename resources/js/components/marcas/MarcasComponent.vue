<template>
    <v-container>
        <v-row align="center">
            <v-col cols="4">
                <div class="text-h6">Catálogo de marcas ({{ marcas.length }} registradas)</div>
            </v-col>
            <v-col cols="4" align="right">
                <new-marca-dialog-component></new-marca-dialog-component>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="searchMarca"
                    label="Buscar marca por nombre"
                    append-icon="mdi-magnify"
                    solo
                    class="mt-6"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-data-table :headers="marcaHeaders" :items="marcas" :search="searchMarca" item-key="id_marca">
                    <template v-slot:item.equipo_marca="{ item }">
                        {{ getProductosTotal(item.id_marca) }}
                    </template>
                    <template v-slot:item.opciones="{ item }">
                        <edit-marca-dialog-component :marca="item"></edit-marca-dialog-component>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        async mounted() {
            await this.fetchMarcas()
            await this.fetchEquipos()
        },

        data() {
            return {
                marcaHeaders: [
                    { text: 'Nombre', value: 'nombre_marca', width: '50%' },
                    { text: 'Productos registrados', value: 'equipo_marca', width: '30%' },
                    { text: 'Opciones', value: 'opciones', width: '20%' },
                ],

                searchMarca: '',
            }
        },

        computed: {
            ...mapGetters('marca', {
                marcas: 'getMarcas',
            }),

            ...mapGetters('equipo', {
                equipos: 'getEquipos',
            }),
        },

        methods: {
            ...mapActions('marca', ['fetchMarcas']),
            ...mapActions('equipo', ['fetchEquipos']),

            getProductosTotal: function(id_marca) {
                return this.equipos.filter(equipo => {
                    return equipo.id_marca_equipo == id_marca
                }).length
            },
        },
    }
</script>
