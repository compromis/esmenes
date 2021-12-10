<template>
  <article :id="article" class="amendable mt-2">
    <button
      class="amendable-card"
      @click="amendText('modification', `#art${article}-edit`)"
    >
      <div class="amendable-title">
        <component :is="hTag" :id="indexId" class="text-regular mb-0">
          {{ indexTitle }}
        </component>
        <circly-button
          :id="`art${article}-delete`"
          icon="trash"
          @click="amendText('deletion', `#art${article}-delete`)"
        >
          Suppress me
        </circly-button>
      </div>
      <div class="amendable-content">
        <div ref="text">
          <slot />
        </div>
        <circly-button
          :id="`art${article}-edit`"
          class="edit-button"
          @click="amendText('modification', `#art${article}-edit`)"
        >
          Edit me
        </circly-button>
      </div>
    </button>
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
      return `Article ${this.article}. ${this.title || ''}`
    },

    amendments() {
      return this.article in this.$store.state.assembly.amendments
        ? this.$store.state.assembly.amendments[this.article]
        : []
    },
  },

  methods: {
    amendText(type = 'modification', focusBackTo) {
      const { article, indexTitle: title } = this
      const html = this.$refs.text.innerHTML
      const turndownService = new TurndownService()
      const text = turndownService.turndown(html)
      this.$root.$emit('amendText', { article, title, text, type }, focusBackTo)
    },
  },
}
</script>

<style lang="scss">
.amendable {
  &-card {
    display: block;
    width: 100%;
    text-align: left;
    appearance: none;
    border: none;
    background: none;
    border-radius: 0.75rem;
    padding: 1rem;
    transition: 0.25s ease;
  }

  &-title,
  &-content {
    display: grid;
    grid-template-columns: 1fr 0.2fr;
    align-items: center;
  }

  &-card:hover {
    background: #fff5d0;
  }
}
</style>
