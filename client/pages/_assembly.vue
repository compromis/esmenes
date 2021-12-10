<template>
  <div>
    <div v-if="currentAssembly">
      <div class="assembly-header pt-5 px-3">
        <h2 class="text-regular text-4xl">{{ currentAssembly.name }}</h2>
        <div class="assembly-documents">
          <b-tab-list class="my-4" size="lg">
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
        </div>
      </div>
      <nuxt-child />
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

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

<style lang="scss">
.assembly-header {
  border-bottom: 1px solid var(--gray-200);
  background: var(--white);
}

.assembly-documents {
  overflow-x: auto;
  margin: 0 -1rem;

  .tab-list {
    flex-wrap: nowrap;
    padding: 0 1rem;
  }
}
</style>
