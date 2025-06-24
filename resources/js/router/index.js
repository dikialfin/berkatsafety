import { createRouter, createWebHistory } from "vue-router";
import store from '@/js/stores'
import NotFound from '@/js/pages/NotFound';
import Dashboard from '@/js/pages/Dashboard';
import Akun from '@/js/pages/Akun';
import Login from '@/js/pages/auth/Login';
import Register from '@/js/pages/auth/Register';
import Forgot from '@/js/pages/auth/Forgot';
import Reset from '@/js/pages/auth/Reset';
import Verification from '@/js/pages/auth/Verification';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes: [
      {
        path: '/',
        name: 'home',
        meta: {guest:true},
        component: Login
      },
      {
        path: '/login',
        name: 'login',
        meta: {guest:true},
        component: Login
      },
      {
        path: '/forgot-password',
        name: 'forgot-password',
        meta: {guest:true},
        component: Forgot
      },
      {
        path: '/reset/:token',
        name: 'reset',
        component: Reset
      },
      {
        path: '/verification/:token',
        name: 'verification',
        component: Verification
      },
      {
        path: '/app',
        component: () => import('@/js/layouts/User.vue'),
        meta: { auth: true },
        children: [
          {
            path: 'dashboard',
            children: [
              {
                path: '',
                name: 'dashboard',
                component: Dashboard,
              },
              {
                path: ':company_id/dashboard',
                name: 'dashboard.community.dashboard',
                component: () => import('@/js/pages/admin/dashboard/Dashboard.vue')
              },
              {
                path: ':company_id/benefits',
                name: 'dashboard.community.benefits',
                component: () => import('@/js/pages/admin/dashboard/Benefits.vue')
              },
            ]
          },
          {
            path: 'account',
            name: 'account',
            component: Akun
          },
          {
            path: 'user-log',
            name: 'user-log',
            component: () => import('@/js/pages/admin/user/Log.vue')
          },
          {
            path: 'brands',
            children: [
                {
                  path: '',
                  name: 'admin.brands.list',
                  component: () => import('@/js/pages/admin/brands/Table.vue'),
                },
                {
                  path: 'add',
                  name: 'admin.brands.add',
                  component: () => import('@/js/pages/admin/brands/Form.vue'),
                },
                {
                  path: 'edit/:id',
                  name: 'admin.brands.edit',
                  component: () => import('@/js/pages/admin/brands/Form.vue'),
                }
            ]
          },
          {
            path: 'categories',
            children: [
                {
                  path: '',
                  name: 'admin.categories.list',
                  component: () => import('@/js/pages/admin/categories/Table.vue'),
                },
                {
                  path: 'add',
                  name: 'admin.categories.add',
                  component: () => import('@/js/pages/admin/categories/Form.vue'),
                },
                {
                  path: 'edit/:id',
                  name: 'admin.categories.edit',
                  component: () => import('@/js/pages/admin/categories/Form.vue'),
                }
            ]
          },
          {
            path: 'products',
            children: [
              {
                path: '',
                name: 'admin.products.list',
                component: () => import('@/js/pages/admin/products/Table.vue'),
              },
              {
                path: 'add',
                name: 'admin.products.add',
                component: () => import('@/js/pages/admin/products/Form.vue'),
              },
              {
                path: 'edit/:id',
                name: 'admin.products.edit',
                component: () => import('@/js/pages/admin/products/Form.vue'),
              }
            ]
          },
          {
            path: 'blogs',
            children: [
              {
                path: '',
                name: 'admin.blogs.list',
                component: () => import('@/js/pages/admin/blogs/Table.vue'),
              },
              {
                path: 'add',
                name: 'admin.blogs.add',
                component: () => import('@/js/pages/admin/blogs/Form.vue'),
              },
              {
                path: 'edit/:id',
                name: 'admin.blogs.edit',
                component: () => import('@/js/pages/admin/blogs/Form.vue'),
              }
            ]
          },
          {
            path: 'catalogue',
            children: [
              {
                path: '',
                name: 'admin.catalogue.list',
                component: () => import('@/js/pages/admin/catalogue/Table.vue'),
              },
              {
                path: 'add',
                name: 'admin.catalogue.add',
                component: () => import('@/js/pages/admin/catalogue/Form.vue'),
              },
              {
                path: 'edit/:id',
                name: 'admin.catalogue.edit',
                component: () => import('@/js/pages/admin/catalogue/Form.vue'),
              }
            ]
          },
          {
            path: 'about',
            children: [
              {
                path: '',
                name: 'admin.about.list',
                component: () => import('@/js/pages/admin/about/Table.vue'),
              },
              {
                path: 'edit/:id',
                name: 'admin.about.edit',
                component: () => import('@/js/pages/admin/about/Form.vue'),
              }
            ]
          },
          {
            path: 'csr',
            children: [
              {
                path: '',
                name: 'admin.csr.list',
                component: () => import('@/js/pages/admin/csr/Table.vue'),
              },
              {
                path: 'add',
                name: 'admin.csr.add',
                component: () => import('@/js/pages/admin/csr/Form.vue'),
              },
              {
                path: 'edit/:id',
                name: 'admin.csr.edit',
                component: () => import('@/js/pages/admin/csr/Form.vue'),
              }
            ]
          },
          {
            path: 'media',
            children: [
              {
                path: '',
                name: 'admin.media',
                component: () => import('@/js/pages/admin/media/Table.vue'),
              }
            ]
          },
          {
            path: 'image-slider',
            children: [
              {
                path: '',
                name: 'admin.slider.list',
                component: () => import('@/js/pages/admin/image-slider/Table.vue'),
              },
              {
                path: 'add',
                name: 'admin.slider.add',
                component: () => import('@/js/pages/admin/image-slider/Form.vue'),
              },
            ]
          },
          {
            path: 'translations',
            children: [
                {
                  path: '',
                  name: 'admin.translations.list',
                  component: () => import('@/js/pages/admin/translations/Table.vue'),
                },
            ]
          },
          {
            path: 'seo-pages-static',
            children: [
              {
                path: '',
                name: 'admin.seo-page.list',
                component: () => import('@/js/pages/admin/seo/Table.vue'),
              },
            ]
          },
          {
            path: 'setting',
            children: [
              {
                path: '',
                name: 'admin.setting',
                component: () => import('@/js/pages/admin/setting/Form.vue'),
              },
            ]
          },
          {
            path: 'contact-us',
            children: [
              {
                path: '',
                name: 'admin.contact-us',
                component: () => import('@/js/pages/admin/contact/Table.vue'),
              },
            ]
          },
          {
            path: 'role',
            children: [
                {
                  path: '',
                  name: 'admin.role.list',
                  component: () => import('@/js/pages/admin/role/Table.vue'),
                },
                {
                  path: 'add',
                  name: 'admin.role.add',
                  component: () => import('@/js/pages/admin/role/Form.vue'),
                },
                {
                  path: 'edit/:id',
                  name: 'admin.role.edit',
                  component: () => import('@/js/pages/admin/role/Form.vue'),
                }
            ]
          },
          {
            path: 'user',
            children: [
                {
                  path: '',
                  name: 'admin.user.list',
                  component: () => import('@/js/pages/admin/user/Table.vue'),
                },
                {
                  path: 'add',
                  name: 'admin.user.add',
                  component: () => import('@/js/pages/admin/user/Form.vue'),
                },
                {
                  path: 'edit/:id',
                  name: 'admin.user.edit',
                  component: () => import('@/js/pages/admin/user/Form.vue'),
                }
            ]
          },
        ]
      },
      {
          path: '/:pathMatch(.*)*',
          name: '404',
          component: () => import('@/js/layouts/User.vue'),
          children:[
            {path:'',component:NotFound}
          ]
      }
    ],
});

router.beforeEach((to, from, next) => {
    //check current login
    if(to.meta.release == false && store.getters.user && store.getters.user.role_id != 1){
      router.push({name:'404'})
    }
    if(from.query.search){
      return next();
    }
    if(to.meta.auth) {
      store.dispatch('attempt_user')
        .catch((error) => {
          if(error.response.status == 401){
            if(!to.meta.noRedirect){
              location.replace('/')
            }else{
              next();
            }
          }
        })
        .then(() => {
          if(store.getters.user){
            next();
          }
        })
    } else if(to.meta.guest){
      store.dispatch('attempt_user')
        .catch((error) => {
          if(error.response.status == 401){
            next()
          }
        })
        .then(() => {
          if(store.getters.user){
            if(store.getters.user.role_id == 3){
              location.replace(`/learn/${store.getters.user.company.saas_setting.adaptive_feedback.slug}`)
            }else{
              location.replace('/app/dashboard')
            }
          }
        })
    } else {
      next();
    }
});

export default router;
