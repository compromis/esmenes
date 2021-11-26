export const state = () => ({
  token: '',
  user: null,
})

export const mutations = {
  setToken(state, payload) {
    state.token = payload
  },

  setUserInfo(state, payload) {
    state.user = payload
  },

  logOut(state, payload) {
    state.token = ''
    state.user = null
  },
}
