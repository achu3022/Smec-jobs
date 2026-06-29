<template>
  <NuxtLayout name="employer">
    <div class="h-[calc(100vh-80px)] flex flex-col -mx-4 -mt-4 sm:mx-0 sm:mt-0 sm:h-[calc(100vh-120px)] bg-white sm:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
      
      <div class="flex h-full">
        <!-- Sidebar: Conversations List -->
        <div class="w-full md:w-80 lg:w-96 border-r border-slate-200 flex flex-col bg-slate-50/50" :class="{ 'hidden md:flex': activeChat }">
          
          <div class="p-4 border-b border-slate-200 bg-white">
            <h2 class="text-xl font-bold text-slate-800">Messages</h2>
            <div class="mt-4 relative">
              <input type="text" placeholder="Search messages..." class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-transparent focus:bg-white focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 rounded-xl text-sm transition-all">
              <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
          </div>

          <div class="flex-1 overflow-y-auto custom-scrollbar">
            <div v-if="pending" class="p-8 text-center text-slate-500">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
            </div>
            <div v-else-if="!groupedMessages || Object.keys(groupedMessages).length === 0" class="p-8 text-center text-slate-500 text-sm font-medium">
              No conversations yet.
            </div>
            <ul v-else class="divide-y divide-slate-100">
              <li v-for="(chat, userId) in groupedMessages" :key="userId" 
                @click="selectChat(userId)"
                class="p-4 hover:bg-white cursor-pointer transition-colors relative"
                :class="{ 'bg-white border-l-4 border-l-indigo-600': activeChat === userId }">
                
                <div class="flex gap-3">
                  <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-bold flex-shrink-0">
                    {{ chat.user.name?.charAt(0) || 'U' }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                      <h4 class="font-bold text-slate-900 truncate text-sm" :class="{ 'text-indigo-900': activeChat === userId }">{{ chat.user.name }}</h4>
                      <span class="text-[10px] text-slate-400 font-medium shrink-0 ml-2">
                        {{ new Date(chat.lastMessage.created_at).toLocaleDateString() }}
                      </span>
                    </div>
                  <div class="flex justify-between items-end">
                    <div class="min-w-0 pr-2">
                      <p class="text-xs text-slate-500 truncate mb-1">Re: <span class="font-medium">{{ chat.application?.job?.title || 'General' }}</span></p>
                      <p class="text-xs text-slate-600 truncate" :class="{ 'font-bold text-slate-900': chat.unreadCount > 0 }">
                        {{ chat.lastMessage.sender_id === authStore.user?.id ? 'You: ' : '' }}{{ chat.lastMessage.content }}
                      </p>
                    </div>
                    <div v-if="chat.unreadCount > 0" class="shrink-0 bg-indigo-600 text-white text-[10px] font-bold h-5 min-w-[20px] px-1.5 rounded-full flex items-center justify-center">
                      {{ chat.unreadCount }}
                    </div>
                  </div>
                </div>
              </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col bg-white" :class="{ 'hidden md:flex': !activeChat }">
          
          <div v-if="!activeChat" class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-slate-50/50">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-300">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">Your Messages</h3>
            <p class="text-slate-500 max-w-sm">Select a conversation from the sidebar to view messages or start communicating with a candidate.</p>
          </div>

          <template v-else>
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-200 flex justify-between items-center bg-white shrink-0">
              <div class="flex items-center gap-3">
                <button @click="activeChat = null" class="md:hidden p-2 -ml-2 text-slate-400 hover:text-slate-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-bold">
                  {{ groupedMessages[activeChat]?.user?.name?.charAt(0) || 'U' }}
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 leading-tight">{{ groupedMessages[activeChat]?.user?.name }}</h3>
                  <p class="text-xs text-slate-500 font-medium">Applicant for <span class="text-slate-700">{{ groupedMessages[activeChat]?.application?.job?.title }}</span></p>
                </div>
              </div>
              <button class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg text-sm font-bold transition-colors">
                View Profile
              </button>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50 custom-scrollbar" id="chat-container">
              
              <div class="text-center">
                <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Start of Conversation</span>
              </div>

              <div v-for="msg in groupedMessages[activeChat]?.messages" :key="msg.id" 
                class="flex w-full" :class="msg.sender_id === authStore.user?.id ? 'justify-end' : 'justify-start'">
                
                <div class="max-w-[75%]">
                  <div class="p-4 rounded-2xl" :class="msg.sender_id === authStore.user?.id ? 'bg-indigo-600 text-white rounded-tr-none shadow-sm shadow-indigo-200' : 'bg-white border border-slate-200 text-slate-800 rounded-tl-none shadow-sm'">
                    <p class="text-sm whitespace-pre-wrap">{{ msg.content }}</p>
                  </div>
                  <p class="text-[10px] text-slate-400 mt-1 font-medium px-1" :class="msg.sender_id === authStore.user?.id ? 'text-right' : 'text-left'">
                    {{ new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Chat Input -->
            <div class="p-4 bg-white border-t border-slate-200 shrink-0">
              <form @submit.prevent="sendReply" class="flex items-end gap-3">
                <div class="flex-1 relative">
                  <textarea v-model="newMessage" rows="1" placeholder="Type your message..." 
                    class="w-full resize-none rounded-xl border border-slate-300 py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm max-h-32"
                    @keydown.enter.prevent="sendReply"
                  ></textarea>
                </div>
                <button type="submit" :disabled="!newMessage.trim() || sending" 
                  class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white h-[46px] w-[46px] rounded-xl flex items-center justify-center transition-colors shadow-sm shrink-0">
                  <svg v-if="sending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                  <svg v-else class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
              </form>
            </div>
          </template>

        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, watch, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

useSeoMeta({
  title: 'Messages | Employer Dashboard'
})

const authStore = useAuthStore()
const activeChat = ref<any>(null)
const newMessage = ref('')
const sending = ref(false)

const { data: messages, pending, refresh } = await useFetch<any[]>('http://127.0.0.1:8000/api/employer/messages', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

// Group messages by the "other" user
const groupedMessages = computed(() => {
  if (!messages.value) return {}
  
  const groups: Record<string, any> = {}
  
  // Sort messages oldest to newest so they appear correctly in chat
  const sorted = [...messages.value].sort((a, b) => new Date(a.created_at).getTime() - new Date(b.created_at).getTime())

  sorted.forEach(msg => {
    // Determine who the other person is
    const otherUserId = msg.sender_id === authStore.user?.id ? msg.receiver_id : msg.sender_id
    const otherUser = msg.sender_id === authStore.user?.id ? msg.receiver : msg.sender
    
    if (!groups[otherUserId]) {
      groups[otherUserId] = {
        user: otherUser,
        application: msg.application,
        messages: [],
        lastMessage: null,
        unreadCount: 0
      }
    }
    
    groups[otherUserId].messages.push(msg)
    groups[otherUserId].lastMessage = msg 
    
    if (!msg.read_at && msg.receiver_id === authStore.user?.id) {
      groups[otherUserId].unreadCount++
    }
  })
  
  return groups
})

const selectChat = async (userId: string | number) => {
  activeChat.value = userId
  scrollToBottom()
  
  // Mark as read
  if (groupedMessages.value[userId]?.unreadCount > 0) {
    try {
      await $fetch(`http://127.0.0.1:8000/api/employer/messages/${userId}/read`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      await refresh()
    } catch (e) {
      console.error(e)
    }
  }
}

const scrollToBottom = () => {
  nextTick(() => {
    const el = document.getElementById('chat-container')
    if (el) el.scrollTop = el.scrollHeight
  })
}

const sendReply = async () => {
  if (!newMessage.value.trim() || !activeChat.value || sending.value) return
  
  sending.value = true
  const chat = groupedMessages.value[activeChat.value]
  
  try {
    await $fetch('http://127.0.0.1:8000/api/employer/messages', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: {
        receiver_id: activeChat.value,
        application_id: chat?.application?.id || null,
        content: newMessage.value.trim()
      }
    })
    newMessage.value = ''
    await refresh()
    scrollToBottom()
  } catch (e) {
    alert('Failed to send message.')
  } finally {
    sending.value = false
  }
}

watch(messages, () => {
  if (activeChat.value) {
    scrollToBottom()
  }
})

let pollInterval: any
onMounted(() => {
  pollInterval = setInterval(() => {
    refresh()
  }, 5000)
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
</style>
