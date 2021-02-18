export default {
    namespaced: true,

    state: {
        marcas: [],
    },

    getters: {
        getMarcas: state => {
            return state.marcas
        },
    },

    mutations: {
        SET_MARCAS(state, marcas) {
            state.marcas = marcas
        },
    },

    actions: {
        fetchMarcas: async function({ commit }) {
            await axios
                .post('fetchMarcas')
                .then(response => {
                    commit('SET_MARCAS', response.data.marcas)
                })
                .catch(error => console.log(error))
        },

        saveMarca: async function({ commit }, marca) {
            await axios
                .post('marcas', marca)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateMarca: async function({ commit }, marca) {
            await axios
                .put('marcas/' + marca.id_marca, marca)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
