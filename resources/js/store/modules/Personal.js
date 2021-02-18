export default {
    namespaced: true,

    state: {
        personal: [],
    },

    getters: {
        getPersonal: state => {
            return state.personal
        },
    },

    mutations: {
        SET_PERSONAL(state, personal) {
            state.personal = personal
        },
    },

    actions: {
        fetchPersonal: async function({ commit }) {
            await axios
                .post('fetchPersonal')
                .then(response => {
                    commit('SET_PERSONAL', response.data.personal)
                })
                .catch(error => console.log(error))
        },

        savePersonal: async function({ commit }, personal) {
            await axios
                .post('personal', personal)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },

        updatePersonal: async function({ commit }, personal) {
            await axios
                .put('personal/' + personal.id_personal, personal)
                .then(response => {
                    console.log('Ok')
                })
                .catch(error => console.log(error))
        },
    },
}
