<template>
  <div>
    <form>I'm a form</form>
    <div v-if="amendable">
      {{ amendable.title }}
      <div class="d-flex">
        <textarea
          v-model="amendable.text"
          style="width: 600px; height: 600px"
        />
        <div v-html="preview"></div>
        <pre>{{ preview }}</pre>
      </div>
    </div>
  </div>
</template>

<script>
import diff from 'rich-text-diff'
import { marked } from 'marked'

export default {
  data() {
    return {
      amendable: null,
      amended: '',
    }
  },

  computed: {
    preview() {
      return marked(diff(this.amendable.original, this.amendable.text))
    },
  },

  mounted() {
    this.$root.$on('amendText', (payload) => {
      this.amendable = {
        original: payload.text,
        ...payload,
      }
    })
  },

  methods: {
    editing() {
      this.amended = this.$refs.text.innerHTML
    },
  },
}
</script>

<style lang="scss">
form {
  background: yellow;
}

ins {
  background: green;
}
</style>
