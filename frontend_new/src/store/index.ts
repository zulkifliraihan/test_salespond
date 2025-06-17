import Vue from 'vue'
import Vuex from 'vuex'
import contacts from './modules/contacts'
import callLogs from './modules/callLogs'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    contacts,
    callLogs
  }
})
