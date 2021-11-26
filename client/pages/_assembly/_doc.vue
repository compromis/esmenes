<template>
  <div>
    <h1>{{ document.title }}</h1>
    <nuxt-content :document="document" />
  </div>
</template>

<script>
export default {
  async asyncData({ $content, params }) {
    const { assembly, doc } = params
    const document = await $content(assembly + '/docs/' + doc).fetch()

    return { assembly, doc, document }
  },
}
</script>
