<template>
  <div>
    <h1>{{ document.title }}</h1>
    <nuxt-content :document="document" />
    <amendable-form />
  </div>
</template>

<script>
export default {
  async asyncData({ $content, params }) {
    const { assembly, doc } = params
    const document = await $content(assembly + '/docs/' + doc).fetch()

    return { assembly, doc, document }
  },

  async mounted() {
    // Fetch document amendments
    const { assembly, doc } = this.$route.params
    const amendments = await this.$api.amendments(assembly, doc)
    this.$store.commit('assembly/setAmendments', amendments)

    // Add amendment if user submitted one
    this.$root.$on('amendmentSubmitted', (amendment) => {
      this.$store.commit('assembly/addAmendment', amendment)
    })
  },
}
</script>
