export const state = () => ({
  assembly: null,
  amendments: [],
})

export const mutations = {
  setAssembly(state, assembly) {
    state.assembly = assembly
  },

  setAmendments(state, amendments) {
    state.amendments = amendments
  },

  addAmendment(state, amendment) {
    state.amendments.push(amendment)
  },
}
