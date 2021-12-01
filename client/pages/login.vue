<template>
  <div>Redirigint...</div>
</template>

<script>
export default {
  async mounted() {
    const token = this.$route.query.token

    if (!token) {
      this.$router.push('/')
    }

    await this.$store.commit('auth/setToken', token)
    const user = await this.$api.userinfo(token)

    this.$store.commit('auth/setUserInfo', user)
    this.$router.push('/assemblies')
  },
}
</script>
