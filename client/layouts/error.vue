<template>
  <div class="container">
    <h1>{{ message }}</h1>
    <nuxt-link to="/">Torna</nuxt-link>
  </div>
</template>

<script>
export default {
  layout: 'blog',
  props: {
    error: {
      type: Object,
      default: null,
    },
  },
  computed: {
    statusCode() {
      return (this.error && this.error.statusCode) || 500
    },
    message() {
      return this.error.message || 'Error'
    },
  },
}
</script>
