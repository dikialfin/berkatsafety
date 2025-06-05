<template>
  <Popover class="relative bg-white shadow" style="z-index:99">
    <div class="max-w-7xl px-6 mx-auto">
      <div class="flex items-center justify-between  border-gray-100 py-6 md:justify-start md:space-x-10">
        <div class="flex justify-start lg:w-0 lg:flex-1">
          <a href="/">
            <span class="sr-only">LSK Amdal</span>
            <img class="h-8 w-auto sm:h-10" src="/images/logo-lsk.jpeg" alt="" />
          </a>
        </div>
        <div class="-my-2 -mr-2 md:hidden">
          <PopoverButton class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
            <span class="sr-only">Open menu</span>
            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
          </PopoverButton>
        </div>
        <PopoverGroup as="nav" class="hidden space-x-10 md:flex">
          <a href="/tentang" :class="[$route.name == 'Tentang'?'font-bold text-green-500 ':'','text-base font-medium text-gray-500 hover:text-gray-900']">Tentang</a>

          <Popover class="relative" v-slot="{ open }">
            <PopoverButton :class="[open ? 'text-gray-900' : 'text-gray-500',$route.name.includes('sertifikasi')?'font-bold text-green-500':'', 'group inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900 ']">
              <span>Sertifikasi</span>
              <ChevronDownIcon :class="[open ? 'text-gray-600' : 'text-gray-400', 'ml-2 h-5 w-5 group-hover:text-gray-500']" aria-hidden="true" />
            </PopoverButton>

            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
              <PopoverPanel class="absolute z-10 -ml-4 mt-3 w-screen max-w-[15rem] transform px-2 sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2">
                <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                  <div class="relative grid gap-6 bg-white px-2 py-6">
                    <a href="/daftar-skema" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Daftar Skema</p>
                      </div>
                    </a>
                    <a href="/daftar-penguji" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Daftar Penguji</p>
                      </div>
                    </a>
                    <a href="/daftar-tuk" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Daftar TUK</p>
                      </div>
                    </a>
                  </div>
                </div>
              </PopoverPanel>
            </transition>
          </Popover>

          <Popover class="relative" v-slot="{ open }">
            <PopoverButton :class="[open ? 'text-gray-900' : 'text-gray-500',$route.name.includes('informasi')?'font-bold text-green-500':'', 'group inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900 ']">
              <span>Informasi</span>
              <ChevronDownIcon :class="[open ? 'text-gray-600' : 'text-gray-400', 'ml-2 h-5 w-5 group-hover:text-gray-500']" aria-hidden="true" />
            </PopoverButton>

            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
              <PopoverPanel class="absolute z-10 -ml-4 mt-3 w-screen max-w-[15rem] transform px-2 sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2">
                <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                  <div class="relative grid gap-6 bg-white px-2 py-6">
                    <a href="/tutorial-pendaftaran" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Tutorial Pendaftaran</p>
                      </div>
                    </a>
                    <a href="/jadwal-uji" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Jadwal Uji</p>
                      </div>
                    </a>
                    <a href="/daftar-pemegang" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Daftar Pemegang Sertifikat</p>
                      </div>
                    </a>
                     <a href="/informasi-galeri" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                      <div class="ml-4">
                        <p class="text-base font-medium text-gray-900">Galeri</p>
                      </div>
                    </a>
                  </div>
                </div>
              </PopoverPanel>
            </transition>
          </Popover>

          <a href="/pengaduan" :class="`text-base font-medium text-gray-500 hover:text-gray-900 ${$route.name.includes('pengaduan')?'font-bold text-green-500':''}`">Pengaduan</a>
          <a href="/artikel" :class="`text-base font-medium text-gray-500 hover:text-gray-900 ${$route.name.includes('artikel')?'font-bold text-green-500':''}`">Artikel</a>

          <a href="https://inkalindo.or.id/" target="_blank" class="text-base font-medium text-gray-500 hover:text-gray-900">Inkalindo</a>

        </PopoverGroup>
        <div class="hidden items-center justify-end md:flex md:flex-1 lg:w-0">
          <a href="/login" class="ml-8 inline-flex items-center justify-center whitespace-nowrap rounded-md border border-transparent bg-green-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-600">Login</a>
        </div>
      </div>
    </div>

    <transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="duration-100 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
      <PopoverPanel focus class="absolute inset-x-0 top-0 origin-top-right transform p-2 transition md:hidden">
        <div class="divide-y-2 divide-gray-50 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
          <div class="px-5 pt-5 pb-6">
            <div class="flex items-center justify-between">
              <div>
                <img class="h-8 w-auto" src="/images/logo-lsk.jpeg" alt="Your Company" />
              </div>
              <div class="-mr-2">
                <PopoverButton class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                  <span class="sr-only">Close menu</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </PopoverButton>
              </div>
            </div>
            <div class="mt-6">
              <nav class="grid gap-y-8">
                <a href="/tentang" :class="[$route.name == 'Tentang'?'font-bold text-green-500 ':'','text-base font-medium text-gray-500 hover:text-gray-900']">Tentang</a>

                <Popover class="relative" v-slot="{ open }">
                  <PopoverButton :class="[open ? 'text-gray-900' : 'text-gray-500',$route.name.includes('sertifikasi')?'font-bold text-green-500':'', 'group inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900 ']">
                    <span>Sertifikasi</span>
                    <ChevronDownIcon :class="[open ? 'text-gray-600' : 'text-gray-400', 'ml-2 h-5 w-5 group-hover:text-gray-500']" aria-hidden="true" />
                  </PopoverButton>

                  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                    <PopoverPanel class="absolute z-10 -ml-4 mt-3 w-screen max-w-[15rem] transform px-2 sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2">
                      <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="relative grid gap-6 bg-white px-2 py-6">
                          <a href="/daftar-skema" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Daftar Skema</p>
                            </div>
                          </a>
                          <a href="/daftar-penguji" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Daftar Penguji</p>
                            </div>
                          </a>
                          <a href="/daftar-tuk" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Daftar TUK</p>
                            </div>
                          </a>
                        </div>
                      </div>
                    </PopoverPanel>
                  </transition>
                </Popover>

                <Popover class="relative" v-slot="{ open }">
                  <PopoverButton :class="[open ? 'text-gray-900' : 'text-gray-500',$route.name.includes('informasi')?'font-bold text-green-500':'', 'group inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900 ']">
                    <span>Informasi</span>
                    <ChevronDownIcon :class="[open ? 'text-gray-600' : 'text-gray-400', 'ml-2 h-5 w-5 group-hover:text-gray-500']" aria-hidden="true" />
                  </PopoverButton>

                  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                    <PopoverPanel class="absolute z-10 -ml-4 mt-3 w-screen max-w-[15rem] transform px-2 sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2">
                      <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="relative grid gap-6 bg-white px-2 py-6">
                          <a href="/tutorial-pendaftaran" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Tutorial Pendaftaran</p>
                            </div>
                          </a>
                          <a href="/jadwal-uji" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Jadwal Uji</p>
                            </div>
                          </a>
                          <a href="/daftar-pemegang" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Daftar Pemegang Sertifikat</p>
                            </div>
                          </a>
                          <a href="/informasi-galeri" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                            <div class="ml-4">
                              <p class="text-base font-medium text-gray-900">Galeri</p>
                            </div>
                          </a>
                        </div>
                      </div>
                    </PopoverPanel>
                  </transition>
                </Popover>

                <a href="/pengaduan" :class="`text-base font-medium text-gray-500 hover:text-gray-900 ${$route.name.includes('pengaduan')?'font-bold text-green-500':''}`">Pengaduan</a>
                <a href="https://inkalindo.or.id/" target="_blank" class="text-base font-medium text-gray-500 hover:text-gray-900">Inkalindo</a>

              </nav>
            </div>
          </div>
          <div class="space-y-6 py-6 px-5">
            <div>
              <a href="/login" class="flex w-full items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700">Login</a>
              <p class="mt-6 text-center text-base font-medium text-gray-500">
                Belum punya akun?
                {{ ' ' }}
                <a href="/register" class="text-green-600 hover:text-green-500">Register</a>
              </p>
            </div>
          </div>
        </div>
      </PopoverPanel>
    </transition>
  </Popover>
