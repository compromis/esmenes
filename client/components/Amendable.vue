<template>
  <article :id="article" class="amendable">
    <component :is="hTag">
      {{ articleTitle }}
    </component>
    <div ref="text">
      <slot />
    </div>
    <div>
      <button @click="amendText('modification')">Edit me</button>
      <button @click="amendText('deletion')">Suppress me</button>
    </div>
    <amendment-list :amendments="amendments" />
  </article>
</template>

<script>
import TurndownService from 'turndown'

export default {
  props: {
    article: {
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

    articleTitle() {
      return this.title || `Article ${this.article}`
    },

    amendments() {
      return this.article in this.$store.state.assembly.amendments
        ? this.$store.state.assembly.amendments[this.article]
        : []
    },
  },

  methods: {
    amendText(type = 'modification') {
      const { article, articleTitle: title } = this
      const html = this.$refs.text.innerHTML
      const turndownService = new TurndownService()
      const text = turndownService.turndown(html)
      this.$root.$emit('amendText', { article, title, text, type })
    },
  },
}
</script>

<style lang="scss" scoped>
.amendable {
  background: #ccc;
}
</style>
