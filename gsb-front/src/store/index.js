import { createStore } from 'vuex'
import auth from './modules/auth'

const store = createStore({
  modules: {
    auth
  },
  strict: process.env.NODE_ENV !== 'production'
})

export default store