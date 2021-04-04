export default {
    namespaced: true,

    state: {
        equipos: [],
    },

    getters: {
        getEquipos: state => {
            return state.equipos
        },

        getEquiposEntrada: state => {
            var equiposEntrada = []

            state.equipos.forEach(equipo => {
                equiposEntrada.push({
                    id_equipo: equipo.id_equipo,
                    codigo_barras_equipo: equipo.codigo_barras_equipo,
                    nombre_equipo: equipo.nombre_equipo,
                    marca_equipo: equipo.marca.nombre_marca,
                    categoria_equipo: equipo.categoria.nombre_categoria,
                    unidad_medida_equipo: equipo.unidad_medida.nombre_unidad_medida,
                    cantidad: 0,
                    costo_unitario: 0,
                })
            })

            return equiposEntrada
        },

        getEquiposSalida: state => {
            var equiposSalida = []

            state.equipos.forEach(equipo => {
                equiposSalida.push({
                    id_equipo: equipo.id_equipo,
                    codigo_barras_equipo: equipo.codigo_barras_equipo,
                    nombre_equipo: equipo.nombre_equipo,
                    stock_equipo: equipo.stock_equipo,
                    marca_equipo: equipo.marca.nombre_marca,
                    categoria_equipo: equipo.categoria.nombre_categoria,
                    unidad_medida_equipo: equipo.unidad_medida.nombre_unidad_medida,
                    cantidad: 0,
                    comentario: '',
                })
            })

            return equiposSalida
        },

        getEquiposPrestamo: state => {
            var equiposPrestamo = []

            state.equipos.forEach((equipo, index) => {
                if (equipo.stock_equipo >= 1) {
                    equiposPrestamo.push({
                        id_equipo: equipo.id_equipo,
                        nombre_equipo: equipo.nombre_equipo,
                        codigo_barras_equipo: equipo.codigo_barras_equipo,
                        desechable: equipo.desechable,
                        stock_equipo: equipo.stock_equipo,
                        marca_equipo: equipo.marca.nombre_marca,
                        categoria_equipo: equipo.categoria.nombre_categoria,
                        unidad_medida_equipo: equipo.unidad_medida.nombre_unidad_medida,
                    })
                }
            })

            return equiposPrestamo
        },
    },

    mutations: {
        SET_EQUIPOS(state, equipos) {
            state.equipos = equipos
        },
    },

    actions: {
        fetchEquipos: async function({ commit }) {
            await axios
                .post('fetchEquipos')
                .then(response => {
                    commit('SET_EQUIPOS', response.data.equipos)
                })
                .catch(error => console.log(error))
        },

        saveEquipo: async function({ commit }, equipo) {
            await axios
                .post('equipos', equipo)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateEquipo: async function({ commit }, equipo) {
            await axios
                .put('equipos/' + equipo.id_equipo, equipo)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
