<template>
  <div class="col-span-12 bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
           alt="Workflow">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        ورود
      </h2>

    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="loginUser"
              class="space-y-6" action="#" method="POST">


          <div class="form-control">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input v-model="login_entries.email"
                   autocomplete="name" required
                   placeholder="email"
                   :class="`input input-bordered `+ (login_errors.email !== null ? 'input-error':'')"
                   type="email">

            <label v-if="login_errors.email" class="label">
                <span v-for="item in login_errors.email"
                      class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text">رمز</span>
            </label>
            <input v-model="login_entries.password"
                   autocomplete="password" required
                   placeholder="رمز"
                   :class="`input input-bordered `+ (login_errors.password !== null ? 'input-error':'')"
                   type="password">

            <label v-if="login_errors.password"
                   v-for="item in login_errors.password"
                   class="label">
                <span class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input disabled id="remember_me" name="remember_me" type="checkbox"
                     class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
              <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                به خاطر سپردن
              </label>
            </div>

            <div class="text-sm">
              <a disabled="disabled" class="text-primary disabled">
                فراموشی رمز عبور
              </a>
            </div>
          </div>

          <div class="mb-4">
            <button type="submit"
                    :disabled="login_loading"
                    :class="[login_loading ? 'loading ':'','btn btn-block btn-primary']">
              ورود
            </button>
          </div>
          <div class="w-100 flex flex-wrap ">
            <nuxt-link class="btn btn-block btn-ghost" to="/auth/register">
              ثبت نام
            </nuxt-link>
          </div>
        </form>


      </div>
    </div>
  </div>

</template>
<script>
export default {
  data() {
    return {
      login_entries: {
        email: '',
        password: '',
      },
      login_errors: {
        email: null,
        password: null,
      },
      login_loading: false
    }
  },
  methods: {
    loginUser() {
      this.login_loading = true
      this.login_errors = this.resetItems(this.login_errors)
      this.$store.dispatch('authentication/user/loginUser', {form: this.login_entries})
        .then(response => {
          this.$router.push('/');
        }).catch(error => {
        if (error.status === 400) {
          this.login_errors = this.setItems(error.data.data, this.login_errors);
        }
        if (error.response.data.message) {
          this.$toast.show({
            type: 'danger',
            message: error.response.data.message,
            primary: {label: 'تلاش مجدد', action: () => this.loginUser()},
            timeout: false,
          })
        }
      }).finally(() => this.login_loading = false)
    },
  },
}
</script>
