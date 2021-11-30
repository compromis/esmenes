<template>
  <article :id="id" class="amendable">
    <component :is="hTag">
      {{ title || `Article ${id}` }}
    </component>
    <div ref="text">
      <slot />
    </div>
    <div>
      <button @click="amendText">Edit me</button>
    </div>
  </article>
</template>

<script>
import TurndownService from 'turndown'

export default {
  props: {
    id: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      default: null,
    },
    level: {
      type: String,
      default: '3',
    },
  },

  computed: {
    hTag() {
      return 'h' + this.level
    },
  },

  methods: {
    amendText() {
      const { id, title } = this
      const html = this.$refs.text.innerHTML
      const turndownService = new TurndownService()
      const text = turndownService.turndown(html)
      this.$root.$emit('amendText', { id, title, text, html })
    },
  },
}
</script>

<style lang="scss" scoped>
.amendable {
  background: #ccc;
}
</style>
