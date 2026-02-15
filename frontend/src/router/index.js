import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "@/stores/user.js"
import { useOrgStore } from "@/stores/org.js"

const routes = [
  { path: '/', component: () => import('@/pages/LandingPage.vue') },
  { path: '/login', component: () => import('@/pages/LoginPage.vue') },
  { path: '/register', component: () => import('@/pages/RegisterPage.vue') },

  {
    path: '/app',
    children: [
      { path: '', component: () => import('@/pages/app/WelcomePage.vue') },
      { path: 'welcome', component: () => import('@/pages/app/WelcomePage.vue') },
      { path: 'email-not-verified', component: () => import('@/pages/app/user/EmailNotVerifiedPage.vue') },
      { path: 'verify-email', component: () => import('@/pages/app/user/VerifyEmailPage.vue') },

      {
        path: 'returns',
        children: [
          {path: '', component: () => import('@/pages/app/ReturnsPage.vue')},
          {path: 'new', component: () => import('@/pages/app/ReturnNewPage.vue')},
          {path: ':id', component: () => import('@/pages/app/ReturnPage.vue')},
        ]
      },
      { path: 'organization/new', component: () => import('@/pages/app/user/CreateOrganizationPage.vue') }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})


router.beforeEach(async (to, from, next) => {
  const user = useUserStore()
  const org = useOrgStore() // есть, но НЕ участвует в доступе

  const path = to.path

  const isLanding = path === '/'
  const isGuestPage = path === '/login' || path === '/register'
  const isApp = path === '/app' || path.startsWith('/app/')

  const verifyPages = ['/app/email-not-verified', '/app/verify-email']
  const orgOnboardingPages = ['/app/welcome', '/app/organization/new']

  const isVerifyFlow = verifyPages.includes(path)
  const isOrgOnboarding = orgOnboardingPages.includes(path)

  // ===== 0) bootstrap user =====
  if (user.user === null) {
    await user.fetchUser()
  }

  // (опционально) подгружаем org для UI, но НЕ для гейтинга
  if (user.isLoggedIn && org.organization === null) {
    await org.fetchOrganization()
  }

  // helper: куда вести залогиненного
  const redirectAuthedHome = () => {
    if (!user.isVerified) {
      return next('/app/email-not-verified')
    }
    if (!user.user.current_organization_id) {
      return next('/app/welcome')
    }
    return next('/app/returns')
  }

  // ===== 1) "/" — входная точка =====
  // по умолчанию залогиненного редиректим в app,
  // но разрешаем осознанный заход на лендинг через history state
  //if (isLanding && user.isLoggedIn && !to.state?.forceLanding) {
   // return redirectAuthedHome()
  //}

  // ===== 2) guest pages (/login, /register) =====
  if (isGuestPage) {
    return user.isLoggedIn ? redirectAuthedHome() : next()
  }

  // ===== 3) /app требует авторизации =====
  if (isApp && !user.isLoggedIn) {
    return next('/login')
  }

  // публичные страницы (например "/") — ок
  if (!isApp) {
    return next()
  }

  // ===== 4) внутри /app — гейты по состоянию =====

  // 4a) не подтверждён email → только verify flow
  if (!user.isVerified) {
    return isVerifyFlow ? next() : next('/app/email-not-verified')
  }

  // 4b) подтверждён, но нет организации → только onboarding
  if (!user.user.current_organization_id) {
    return isOrgOnboarding ? next() : next('/app/welcome')
  }

  // 4c) /app без child → домой
  if (path === '/app') {
    return redirectAuthedHome()
  }

  return next()
})

export default router
