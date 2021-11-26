<template>
  <div>
    <h1>{{ document.title }}</h1>
    <nuxt-content :document="document" />
  </div>
</template>

<script>
import Vue from 'vue'
import EditableParagraph from '@/components/EditableParagraph'

export default {
  async asyncData({ $content, params }) {
    const { assembly, doc } = params
    const document = await $content(assembly + '/docs/' + doc).fetch()

    return { assembly, doc, document }
  },

  mounted() {
    const everyP = document.querySelectorAll('.nuxt-content p')
    everyP.forEach((elem) => {
      const EditableParagraphConstructor = Vue.extend(EditableParagraph)
      const EditableParagraphInstance =
        new EditableParagraphConstructor().$mount()
      elem.appendChild(EditableParagraphInstance.$el)
    })
  },
}
</script>
