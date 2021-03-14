<template>
    <v-navigation-drawer expand-on-hover app color="secondary">
        <v-list-item link href="/home" class="px-2">
            <v-list-item-avatar>
                <v-icon>mdi-home</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Inicio</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/personal" class="px-2">
            <v-list-item-avatar>
                <v-icon>mdi-account-group</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Personal</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/destinos" class="px-2">
            <v-list-item-avatar>
                <v-icon>mdi-map-marker</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Destinos</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/prestamos" class="px-2">
            <v-list-item-avatar>
                <v-icon>mdi-ticket-account</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Préstamos</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list>
            <v-list-item-group>
                <v-list-item link href="/equipos" class="px-2">
                    <v-list-item-avatar>
                        <v-icon>mdi-hammer-wrench</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Equipos</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/marcas" class="px-2">
                    <v-list-item-avatar>
                        <v-icon>mdi-tag</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Marcas</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/categorias" class="px-2">
                    <v-list-item-avatar>
                        <v-icon>mdi-format-list-bulleted-type</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Categorías</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <v-list>
            <v-list-item-group>
                <v-list-item link href="/entradas" class="px-2">
                    <v-list-item-avatar>
                        <v-icon>mdi-clipboard-arrow-down</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Entradas de inventario</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/salidas" class="px-2">
                    <v-list-item-avatar>
                        <v-icon>mdi-clipboard-arrow-up</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Salidas de inventario</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <template v-slot:append>
            <v-list-item link href="/logout" class="px-2">
                <v-list-item-avatar>
                    <v-icon>mdi-exit-to-app</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>Cerrar sesión</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </template>
    </v-navigation-drawer>
</template>
<script>
    import { mapGetters, mapMutations } from 'vuex'
    export default {
        computed: {
            ...mapGetters({
                drawer: 'getDrawer',
            }),
        },

        methods: {
            ...mapMutations(['HIDE_DRAWER']),
        },
    }
</script>
