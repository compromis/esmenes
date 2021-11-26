<template>
  <div>
    <h2>Assembly ID: {{ assembly }}</h2>
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
}
</script>
