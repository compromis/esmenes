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
              :class="[
                'tab',
                { selected: document.slug === currentDocumentSlug },
              ]"
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
      .sortBy('order')
      .fetch()

    return { assembly, documents }
  },

  computed: {
    currentAssembly() {
      return this.$store.state.assembly.assembly
    },

    currentDocumentSlug() {
      return this.$store.state.assembly.document
        ? this.$store.state.assembly.document.slug
        : null
    },
  },

  async mounted() {
    try {
      const assembly = await this.$api.assembly(this.assembly)
      this.$store.commit('assembly/setAssembly', assembly)
    } catch (error) {
      window.location.replace(process.env.NUXT_ENV_ESPAI_SSO)
    }
  },
}
</script>

<style lang="scss">
.assembly-header {
  border-bottom: 1px solid var(--gray-300);
  background: var(--white);
}

.assembly-documents {
  overflow-x: auto;
  margin: 0 -1rem;

  @include scrollbar();

  .tab-list {
    flex-wrap: nowrap;
    padding: 0 1rem;
  }
}
</style>
