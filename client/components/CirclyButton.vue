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
    width: 32px;
    height: 32px;
    border: 2px solid var(--gray-600);
    transition: 0.25s;
  }

  &:hover {
    color: var(--black);

    .icon-circle {
      color: var(--black);
      border-color: var(--black);
    }
  }
}

a.button {
  display: inline-block;
}
</style>
