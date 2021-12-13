<template>
  <section>
    <component :is="hTag" :id="indexId">
      {{ indexTitle }}
    </component>
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
    level: {
      type: [String, Number],
      default: 2,
    },
  },

  computed: {
    indexTitle() {
      return this.title
    },

    indexId() {
      return 'Sec' + this.id
    },

    hTag() {
      return 'h' + this.level
    },
  },
}
</script>
