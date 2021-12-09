<template>
  <component :is="tag" :to="to" :href="href" class="circly-button">
    <span><slot /></span>
    <span class="icon-circle"
      ><font-awesome-icon :icon="['far', `${icon}`]"
    /></span>
  </component>
</template>

<script>
export default {
  props: {
    to: {
      type: String,
      default: null,
    },
    href: {
      type: String,
      default: null,
    },
    icon: {
      type: String,
      default: 'pencil',
    },
  },
  computed: {
    tag() {
      if (this.to) {
        return 'nuxt-link'
      }

      if (this.href) {
        return 'a'
      }

      return 'button'
    },
  },
}
</script>

<style lang="scss" scoped>
.circly-button {
  display: flex;
  align-items: center;
  appearance: none;
  border: none;
  background: none;
  padding: 0.5rem 0;
  transition: 0.25s ease;
  color: var(--gray-600);

  .icon-circle {
    margin-left: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
    width: 32px;
    height: 32px;
    border: 2px solid var(--gray-600);
    transition: 0.25s ease;
  }

  &:hover {
    color: var(--black);

    .icon-circle {
      border-color: var(--black);
    }
  }
}

a.button {
  display: inline-block;
}
</style>
