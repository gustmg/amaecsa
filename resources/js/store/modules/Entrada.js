export default {
    namespaced: true,

    state: {
        entradas: [],
    },

    getters: {
        getEntradas: state => {
            return state.entradas
        },
    },

    mutations: {
        SET_ENTRADAS(state, entradas) {
            state.entradas = entradas
        },
    },

    actions: {
        fetchEntradas: async function({ commit }) {
            await axios
                .post('fetchEntradas')
                .then(response => {
                    commit('SET_ENTRADAS', response.data.entradas)
                })
                .catch(error => console.log(error))
        },

        saveEntrada: async function({ commit }, entrada) {
            await axios
                .post('entradas', entrada)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateEntrada: async function({ commit }, entrada) {
            await axios
                .put('entradas/' + entrada.id_entrada, entrada)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
