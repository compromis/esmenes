<template>
  <div>
    <nuxt-content :document="index" />
  </div>
</template>

<script>
export default {
  async asyncData({ $content, params }) {
    const { assembly } = params
    const index = await $content(`/${assembly}/index`).fetch()

    return { index }
  },
}
</script>