</template>

<script setup>
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from '@headlessui/vue'
import {
  ArrowPathIcon,
  Bars3Icon,
  BookmarkSquareIcon,
  CalendarIcon,
  ChartBarIcon,
  CursorArrowRaysIcon,
  LifebuoyIcon,
  PhoneIcon,
  PlayIcon,
  ShieldCheckIcon,
  Squares2X2Icon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const solutions = [
  {
    name: 'Analytics',
    description: 'Get a better understanding of where your traffic is coming from.',
    href: '#',
    icon: ChartBarIcon,
  },
  {
    name: 'Engagement',
    description: 'Speak directly to your customers in a more meaningful way.',
    href: '#',
    icon: CursorArrowRaysIcon,
  },
  { name: 'Security', description: "Your customers' data will be safe and secure.", href: '#', icon: ShieldCheckIcon },
  {
    name: 'Integrations',
    description: "Connect with third-party tools that you're already using.",
    href: '#',
    icon: Squares2X2Icon,
  },
  {
    name: 'Automations',
    description: 'Build strategic funnels that will drive your customers to convert',
    href: '#',
    icon: ArrowPathIcon,
  },
]
const callsToAction = [
  { name: 'Watch Demo', href: '#', icon: PlayIcon },
  { name: 'Contact Sales', href: '#', icon: PhoneIcon },
]
const resources = [
  {
    name: 'Help Center',
    description: 'Get all of your questions answered in our forums or contact support.',
    href: '#',
    icon: LifebuoyIcon,
  },
  {
    name: 'Guides',
    description: 'Learn how to maximize our platform to get the most out of it.',
    href: '#',
    icon: BookmarkSquareIcon,
  },
  {
    name: 'Events',
    description: 'See what meet-ups and other events we might be planning near you.',
    href: '#',
    icon: CalendarIcon,
  },
  { name: 'Security', description: 'Understand how we take your privacy seriously.', href: '#', icon: ShieldCheckIcon },
]
const recentPosts = [
  { id: 1, name: 'Boost your conversion rate', href: '#' },
  { id: 2, name: 'How to use search engine optimization to drive traffic to your site', href: '#' },
  { id: 3, name: 'Improve your customer experience', href: '#' },
]
</script>