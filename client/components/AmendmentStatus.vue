<template>
  <b-badge :variant="statusVariant" size="sm">
    {{ statusWord }}
  </b-badge>
</template>

<script>
export default {
  props: {
    status: {
      type: String,
      default: 'pending',
    },
  },

  computed: {
    statusWord() {
      const words = {
        pending: 'Pendent de revisió',
        approved: 'Aprovada',
        compromise: 'Transaccionada',
        denied: 'Rebutjada',
        withdrawn: 'Retirada',
      }

      return words[this.status]
    },

    statusVariant() {
      const variants = {
        pending: 'warning',
        approved: 'success',
        compromise: 'info',
        denied: 'danger',
        withdrawn: 'supermuted',
      }

      return variants[this.status]
    },
  },
}
</script>
