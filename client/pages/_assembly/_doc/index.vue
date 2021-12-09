<template>
  <div>
    <h1>{{ document.title }}</h1>
    <button @click="generalAmendment">Esmena general</button>
    <amendment-list :amendments="generalAmendments" />
    <nuxt-content :document="document" />
    <amendable-form />
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  async asyncData({ $content, params }) {
    const { assembly, doc } = params
    const document = await $content(assembly + '/docs/' + doc).fetch()

    return { assembly, doc, document }
  },

  computed: {
    generalAmendments() {
      const amendments = this.$store.state.assembly.amendments
      return amendments.general || []
    },
  },

  watch: {
    '$route.params.doc'() {
      this.fetchAmendments()
    },
  },

  mounted() {
    // Fetch document amendments
    this.fetchAmendments()

    // Add amendment if user submitted one
    this.$root.$on('amendmentSubmitted', (amendment) => {
      this.$store.commit('assembly/addAmendment', amendment)
    })
  },

  methods: {
    async fetchAmendments() {
      const { assembly, doc } = this.$route.params
      const amendments = await this.$api.amendments(assembly, doc)
      this.$store.commit('assembly/setAmendments', amendments)
    },

    generalAmendment() {
      this.$root.$emit('amendText', {
        article: 'general',
        title: this.document.title,
        type: 'addition',
        text: '',
      })
    },
  },
}
</script>
