<template>
     <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ _user.firstName }} {{ _user.lastName }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">
                        <div>

                            <jet-secondary-button @click.na.native="dialog=true">
                                Profile Information
                            </jet-secondary-button>

                            <jet-dialog-modal :show="dialog" @close="closeModal">
                                <template #title>
                                    User Profile
                                </template>

                                <template #content class="p-6 md:p-12">
                                    <!-- Personal Information -->
                                    <div >

                                        <div class="grid grid-cols-1 md:grid-cols-2">
                                            <div class="mt-4" >
                                                <div>{{_user.firstName}}</div>
                                                <div class="text-sm text-gray-400">First Name</div>
                                            </div>
                                            <div class="mt-4 md:ml-4" >
                                                <div>{{_user.middleName}}</div>
                                                <div class="text-sm text-gray-400">Middle Name</div>
                                            </div>
                                            <div class="mt-4 ">
                                                <div>{{_user.lastName}}</div>
                                                <div class="text-sm text-gray-400">Last Name</div>
                                            </div>
                                            <div class="mt-4 md:ml-4">
                                                <div>{{_user.email}}</div>
                                                <div class="text-sm text-gray-400">Email</div>
                                            </div>
                                            <div class="mt-4 ">
                                                <div>{{_user.nationalId}}</div>
                                                <div class="text-sm text-gray-400">National Id</div>
                                            </div>
                                            <div v-if="_user.address" class="mt-4 md:ml-4">
                                                <div>{{_user.address}}</div>
                                                <div class="text-sm text-gray-400">Address</div>
                                            </div>
                                        </div>

                                    </div>

                                </template>

                                <template #footer>
                                    <jet-secondary-button @click.native="closeModal">
                                        Close
                                    </jet-secondary-button>
                                </template>
                            </jet-dialog-modal>


                            <jet-section-border class="mt-4" />
                        </div>
                        <div class="grid grid-cols-1">
                            <loan
                                v-for="loan in $page.props.loans"
                                :key="loan.id"
                                :loan="loan"
                            />

                            <div   v-if="($page.props.loans).length==0" class="m-2 p-6">
                                <div>No Loans found.</div>
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
import JetButton from '@/Jetstream/Button'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDialogModal from '@/Jetstream/DialogModal'
import Loan from '@/Pages/Admin/Components/Loan'

export default {
    name: "Show",
    props:[
        '_user'

    ],
    components:{
        JetSectionBorder,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        AppLayoutAdmin,
        Loan,


    },
     data() {
        return {
            dialog:false,
        }
    },
    methods: {
        closeModal() {
            this.dialog = false
        },
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
                case '5':
                    return 'Defaulted';
                    break;
                case '6':
                    return 'Over Due';
                    break;
                case '7':
                    return 'Rejected';
                    break;
                default:
                    return '-';
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
                default:
                    return '#EF4444';
                    break;
            }
        },

    }



}
</script>

<style scoped>

</style>
