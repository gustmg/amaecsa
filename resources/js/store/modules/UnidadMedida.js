export default {
    namespaced: true,

    state: {
        unidadesMedida: [],
    },

    getters: {
        getUnidadesMedida: (state) => {
            return state.unidadesMedida
        },
    },

    mutations: {
        SET_UNIDADES_MEDIDA(state, unidadesMedida) {
            state.unidadesMedida = unidadesMedida
        },
    },

    actions: {
        fetchUnidadesMedida: async function ({ commit }) {
            await axios
                .post('fetchUnidadesMedida')
                .then((response) => {
                    commit('SET_UNIDADES_MEDIDA', response.data.unidades_medida)
                })
                .catch((error) => console.log(error))
        },

        saveUnidadMedida: async function ({ commit }, unidadMedida) {
            await axios
                .post('unidades_medida', unidadMedida)
                .then((response) => {
                    console.log('Ok')
                })
                .catch((error) => console.log(error))
        },

        updateUnidadMedida: async function ({ commit }, unidadMedida) {
            await axios
                .put('unidades_medida/' + unidadMedida.id_unidad_medida, unidadMedida)
                .then((response) => {
                    console.log('Ok')
                })
                .catch((error) => console.log(error))
        },
    },
}
