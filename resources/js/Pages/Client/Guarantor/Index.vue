<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Guaranteed Loans
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1">
                            <div
                                v-for="loan in $page.props.loans"
                                :key="loan.id"
                            >
                                <inertia-link
                                    class="cursor-pointer m-2 p-6 rounded active:bg-gray-200 transition"
                                    :href="route('guarantor.show',{'code':loan.code})"
                                >
                                    <div class="text-4xl text-gray-800 font-bold ">MK{{ loan.amount }}</div>
                                    <div class=" flex justify-start">
                                        <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                        <span class="ml-2 text-gray-600">{{getStatus(loan.progress)}}</span>
                                    </div>
                                    <div class="text-gray-400">Borrower: {{(loan.firstName +" "+loan.lastName )}}</div>
                                    <div class="text-gray-400">Applied on: {{(loan.appliedDate)}}</div>
                                </inertia-link>
                                 <jet-section-border />
                            </div>
                            <div   v-if="($page.props.loans).length==0" class="m-2 p-6">
                                <div>No Loans found.</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </app-layout>

</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'

export default {
    name: "Index",
    props:[

    ],
    components:{
        JetSectionBorder,
        AppLayout,
         AlertCircle
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
