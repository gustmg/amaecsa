export default {
    namespaced: true,

    state: {
        salidas: [],
    },

    getters: {
        getSalidas: state => {
            return state.salidas
        },
    },

    mutations: {
        SET_SALIDAS(state, salidas) {
            state.salidas = salidas
        },
    },

    actions: {
        fetchSalidas: async function({ commit }) {
            await axios
                .post('fetchSalidas')
                .then(response => {
                    commit('SET_SALIDAS', response.data.salidas)
                })
                .catch(error => console.log(error))
        },

        saveSalida: async function({ commit }, salida) {
            await axios
                .post('salidas', salida)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateSalida: async function({ commit }, salida) {
            await axios
                .put('salidas/' + salida.id_salida, salida)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
