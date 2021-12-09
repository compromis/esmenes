<template>
  <div>
    {{ amendments.length }} {{ amendmentWord }}
    <div v-for="amendment in amendments" :key="amendment.id">
      <support-amendment :amendment="amendment" />
      <pre>{{ amendment }}</pre>
      <hr />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    amendments: {
      type: Array,
      required: true,
    },
  },

  computed: {
    amendmentWord() {
      return this.amendments.length === 1 ? 'esmena' : 'esmenes'
    },
  },
}
</script>
