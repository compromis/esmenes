import Vue from 'vue'

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
    if (amendment.article in state.amendments) {
      state.amendments[amendment.article].push(amendment)
    } else {
      Vue.set(state.amendments, amendment.article, [amendment])
    }
  },
}
