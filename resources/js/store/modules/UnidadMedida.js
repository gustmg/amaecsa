export default {
    namespaced: true,

    state: {
        unidadesMedida: [],
    },

    getters: {
        getUnidadesMedida: state => {
            return state.unidadesMedida
        },
    },

    mutations: {
        SET_UNIDADES_MEDIDA(state, unidadesMedida) {
            state.unidadesMedida = unidadesMedida
        },
    },

    actions: {
        fetchUnidadesMedida: async function({ commit }) {
            await axios
                .post('fetchUnidadesMedida')
                .then(response => {
                    commit('SET_UNIDADES_MEDIDA', response.data.unidades_medida)
                })
                .catch(error => console.log(error))
        },
    },
}
