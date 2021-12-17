export const state = () => ({

})

export const mutations = {

}

export const getters ={

}
export const actions = {
  async newMeeting(state,payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post('meeting',payload.form)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => reject(error.response))
    });
  } ,
}
