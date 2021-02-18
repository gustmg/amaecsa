<template>
    <v-navigation-drawer app clipped color="secondary">
        <v-list-item link href="/home">
            <v-list-item-avatar>
                <v-icon>mdi-home</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Inicio</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/personal">
            <v-list-item-avatar>
                <v-icon>mdi-account-group</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Personal</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/destinos">
            <v-list-item-avatar>
                <v-icon>mdi-map-marker</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Destinos</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/prestamos">
            <v-list-item-avatar>
                <v-icon>mdi-ticket-account</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>Préstamos</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list>
            <v-list-item-group>
                <v-subheader>Equipo</v-subheader>
                <v-list-item link href="/equipos">
                    <v-list-item-avatar>
                        <v-icon>mdi-hammer-wrench</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Equipo</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/marcas">
                    <v-list-item-avatar>
                        <v-icon>mdi-tag</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Marcas</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/categorias">
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
                <v-subheader>Inventario</v-subheader>
                <v-list-item link href="/entradas">
                    <v-list-item-avatar>
                        <v-icon>mdi-clipboard-arrow-down</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Entradas</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link href="/salidas">
                    <v-list-item-avatar>
                        <v-icon>mdi-clipboard-arrow-up</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Salidas</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <template v-slot:append>
            <v-list-item link href="/logout">
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
    export default {}
</script>
