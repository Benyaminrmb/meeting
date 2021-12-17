<template>
  <div class="flex-none">
    <div v-if="$store.state.authentication.user.isLoggedIn" class="flex-none ">
      <div tabindex="0" class="avatar dropdown">
        <div class="rounded-full w-10 h-10 m-1">
          <img src="https://i.pravatar.cc/500?img=32">
        </div>

        <ul tabindex="0" class="p-2 text-gray-800 shadow menu dropdown-content bg-base-100 rounded-box w-52">
          <li>
            <a>Item 1</a>
          </li>
          <li>
            <a>Item 2</a>
          </li>
          <li>
            <a @click="logout">خروج</a>
          </li>
        </ul>
      </div>
    </div>
    <div v-else class="flex-none ">
      <div tabindex="0" class="avatar dropdown">
        <div class="rounded-full w-10 h-10 m-1">
          <img src="https://i.pravatar.cc/500?img=32">
        </div>

        <ul tabindex="0" class="p-2 text-gray-800 shadow menu dropdown-content bg-base-100 rounded-box w-52">
          <li>
            <nuxt-link to="/auth/login">ورود</nuxt-link>
          </li>
          <li>
            <nuxt-link to="/auth/register">ثبت نام</nuxt-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    async logout() {

      await this.$auth
      this.$store.dispatch('authentication/user/logoutUser')
        .then(() => {

          this.$toast.show({
            type: 'success',
            message: 'خارج شدید',
            timeout: 2,
          })

          this.$router.push('/')
        })
        .catch((e) => (this.error = e.response.data))
    },
  },
}
</script>
