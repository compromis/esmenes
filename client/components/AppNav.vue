<template>
  <nav class="app-nav">
    <div class="nav-logo">
      <a href="https://compromis.net" class="nav-logo-compromis">
        <compromis-logo collapsible="sm" />
      </a>
      <div class="nav-logo-append">
        <router-link to="/assemblies">Esmenes</router-link>
      </div>
    </div>
    <div class="nav-user">
      <b-dropdown />
    </div>
  </nav>
</template>

<script>
import BDropdown from '@/components/BDropdown.vue'
export default {
  components: { BDropdown },
}
</script>

<style lang="scss" scoped>
.app-nav {
  display: flex;
  position: fixed;
  background: var(--white);
  height: var(--nav-height, 3rem);
  padding: 0.85rem 1rem;
  top: 0;
  left: 0;
  right: 0;

  .nav-logo {
    svg {
      height: 1.75rem;
    }

    &-append {
      font-size: 1.25em;
      color: var(--text-muted);
      margin-left: 0.35rem;
    }
  }

  .nav-user {
    margin-left: auto;
  }
}
</style>
