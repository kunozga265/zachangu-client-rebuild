<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <inertia-link :href="route('dashboard.admin')">
                                    <jet-application-mark class="block h-9 w-auto" />
                                </inertia-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('dashboard.admin')" :active="route().current('dashboard.admin')">
                                    Dashboard
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('employer.admin.index')" :active="route().current('employer.admin.index')">
                                    Employers
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('users.admin.index')" :active="route().current('users.admin.index')">
                                    Users
                                </jet-nav-link>
                            </div>
<!--                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:items-center border-b-2 border-transparent hover:border-gray-300">
                                <jet-dropdown align="right" width="48" class=" ">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                Users

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <jet-dropdown-link :href="route('users.index',{role:'borrower'})">
                                            {{$page.props.usersCount.borrowers ==1?'Borrower':'Borrowers'}} ({{ $page.props.usersCount.borrowers }})
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('users.index',{role:'guarantor'})">
                                            {{$page.props.usersCount.guarantors ==1?'Guarantor':'Guarantors'}} ({{ $page.props.usersCount.guarantors }})
                                        </jet-dropdown-link>

                                    </template>
                                </jet-dropdown>
                            </div>-->
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">

                            <!-- Notifications Icon -->
                            <inertia-link :href="route('notifications.admin')">
                                <div class="ml-3 relative hover:cursor-pointer">
                                    <div v-show="$page.props.notificationsCount > 0" style="font-size:10px" class="absolute right-0 h-4 w-4 rounded-full text-white font-bold grid justify-items-center text-xs bg-red-500 flex items-center ">
                                        {{$page.props.notificationsCount}}
                                    </div>
                                    <div class="h-9 w-9 rounded-full hover:bg-gray-100  grid justify-items-center flex items-center">
                                        <img style="height:28px" :src="$page.props.publicPath+'images/notifications.svg'" alt="Notifications Icon">
                                    </div>
                                </div>
                            </inertia-link>


                            <!-- Settings Dropdown -->
                            <div class="ml-1 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.user.firstName + " " + $page.props.user.lastName }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show.admin')">
                                            Profile
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                Logout
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <!-- Notifications Icon -->
                            <inertia-link :href="route('notifications.admin')">
                                <div class="ml-3 relative hover:cursor-pointer">
                                    <div v-show="$page.props.notificationsCount > 0" style="font-size:10px" class="absolute right-0 h-4 w-4 rounded-full text-white font-bold grid justify-items-center text-xs bg-red-500 flex items-center ">
                                        {{$page.props.notificationsCount}}
                                    </div>
                                    <div class="h-9 w-9 rounded-full hover:bg-gray-100  grid justify-items-center flex items-center">
                                        <img style="height:28px" :src="$page.props.publicPath+'images/notifications.svg'" alt="Notifications Icon">
                                    </div>
                                </div>
                            </inertia-link>

                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="ml-1 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <jet-responsive-nav-link :href="route('dashboard.admin')" :active="route().current('dashboard.admin')">
                            Dashboard
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('employer.admin.index')" :active="route().current('employer.admin.index')">
                            Employers
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('users.admin.index')" :active="route().current('users.admin.index')">
                            Users
                        </jet-responsive-nav-link>
<!--                        <jet-responsive-nav-link :href="route('users.index',{role:'borrower'})">
                            {{$page.props.usersCount.borrowers ==1?'Borrower':'Borrowers'}} ({{ $page.props.usersCount.borrowers }})
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('users.index',{role:'guarantor'})">
                            {{$page.props.usersCount.guarantors ==1?'Guarantor':'Guarantors'}} ({{ $page.props.usersCount.guarantors }})
                        </jet-responsive-nav-link>-->
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800">{{ $page.props.user.firstName + " " + $page.props.user.lastName }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <jet-responsive-nav-link :href="route('profile.show.admin')" :active="route().current('profile.show.admin')">
                                Profile
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link as="button">
                                    Logout
                                </jet-responsive-nav-link>
                            </form>

                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple>
            </portal-target>
        </div>
    </div>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

    export default {
        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    }
</script>
