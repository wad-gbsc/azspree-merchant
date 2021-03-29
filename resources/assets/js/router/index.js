import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '@/containers/Full'

// Views
import Dashboard from '@/views/Dashboard'

// Views - Pages
import Page404 from '@/views/pages/Page404'
import Page500 from '@/views/pages/Page500'
import Login from '@/views/pages/Login'
import Logout from '@/views/pages/Logout'
// import Register from '@/views/pages/Register'
import Profile from '@/views/pages/Profile'


//Views - Utilities
// import users from '@/views/utilities/Users' 

import Products from '@/views/product/Products'
import Orders from '@/views/shop/Orders'
import Logs from '@/views/shop/Logs'
import Shipments from '@/views/shop/Shipments'
import Cancellations from '@/views/shop/Cancellations'
import Comments from '@/views/shop/Comments'
import Merchants from '@/views/maintenance/Merchants'


import store from '../store'
Vue.use(Router)

const router = new Router({
  mode: 'hash', // Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: 'dashboard',
      name: 'Home',
      component: Full,
      children: [
        {
          path: 'Profile',
          name: 'Profile',
          component: Profile,
          meta: { requiresAuth: true },
        },
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
          meta: { requiresAuth: true },
        },

        {
          path: 'product',
          name: 'Product',
          component: {
            render (c) { return c('router-view') }
          },

          children: [
            {
              path: 'Products',
              name: 'Products',
              component: Products,
              meta: {requiresAuth: true}
            },
          ]},

          {
            path: 'shop',
            name: 'My Shop',
            component: {
              render (c) { return c('router-view') }
            },
  
            children: [
              {
                path: 'Orders',
                name: 'Order(s)',
                component: Orders,
                meta: {requiresAuth: true}
              },
              {
                path: 'Shipments',
                name: 'Shipment(s)',
                component: Shipments,
                meta: {requiresAuth: true}
              },
              {
                path: 'Cancellations',
                name: 'Cancellation(s)',
                component: Cancellations,
                meta: {requiresAuth: true}
              },
              {
                path: 'Comments',
                name: 'Comment(s)',
                component: Comments,
                meta: {requiresAuth: true}
              },
              {
                path: 'Logs',
                name: 'History Log(s)',
                component: Logs,
                meta: {requiresAuth: true}
              },
            ]},
            {
              path: 'maintenance',
              name: 'Maintenance',
              component: {
                render (c) { return c('router-view') }
              },
              children: [
                {
                  path: 'Merchants',
                  name: 'Merchants',
                  component: Merchants,
                  meta: {requiresAuth: true}
                },
              ]},
    
      ]
    },
    // {
    //   path: '/register',
    //   name: 'Register',
    //   component: Register
    // },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/logout',
      name: 'Logout',
      component: Logout
    },
    {
      path: '*',
      name:'404', 
      component: Page404
    },
  ]
})
export default router
router.beforeEach((to, from, next) => {

  // check if the route requires authentication and user is not logged in
  if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
    // redirect to login page
    next({ name: 'Login' })
    return
  }

// if logged in redirect to dashboard
  if(to.path === '/login' && store.state.isLoggedIn) {
      next({name: from.name})
      return
  }

next()
})
