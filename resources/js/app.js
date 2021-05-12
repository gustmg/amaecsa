/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

import Vuex from 'vuex'
import Vuetify from 'vuetify'
import storeData from './store/index'
import VueApexCharts from 'vue-apexcharts'
import es from 'vuetify/lib/locale/es'
import Vue from 'vue'
import VueExcelEditor from 'vue-excel-editor'

import Print from 'vue-print-nb'
// Global instruction
Vue.use(Print)

Vue.use(Vuetify)
Vue.use(Vuex)
Vue.use(VueApexCharts)
Vue.use(VueExcelEditor)

Vue.component('apexchart', VueApexCharts)

const store = new Vuex.Store(storeData)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('login-component', require('./components/LoginComponent.vue').default)
Vue.component('home-component', require('./components/HomeComponent.vue').default)
Vue.component('app-header-component', require('./components/AppHeaderComponent.vue').default)
Vue.component('navigation-drawer-component', require('./components/NavigationDrawerComponent.vue').default)

Vue.component('personal-component', require('./components/personal/PersonalComponent.vue').default)
Vue.component('new-personal-dialog-component', require('./components/personal/NewPersonalDialogComponent.vue').default)
Vue.component(
    'edit-personal-dialog-component',
    require('./components/personal/EditPersonalDialogComponent.vue').default
)

Vue.component('destinos-component', require('./components/destinos/DestinosComponent.vue').default)
Vue.component('new-destino-dialog-component', require('./components/destinos/NewDestinoDialogComponent.vue').default)
Vue.component('edit-destino-dialog-component', require('./components/destinos/EditDestinoDialogComponent.vue').default)

Vue.component('marcas-component', require('./components/marcas/MarcasComponent.vue').default)
Vue.component('new-marca-dialog-component', require('./components/marcas/NewMarcaDialogComponent.vue').default)
Vue.component('edit-marca-dialog-component', require('./components/marcas/EditMarcaDialogComponent.vue').default)

Vue.component('categorias-component', require('./components/categorias/CategoriasComponent.vue').default)
Vue.component(
    'new-categoria-dialog-component',
    require('./components/categorias/NewCategoriaDialogComponent.vue').default
)
Vue.component(
    'edit-categoria-dialog-component',
    require('./components/categorias/EditCategoriaDialogComponent.vue').default
)

Vue.component('equipos-component', require('./components/equipos/EquiposComponent.vue').default)
Vue.component('new-equipo-dialog-component', require('./components/equipos/NewEquipoDialogComponent.vue').default)
Vue.component('edit-equipo-dialog-component', require('./components/equipos/EditEquipoDialogComponent.vue').default)

Vue.component('entradas-component', require('./components/entradas/EntradasComponent.vue').default)
Vue.component('new-entrada-dialog-component', require('./components/entradas/NewEntradaDialogComponent.vue').default)
Vue.component(
    'entrada-detalle-dialog-component',
    require('./components/entradas/EntradaDetalleDialogComponent.vue').default
)

Vue.component('salidas-component', require('./components/salidas/SalidasComponent.vue').default)
Vue.component('new-salida-dialog-component', require('./components/salidas/NewSalidaDialogComponent.vue').default)
Vue.component(
    'salida-detalle-dialog-component',
    require('./components/salidas/SalidaDetalleDialogComponent.vue').default
)

Vue.component('prestamos-component', require('./components/prestamos/PrestamosComponent.vue').default)
Vue.component('new-prestamo-dialog-component', require('./components/prestamos/NewPrestamoDialogComponent.vue').default)

Vue.component('unidades-medida-component', require('./components/unidades_medida/UnidadesMedidaComponent.vue').default)

Vue.component('tipo-equipo-component', require('./components/tipo_equipo/TipoEquipoComponent.vue').default)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: '#1565C0',
                    secondary: '#E3F2FD',
                    accent: '#FFAB40',
                },
            },
        },
        lang: {
            locales: { es },
            current: 'es',
        },
    }),
    store,
})
