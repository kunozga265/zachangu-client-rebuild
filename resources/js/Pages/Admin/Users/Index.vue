<template>
    <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manager Users
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1">
                            <div
                                v-for="(_user,index) in $page.props.users.data"
                                :key="_user.id"
                            >
                                <inertia-link
                                    class="cursor-pointer"
                                    :href="route('users.admin.show',{'id':_user.id})"
                                >
                                    <div class="text-base sm:text-md text-gray-800 font-bold ">
                                        <span>{{index+1}}. </span>
                                        {{_user.firstName}} {{_user.lastName}}
                                        <span class="ml-2 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold"> {{_user.loansCount}} {{_user.loansCount==1?'Loan':'Loans'}}</span>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{_user.email}}
                                    </div>
                                    <!--                                    <div class="font-semibold">
                                                                            {{_user.loansCount}} {{_user.loansCount==1?'Loan':'Loans'}}
                                                                        </div>-->
                                </inertia-link>
                                <jet-section-border />
                            </div>

                            <div   v-if="($page.props.users.data).length==0" class="m-2 p-6">
                                <div>No Users found.</div>
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
import JetSectionBorder from '@/Jetstream/SectionBorder'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    name: "Index",
    props:[

    ],
    components:{
        JetSectionBorder,
        JetSecondaryButton,
         AppLayoutAdmin,

    },
    methods: {
        getStatus(progress){
            switch (progress){
                case '0':
                    return 'Finish the applying process';
                    break;
                case '1':
                    return 'Waiting for guarantor to approve';
                    break;
                case '2':
                    return 'Waiting for employer to approve';
                    break;
                case '3':
                    return 'Active';
                    break;
                case '4':
                    return 'Closed';
                    break;
                default:
                    return 'Nothing';
                    break;
            }
        },
        getStatusColor(progress){
            switch (progress){
                case '0':
                    return '#FBBF24';
                    break;
                case '1':
                    return '#FBBF24';
                    break;
                case '2':
                    return '#FBBF24';
                    break;
                case '3':
                    return '#4ADE80';
                    break;
                case '4':
                    return '#EF4444';
                    break;
                default:
                    break;
            }
        },

    }



}
</script>

<style scoped>

</style>
