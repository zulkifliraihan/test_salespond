import Vue from 'vue'
import VueRouter from 'vue-router'
import ContactsView from '../views/ContactsView.vue'
import CallLogsView from '../views/CallLogsView.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/contacts'
  },
  {
    path: '/contacts',
    name: 'Contacts',
    component: ContactsView
  },
  {
    path: '/call-logs',
    name: 'CallLogs',
    component: CallLogsView
  }
]

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.BASE_URL,
  routes
})

export default router