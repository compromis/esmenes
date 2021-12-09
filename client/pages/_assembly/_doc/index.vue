<template>
  <div>
    <h1>{{ document.title }}</h1>
    <button @click="generalAmendment">Esmena general</button>
    <amendment-list :amendments="generalAmendments" />
    Index {{ toc }}
    <nuxt-content ref="content" :document="document" />
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

  data() {
    return {
      toc: [],
    }
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

    // Create toc
    this.toc = this.createToc(this.$refs.content.$children)
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

    createToc(components) {
      const toc = []
      components.forEach((component) => {
        if (component.isIndexable) {
          toc.push({
            id: component.indexId,
            title: component.indexTitle,
            children: this.createToc(component.$children),
          })
        }
      })

      return toc
    },
  },
}
</script>
