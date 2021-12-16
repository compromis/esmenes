<template>
  <section>
    <component :is="hTag" :id="indexId" class="text-regular mx-3">
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

<style lang="scss" scoped>
h2 {
  margin-top: 2.5rem;
  margin-bottom: 1rem;
  border-bottom: 1px solid var(--b-gray-300);
  padding-bottom: 0.75rem;
}

h3 {
  margin-top: 1.75rem;
  margin-bottom: 0.5rem;
}
</style>
