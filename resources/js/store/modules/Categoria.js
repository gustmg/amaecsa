export default {
    namespaced: true,

    state: {
        categorias: [],
    },

    getters: {
        getCategorias: state => {
            return state.categorias
        },
    },

    mutations: {
        SET_CATEGORIAS(state, categorias) {
            state.categorias = categorias
        },
    },

    actions: {
        fetchCategorias: async function({ commit }) {
            await axios
                .post('fetchCategorias')
                .then(response => {
                    commit('SET_CATEGORIAS', response.data.categorias)
                })
                .catch(error => console.log(error))
        },

        saveCategoria: async function({ commit }, categoria) {
            await axios
                .post('categorias', categoria)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updateCategoria: async function({ commit }, categoria) {
            await axios
                .put('categorias/' + categoria.id_categoria, categoria)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
