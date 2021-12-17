<template>

  <div class="shadow drawer drawer-end  h-screen">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle">

    <div class="flex flex-col drawer-content">


      <div class="navbar mb-4 pb-2 flex-row-reverse shadow-lg bg-neutral text-neutral-content">
        <div class="flex-none lg:hidden">
          <label for="my-drawer-3" class="btn btn-square btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block w-6 h-6 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </label>
        </div>
        <div class="flex-none hidden px-2 mx-2 lg:flex">
          <span class="text-lg font-bold">
            daisyUI
          </span>
        </div>


        <div class="flex-1 lg:flex-none">
          <div class="form-control">
            <input type="text" placeholder="Search" class="input input-ghost">
          </div>
        </div>
        <div class="flex-none">
          <button class="btn btn-square btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block w-6 h-6 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
        </div>


        <div class="flex-1 items-stretch">
          <AppHeaderItems/>
        </div>


        <div class="flex-1">
          <button class="btn btn-square btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block w-6 h-6 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
          </button>
        </div>

        <AppHeaderAvatar/>

      </div>


      <div class="flex bg-gray-50  rtl flex-wrap container grid grid-cols-12">
        <Nuxt></Nuxt>
      </div>

    </div>

    <div class="drawer-side">
      <label for="my-drawer-3" class="drawer-overlay"></label>
      <ul class="p-4 overflow-y-auto menu w-80 bg-base-100">
        <AppHeaderItems/>
      </ul>
    </div>

  </div>
</template>
