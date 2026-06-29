import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const tokenCookie = useCookie<string | null>('auth_token', { maxAge: 60 * 60 * 24 * 7 }) // 7 days
  const userCookie = useCookie<any>('auth_user', { maxAge: 60 * 60 * 24 * 7 })
  
  const user = ref<any>(userCookie.value || null)
  const token = ref<string | null>(tokenCookie.value || null)
  const isAuthenticated = ref(!!tokenCookie.value)

  const setAuth = (userData: any, accessToken: string) => {
    user.value = userData
    token.value = accessToken
    isAuthenticated.value = true
    
    tokenCookie.value = accessToken
    userCookie.value = userData
  }

  const clearAuth = () => {
    user.value = null
    token.value = null
    isAuthenticated.value = false
    
    tokenCookie.value = null
    userCookie.value = null
  }

  const initAuth = () => {
    if (tokenCookie.value && userCookie.value) {
      token.value = tokenCookie.value
      user.value = userCookie.value
      isAuthenticated.value = true
    }
  }

  const logout = async () => {
    if (token.value) {
      try {
        await $fetch('/api/logout', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token.value}`
          }
        })
      } catch (e) {
        // Ignored, token might already be invalid
      }
    }
    clearAuth()
  }

  return { user, token, isAuthenticated, setAuth, clearAuth, initAuth, logout }
})
