export const state = () => ({
  user : {} ,
  isLoggedIn : false ,
  zzzz : '' ,
})

export const mutations = {
  SET_REGISTERED_USER ( state , user) {
    state.user = user
    state.isLoggedIn = true
  },
  LOGOUT_USER ( state ) {
    state.user = {}
    state.isLoggedIn = false
  },
}


export const actions = {
  async registerUser(state , payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post('auth/register' , payload.form)
        .then(response => {
          state.commit('SET_REGISTERED_USER', response.data.data)
          this.$cookies.set('token', response.data.data.access_token , {
            path: '/',
            maxAge: 60 * 60 * 24 * 7
          })
          resolve(response.data)
        })
        .catch(error => reject(error.response))
    });
  } ,

  async loginUser(state , payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post('auth/login' , payload.form)
        .then(response => {
          state.commit('SET_REGISTERED_USER', response.data.data)
          this.$cookies.set('token', response.data.data.access_token , {
            path: '/',
            maxAge: 60 * 60 * 24 * 7
          })
          resolve(response.data)
        })
        .catch(error => reject(error.response))
    });
  } ,

  async logoutUser(state) {
    return new Promise((resolve, reject) => {
      this.$axios.post('auth/logout')
        .then(response => {
          state.commit('LOGOUT_USER')
          this.$cookies.remove('token')
          resolve(response.data)
        })
        .catch(error => reject(error.response.data))
    });
  } ,

  async getUser(state) {
    return new Promise((resolve, reject) => {
      this.$axios.get('auth/user')
        .then(response => {
          state.commit('SET_REGISTERED_USER', response.data.data)
          resolve(response.data)
        })
        .catch(error => {
          this.$cookies.remove('token')
          reject(error.response.data);
        })
    });
  } ,

}
