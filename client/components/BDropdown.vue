<template>
  <div class="dropdown-trigger">
    <a href="#">User</a>
    <ul class="dropdown-items">
      <li><a href="#">Les meues esmenes</a></li>
      <li><nuxt-link to="/logout">Tanca sessió</nuxt-link></li>
    </ul>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.dropdown-items {
  visibility: hidden;
  opacity: 0;
  transform: scale(0.95);
  position: absolute;
  display: block;
  top: 3rem;
  right: 0;
  transition: 0.15s ease-in-out;
}

.dropdown-trigger:hover,
.dropdown-trigger:focus-within {
  .dropdown-items {
    visibility: visible;
    opacity: 1;
    transform: scale(1);
  }
}
</style>
