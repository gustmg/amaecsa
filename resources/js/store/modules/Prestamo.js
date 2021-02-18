export default {
    namespaced: true,

    state: {
        prestamos: [],
    },

    getters: {
        getPrestamos: state => {
            return state.prestamos
        },
    },

    mutations: {
        SET_PRESTAMO(state, prestamos) {
            state.prestamos = prestamos
        },
    },

    actions: {
        fetchPrestamos: async function({ commit }) {
            await axios
                .post('fetchPrestamos')
                .then(response => {
                    commit('SET_PRESTAMO', response.data.prestamos)
                })
                .catch(error => console.log(error))
        },

        savePrestamo: async function({ commit }, prestamo) {
            await axios
                .post('prestamos', prestamo)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updatePrestamo: async function(context, id_prestamo) {
            await axios
                .put('prestamos/' + id_prestamo)
                .then(response => {
                    context.dispatch('fetchPrestamos')
                })
                .catch(error => console.log(error))
        },
    },
}
