export default {
    namespaced: true,

    state: {
        tipoEquipo: [],
    },

    getters: {
        getTipoEquipo: (state) => {
            return state.tipoEquipo
        },
    },

    mutations: {
        SET_TIPO_EQUIPO(state, tipoEquipo) {
            state.tipoEquipo = tipoEquipo
        },
    },

    actions: {
        fetchTipoEquipo: async function ({ commit }) {
            await axios
                .post('fetchTipoEquipo')
                .then((response) => {
                    commit('SET_TIPO_EQUIPO', response.data.tipo_equipo)
                })
                .catch((error) => console.log(error))
        },

        saveTipoEquipo: async function ({ commit }, tipoEquipo) {
            await axios
                .post('tipo_equipo', tipoEquipo)
                .then((response) => {
                    console.log('Ok')
                })
                .catch((error) => console.log(error))
        },

        updateTipoEquipo: async function ({ commit }, tipoEquipo) {
            await axios
                .put('tipo_equipo/' + tipoEquipo.id_tipo_equipo, tipoEquipo)
                .then((response) => {
                    console.log('Ok')
                })
                .catch((error) => console.log(error))
        },
    },
}
