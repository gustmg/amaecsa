<template>
    <v-app-bar app clipped-left color="primary">
        <v-app-bar-nav-icon @click.stop="SHOW_DRAWER()"></v-app-bar-nav-icon>
        <v-toolbar-title class="font-weight-black white--text">AMAECSA</v-toolbar-title>
    </v-app-bar>
</template>
<script>
    import { mapMutations } from 'vuex'

    export default {
        methods: {
            ...mapMutations(['SHOW_DRAWER']),
        },
    }
</script>
