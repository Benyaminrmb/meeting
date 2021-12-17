export default function ({$axios, redirect, app}) {
  $axios.onRequest(config => {
    config.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    const accessToken = app.$cookies.get('token');
    if (accessToken) {
      config.headers.Authorization = "Bearer " + accessToken;
    }
    return config;
  })
}
