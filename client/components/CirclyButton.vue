<template>
  <button
    class="circly-button"
    @click="$emit('click')"
    @mouseover="$emit('mouseover')"
    @mouseout="$emit('mouseout')"
  >
    <span><slot /></span>
    <span class="icon-circle"><font-awesome-icon :icon="['far', icon]" /></span>
  </button>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: 'pencil',
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
  color: var(--gray-600);
  transition: 0.25s;
  gap: 0.5rem;
  flex-direction: row-reverse;
  justify-self: start;
  padding: 0;

  .icon-circle {
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
    width: 2rem;
    height: 2rem;
    border: 2px solid var(--gray-600);
    transition: 0.25s;
  }

  &:hover,
  &:focus {
    color: var(--text-base);

    .icon-circle {
      color: var(--text-base);
      border-color: var(--text-base);
    }
  }

  &:focus {
    outline: none;

    .icon-circle {
      box-shadow: 0 0 0 3px rgba($black, 0.25);
    }
  }
}

a.button {
  display: inline-block;
}
</style>
