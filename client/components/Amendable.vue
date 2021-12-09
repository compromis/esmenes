<template>
  <article :id="article" class="amendable">
    <div class="amendable-title">
      <component :is="hTag" class="text-regular">
        {{ indexTitle }}
      </component>
      <circly-button icon="trash" @click="amendText('deletion')"
        >Suppress me</circly-button
      >
    </div>
    <div ref="text" class="amendable-content">
      <slot />
      <circly-button @click="amendText('modification')">Edit me</circly-button>
    </div>
    <div></div>
    <amendment-list :amendments="amendments" />
  </article>
</template>

<script>
import TurndownService from 'turndown'

export default {
  isIndexable: true,

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
      default: '4',
    },
    isIndexable: {
      type: Boolean,
      default: true,
    },
  },

  computed: {
    hTag() {
      return 'h' + this.level
    },

    indexId() {
      return 'Art' + this.article
    },

    indexTitle() {
      return `Article ${this.article}. ${this.title}`
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

<style lang="scss">
.amendable {
  border-radius: 0.75rem;

  &-title,
  &-content {
    display: grid;
    grid-template-columns: 1fr 0.2fr;
  }

  &:hover {
    background: wheat;
  }
}
</style>
