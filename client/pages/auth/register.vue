<template>
  <div class="col-span-12 min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
           alt="Workflow">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        ثبت نام
      </h2>

    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="registerUser"
              id="register_form"
              class="space-y-6" action="#" method="POST">


          <div class="form-control">
            <label class="label">
              <span class="label-text">نام</span>
            </label>
            <input v-model="register_entries.name"
                   autocomplete="name" required
                   placeholder="نام"
                   :class="`input input-bordered `+ (register_errors.name !== null ? 'input-error':'')"
                   type="text">

            <label v-if="register_errors.name" class="label">
                <span v-for="item in register_errors.name"
                      class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text">ایمیل</span>
            </label>
            <input v-model="register_entries.email"
                   autocomplete="email" required
                   placeholder="ایمیل"
                   :class="`input input-bordered `+ (register_errors.email !== null ? 'input-error':'')"
                   type="email">

            <label v-if="register_errors.email" class="label">
                <span v-for="item in register_errors.email"
                      class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text">رمز</span>
            </label>
            <input v-model="register_entries.password"
                   autocomplete="password" required
                   placeholder="رمز"
                   :class="`input input-bordered `+ (register_errors.password !== null ? 'input-error':'')"
                   type="password">

            <label v-if="register_errors.password"
                   v-for="item in register_errors.password"
                   class="label">
                <span class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>


          <div class="form-control">
            <label class="label">
              <span class="label-text">تکرار رمز</span>
            </label>
            <input v-model="register_entries.password_confirmation"
                   autocomplete="password" required
                   placeholder="تکرار رمز"
                   :class="`input input-bordered `+ (register_errors.password_confirm !== null ? 'input-error':'')"
                   type="password">

            <label v-if="register_errors.password_confirm"
                   v-for="item in register_errors.password_confirm"
                   class="label">
                <span class="label-text-alt">
                  {{ item }}
                </span>
            </label>
          </div>


          <div class="mb-4">
            <button type="submit"
                    :disabled="register_loading"
                    :class="[register_loading ? 'loading ':'','btn btn-block btn-primary']">
              ثبت نام
            </button>
          </div>
          <div class="w-100 flex flex-wrap ">
            <nuxt-link class="btn btn-block btn-ghost" to="/auth/login">
              ورود
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
      register_entries: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      register_errors: {
        name: null,
        email: null,
        password: null,
        password_confirm: null,
      },
      register_loading: false
    }
  },
  methods: {
    registerUser() {
      this.register_loading = true
      this.register_errors = this.resetItems(this.register_errors)
      this.$store.dispatch('authentication/user/registerUser', {form: this.register_entries})
        .then(response => {
          this.$router.push('/');
        }).catch(error => {
        if (error.status === 400) {
          this.register_errors = this.setItems(error.data.data, this.register_errors);
        }
        if (error.response.data.message) {
          this.$toast.show({
            type: 'danger',
            message: error.response.data.message,
            primary: {label: 'تلاش مجدد', action: () => this.registerUser()},
            timeout: false,
          })
        }
      }).finally(() => this.register_loading = false)
    },
  },

}
</script>
