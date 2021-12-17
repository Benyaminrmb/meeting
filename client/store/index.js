export const strict = false
const Cookie = process.client ? require('js-cookie') : undefined
export const actions = {
  async nuxtServerInit({dispatch, document}) {


      await dispatch('authentication/user/getUser').catch(error => {
      })

  }
}
