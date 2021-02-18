export default {
    namespaced: true,

    state: {
        destinos: [],
    },

    getters: {
        getDestinos: state => {
            return state.destinos
        },
    },

    mutations: {
        SET_DESTINOS(state, destinos) {
            state.destinos = destinos
        },
    },

    actions: {
        fetchDestinos: async function({ commit }) {
            await axios
                .post('fetchDestinos')
                .then(response => {
                    commit('SET_DESTINOS', response.data.destinos)
                })
                .catch(error => console.log(error))
        },

        saveDestino: async function({ commit }, destino) {
            await axios
                .post('destinos', destino)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateDestino: async function({ commit }, destino) {
            await axios
                .put('destinos/' + destino.id_destino, destino)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
