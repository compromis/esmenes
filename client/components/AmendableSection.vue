<template>
  <section>
    <h2 :id="indexId">{{ indexTitle }}</h2>
    <slot />
  </section>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },

    id: {
      type: String,
      required: true,
    },

    isIndexable: {
      type: Boolean,
      default: true,
    },
  },

  computed: {
    indexTitle() {
      return this.title
    },

    indexId() {
      return 'Sec' + this.id
    },
  },
}
</script>
