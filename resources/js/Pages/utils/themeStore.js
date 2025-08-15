// resources/js/themeStore.js
import { ref } from 'vue'

export const theme = ref(localStorage.getItem('theme') || 'dark')

export function setTheme(newTheme) {
  theme.value = newTheme
  document.documentElement.setAttribute('data-theme', newTheme)
  localStorage.setItem('theme', newTheme)
}