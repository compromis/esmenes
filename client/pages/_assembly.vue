<template>
  <div>
    <h2>Assembly ID: {{ currentAssembly }}</h2>
    <b-tab-list>
      <nuxt-link :to="`/${assembly}`" class="tab">Inici</nuxt-link>
      <nuxt-link
        v-for="document in documents"
        :key="document.slug"
        class="tab"
        :to="`/${assembly}/${document.slug}`"
      >
        {{ document.title }}
      </nuxt-link>
    </b-tab-list>
    <nuxt-child />
  </div>
</template>

<script>
export default {
  async asyncData({ $content, params }) {
    const { assembly } = params
    const documents = await $content(assembly + '/docs')
      .only(['slug', 'title'])
      .fetch()

    return { assembly, documents }
  },

  computed: {
    currentAssembly() {
      return this.$store.state.assembly.assembly
    },
  },

  async mounted() {
    const assembly = await this.$api.assembly(this.assembly)
    this.$store.commit('assembly/setAssembly', assembly)
  },
}
</script>
