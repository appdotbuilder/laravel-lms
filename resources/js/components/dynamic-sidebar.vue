<template>
  <Sidebar collapsible="icon" variant="inset" class="border-r border-gray-200 dark:border-gray-700">
    <SidebarHeader class="border-b border-gray-200 dark:border-gray-700">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <a :href="route('dashboard')" class="flex items-center space-x-3 p-2">
              <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                {{ getRoleEmoji() }}
              </div>
              <div class="flex flex-col">
                <span class="font-bold text-gray-900 dark:text-white">EduPlatform</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ getRoleTitle() }}</span>
              </div>
            </a>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent class="px-2 py-4 space-y-2">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-8">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Loading menu...</span>
      </div>

      <!-- Error State -->
      <div v-if="error" class="px-3 py-2 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg">
        <p class="font-medium">Failed to load menu</p>
        <p class="text-xs opacity-75">{{ error }}</p>
        <button 
          @click="fetchMenuData"
          class="mt-1 text-xs underline hover:no-underline"
        >
          Retry
        </button>
      </div>

      <!-- Empty State -->
      <div v-if="isEmpty && !error" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
        <component :is="resolveIcon('Menu')" class="w-8 h-8 mx-auto mb-2 opacity-50" />
        <p>No menu items available</p>
      </div>

      <!-- Menu Groups -->
      <SidebarGroup v-for="group in menuItems" :key="group.label">
        <SidebarGroupLabel class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide px-3">
          {{ group.label }}
        </SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in group.items" :key="`${item.href}-${item.label}`">
              <SidebarMenuButton as-child>
                <a 
                  :href="item.href"
                  :class="[
                    'flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700',
                    item.isActive ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : ''
                  ]"
                >
                  <component :is="resolveIcon(item.icon)" class="w-4 h-4 flex-shrink-0" />
                  <span class="flex-1 truncate">{{ item.label }}</span>
                  <Badge 
                    v-if="item.badge && item.badge > 0"
                    variant="default" 
                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-medium min-w-[1.25rem] h-5 flex items-center justify-center"
                  >
                    {{ item.badge > 99 ? '99+' : item.badge }}
                  </Badge>
                </a>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>

    <SidebarFooter class="border-t border-gray-200 dark:border-gray-700 p-2">
      <NavUser />
    </SidebarFooter>
  </Sidebar>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { 
  Sidebar, 
  SidebarContent, 
  SidebarFooter, 
  SidebarHeader, 
  SidebarMenu, 
  SidebarMenuButton, 
  SidebarMenuItem,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel
} from '@/components/ui/sidebar'
import { NavUser } from '@/components/nav-user'
import { Badge } from '@/components/ui/badge'
import { setupUserEchoListeners, cleanupEchoListeners } from '@/lib/echo'
import { 
  LayoutDashboard,
  CheckCircle,
  Users,
  DollarSign,
  UserCheck,
  BookOpen,
  Tags,
  BarChart3,
  Bell,
  Settings,
  Plus,
  GraduationCap,
  Star,
  MessageCircle,
  TrendingUp,
  Play,
  Search,
  Award,
  MessageSquare,
  Heart,
  ShoppingBag,
  Menu,
  HelpCircle
} from 'lucide-vue-next'

// Types
interface MenuItem {
  type: 'item'
  label: string
  href: string
  icon: string
  badge?: number
  isActive?: boolean
}

interface MenuGroup {
  type: 'group'
  label: string
  items: MenuItem[]
}

interface SharedData {
  auth: {
    user: {
      id: number
      name: string
      email: string
      role: 'admin' | 'instructor' | 'student'
    } | null
  }
}

// Reactive state
const menuItems = ref<MenuGroup[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

// Get user from Inertia
const { props } = usePage<SharedData>()
const user = computed(() => props.auth.user)

// Computed properties
const hasMenuItems = computed(() => menuItems.value.length > 0)
const isEmpty = computed(() => !loading.value && menuItems.value.length === 0)

// Icon resolver method
const resolveIcon = (iconName: string) => {
  const iconMap: Record<string, any> = {
    LayoutDashboard,
    CheckCircle,
    Users,
    DollarSign,
    UserCheck,
    BookOpen,
    Tags,
    BarChart3,
    Bell,
    Settings,
    Plus,
    GraduationCap,
    Star,
    MessageCircle,
    TrendingUp,
    Play,
    Search,
    Award,
    MessageSquare,
    Heart,
    ShoppingBag,
    Menu,
    HelpCircle
  }
  
  const IconComponent = iconMap[iconName]
  
  if (!IconComponent) {
    console.warn(`Icon "${iconName}" not found, using default icon`)
    return HelpCircle
  }
  
  return IconComponent
}

// Methods
const fetchMenuData = async () => {
  try {
    loading.value = true
    error.value = null
    
    const response = await axios.get('/api/sidebar-menu', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      withCredentials: true,
    })

    if (response.data.success) {
      menuItems.value = response.data.data || []
    } else {
      throw new Error(response.data.message || 'Failed to load menu items')
    }
  } catch (err) {
    console.error('Error fetching sidebar menu:', err)
    error.value = err instanceof Error ? err.message : 'Unknown error occurred'
    menuItems.value = []
  } finally {
    loading.value = false
  }
}

const handleNotificationUpdate = () => {
  // Re-fetch menu when notifications are received
  fetchMenuData()
}

const getRoleEmoji = () => {
  if (!user.value) return '🎓'
  
  switch (user.value.role) {
    case 'admin': return '🛡️'
    case 'instructor': return '👨‍🏫'
    case 'student': default: return '🎓'
  }
}

const getRoleTitle = () => {
  if (!user.value) return 'Student Portal'
  
  switch (user.value.role) {
    case 'admin': return 'Admin Panel'
    case 'instructor': return 'Instructor Hub'
    case 'student': default: return 'Student Portal'
  }
}

// Event handler for notification updates
const handleNotificationReceived = () => {
  handleNotificationUpdate()
}

// Lifecycle hooks
onMounted(() => {
  fetchMenuData()
  
  // Listen for custom notification events
  window.addEventListener('notification-received', handleNotificationReceived)
  
  // Setup Echo listeners for real-time updates
  if (user.value) {
    setupUserEchoListeners(user.value.id)
  }
})

onUnmounted(() => {
  // Cleanup event listeners
  window.removeEventListener('notification-received', handleNotificationReceived)
  
  // Cleanup Echo listeners
  if (user.value) {
    cleanupEchoListeners(user.value.id)
  }
})

// Global utility function for triggering sidebar updates
const triggerSidebarUpdate = () => {
  window.dispatchEvent(new CustomEvent('notification-received'))
}

// Expose the trigger function globally
if (typeof window !== 'undefined') {
  (window as any).triggerSidebarUpdate = triggerSidebarUpdate
}
</script>