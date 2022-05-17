<template>
    <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Notifications
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-6 sm:px-20 bg-white border-b border-gray-200">

                        <div
                            class="my-6"
                            v-for="(notificationCompound,index) in notificationCompounds"
                            :key="index"
                        >
<!--                            <div class="text-lg">
                                {{notificationCompound.month}} {{notificationCompound.year}}
                            </div>-->
                            <span class="ml-2 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold">
                                {{notificationCompound.month}} {{notificationCompound.year}}
                            </span>
                            <div
                                class="ml-6"
                                v-for="notifications in notificationCompound.notifications"
                            >
                                <inertia-link v-if="notifications.type==='user_new'" :href="route('users.admin.show',{'id':notifications.user.id})">
                                    <div  class="my-2 flex items-center justify-start" >
                                        <account-plus :size="24" fill-color="#3B82F6"/>
                                        <div class="ml-4">
                                            <div class="text-base md:text-lg"><span class="font-bold">{{ notifications.user.firstName }} {{ notifications.user.lastName }}</span> has just registered an account</div>
                                            <div class="text-xs md:text-sm text-gray-400">{{ notifications.date.formatted }}</div>
                                        </div>
                                    </div>
                                </inertia-link>

                                <inertia-link v-else-if="notifications.type==='loan_new'" :href="route('users.admin.show',{'id':notifications.user.id})">
                                    <div  class="my-2 flex items-center justify-start">
                                        <book-plus :size="24" fill-color="#FBBF24"/>
                                        <div class="ml-4">
                                            <div class="text-base md:text-lg"><span class="font-bold">{{ notifications.user.firstName }} {{ notifications.user.lastName }}</span> has submitted details for a new loan application</div>
                                            <div class="text-xs md:text-sm text-gray-400">{{ notifications.date.formatted }}</div>
                                        </div>
                                    </div>
                                </inertia-link>

                                <inertia-link v-else-if="notifications.type==='loan_apply'" :href="route('loan.admin.show',{code:notifications.contents.loanCode})">
                                    <div  class="my-2 flex items-center justify-start">
                                        <book-check :size="24" fill-color="#4ADE80"/>
                                        <div class="ml-4">
                                            <div class="text-base md:text-lg"><span class="font-bold">{{ notifications.user.firstName }} {{ notifications.user.lastName }}</span> has applied for a MK{{ notifications.contents.amount }} loan</div>
                                            <div class="text-xs md:text-sm text-gray-400">{{ notifications.date.formatted }}</div>
                                        </div>
                                    </div>
                                </inertia-link>

                                <employer-notification
                                    v-else-if="notifications.type==='employer_register'"
                                    :notifications="notifications"
                                />

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </app-layout-admin>
</template>

<script>
    import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin";
    import AccountPlus from 'vue-material-design-icons/AccountPlus.vue'
    import BookPlus from 'vue-material-design-icons/BookPlus.vue'
    import BookCheck from 'vue-material-design-icons/BookCheck.vue'
    import CardAccountDetails from 'vue-material-design-icons/CardAccountDetails.vue'
    import EmployerNotification from "@/Pages/Admin/Components/EmployerNotification";


    export default {
        props:[

        ],
        components: {
            AppLayoutAdmin,
            AccountPlus,
            BookPlus,
            BookCheck,
            CardAccountDetails,
            EmployerNotification,
        },
          data() {
            return {

            }
        },
        computed:{
            notificationCompounds(){
                let unsorted=this.$page.props.notifications.data
                let sorted=[]
                let index=0

                if(unsorted){
                    if (unsorted.length!==0){
                        let currentMonth=unsorted[0].date.month
                        let currentYear=unsorted[0].date.year

                        for (let item in unsorted){
                            if (item==='0'){
                                sorted.push({
                                    month: currentMonth,
                                    year: currentYear,
                                    notifications:[unsorted[item]]
                                })
                            }else{
                                if (currentMonth===unsorted[item].date.month && currentYear===unsorted[item].date.year){
                                    sorted[index].notifications.push(unsorted[item])
                                }else{
                                    index++
                                    currentMonth=unsorted[item].date.month
                                    currentYear=unsorted[item].date.year

                                    sorted.push({
                                        month: currentMonth,
                                        year: currentYear,
                                        notifications:[unsorted[item]]
                                    })
                                }
                            }
                        }
                        return sorted
                    }else
                        return []
                }else
                    return []
            }

        },
        methods:{

        }
    }
</script>
