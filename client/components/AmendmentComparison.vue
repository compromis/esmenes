<template>
  <div class="comparison" v-html="comparison" />
</template>

<script>
import Diff from 'text-diff'

export default {
  props: {
    before: {
      type: String,
      default: '',
    },
    after: {
      type: String,
      default: '',
    },
  },

  computed: {
    comparison() {
      const { before, after } = this
      const diff = new Diff({ timeout: 2, editCost: 6 })
      const textDiff = diff.main(before || '', after || '')
      diff.cleanupEfficiency(textDiff)
      const html = diff.prettyHtml(textDiff)
      return html.replaceAll('  ', '&nbsp;&nbsp;')
    },
  },
}
</script>

<style lang="scss">
.comparison {
  font-family: $font-family-sans-serif;
  word-break: break-word;

  ins {
    background: rgba($green, 0.25);
    text-decoration: none;
    padding: 1px 3px;
    border-radius: 3px;
    -webkit-box-decoration-break: clone;
    box-decoration-break: clone;
  }

  del {
    background: rgba($red, 0.25);
    border-radius: 3px;
    padding: 1px 3px;
    text-decoration-color: rgba($red, 0.5);
    -webkit-box-decoration-break: clone;
    box-decoration-break: clone;
  }
}
</style>
