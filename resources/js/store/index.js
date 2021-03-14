import personal from './modules/Personal'
import destino from './modules/Destino'
import marca from './modules/Marca'
import categoria from './modules/Categoria'
import equipo from './modules/Equipo'
import unidad_medida from './modules/UnidadMedida'
import entrada from './modules/Entrada'
import salida from './modules/Salida'
import prestamo from './modules/Prestamo'

export default {
    state: {
        drawer: false,
    },

    getters: {
        getDrawer(state) {
            return state.drawer
        },
    },

    mutations: {
        SHOW_DRAWER(state) {
            console.log('Mostrando')
            state.drawer = !state.drawer
        },
        HIDE_DRAWER(state) {
            console.log('Ocultando')
            state.drawer = !state.drawer
        },
    },

    modules: {
        personal,
        destino,
        marca,
        categoria,
        equipo,
        unidad_medida,
        entrada,
        salida,
        prestamo,
    },
}
