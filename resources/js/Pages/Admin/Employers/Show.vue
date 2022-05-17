<template>
     <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $page.props.employer.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">
                        <div>
                            <inertia-link  :href="route('employee.admin.new',{id:employer.id})">
                                <jet-button class="ml-0 m-2"  >
                                    + New Employee
                                </jet-button>
                            </inertia-link>


                            <jet-secondary-button @click.native="dialog=true" class="ml-0 sm:m-2">
                                Profile Information
                            </jet-secondary-button>


                               <jet-dialog-modal :show="dialog" @close="closeModal">
                                <template #title>
                                    Employer Profile
                                </template>

                                <template #content class="p-6 md:p-12">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="" >
                                            <div>{{employer.name}}</div>
                                            <div class="text-sm text-gray-400">Name</div>
                                        </div>
                                        <div class="mt-4 md:ml-4 md:mt-0">
                                            <div>{{employer.email}}</div>
                                            <div class="text-sm text-gray-400">Email</div>
                                        </div>

                                        <div class="mt-4 md:col-span-2">
                                            <div>{{employer.physicalAddressName}} P. O. Box {{employer.physicalAddressBox}} {{employer.physicalAddressLocation}}</div>
                                            <div class="text-sm text-gray-400">Address</div>
                                        </div>

                                        <div class="mt-6">
                                            <a :href="$page.props.publicPath+employer.letter" target="_blank"> <jet-secondary-button>View File</jet-secondary-button></a>
                                            <div class="mt-2 text-sm text-gray-400">Agreement Letter</div>
                                        </div>

                                    </div>

                                    <div class="mt-6 text-lg text-gray-400 ">Proxy Details</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="" >
                                            <div>{{employer.proxyName}}</div>
                                            <div class="text-sm text-gray-400">Proxy Name</div>
                                        </div>
                                        <div class="mt-4 md:ml-4 md:mt-0">
                                            <div>{{employer.proxyEmail}}</div>
                                            <div class="text-sm text-gray-400">Proxy Email</div>
                                        </div>
                                        <div class="mt-4">
                                            <div>+265 {{employer.proxyPhoneNumber}}</div>
                                            <div class="text-sm text-gray-400">Proxy Phone Number</div>
                                        </div>

                                    </div>

                                </template>

                                <template #footer>
                                    <jet-button @click.native="edit">
                                        Edit
                                    </jet-button>
                                    <jet-secondary-button @click.native="closeModal">
                                        Close
                                    </jet-secondary-button>
                                </template>
                            </jet-dialog-modal>

                            <jet-section-border class="mt-4" />
                        </div>
                        <div class="grid grid-cols-1">
                            <employee
                                v-for="(employee,index) in $page.props.employees.data"
                                :key="employee.id"
                                :employee="employee"
                                :index="index"
                          />

                            <div   v-if="($page.props.employees).length==0" class="m-2 p-6">
                                <div>No Employees found.</div>
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
import Employee from './Partials/Employee'
import JetDialogModal from '@/Jetstream/DialogModal'

export default {
    name: "Show",
    props:[
        'employer'
    ],
    components:{
        JetSectionBorder,
        JetButton,
        JetSecondaryButton,
         AppLayoutAdmin,
        JetDialogModal,
        Employee

    },
      data() {
        return {
            dialog:false,
        }
    },
    methods: {
        edit(){
            this.$inertia.get(route('employer.admin.edit',{id:this.employer.id}))
        },
       closeModal() {
                this.dialog = false
            },

    }



}
</script>

<style scoped>

</style>
