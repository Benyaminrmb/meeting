
export default function ({ app, store  }) {
   if (!store.state.authentication.user.isLoggedIn) {
    app.redirect(403,'/')
  }
}
