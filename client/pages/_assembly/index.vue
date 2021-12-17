<template>
  <div class="document-container px-3">
    <nuxt-content :document="index" />
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  async asyncData({ $content, params }) {
    const { assembly } = params
    const index = await $content(`${assembly}/index`).fetch()

    return { index }
  },

  mounted() {
    this.$store.commit('assembly/setDocument', { slug: '', title: 'Inici' })
  },
}
</script>

<style lang="scss" scoped>
.document-container {
  max-width: 1000px;
  margin: 2rem auto;
}
</style>
