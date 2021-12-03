export const state = () => ({
  assembly: null,
})

export const mutations = {
  setAssembly(state, payload) {
    state.assembly = payload
  },
}
