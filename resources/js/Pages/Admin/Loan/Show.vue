<template>
    <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Loan Details
            </h2>
        </template>

        <div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">
                            <div>

                                <div v-if="loan.progress==2">
                                    <jet-button @click.native="approve" :disabled="loading">
                                        Approve
                                    </jet-button>

                                    <jet-button-secondary @click.native="reject" :disabled="loading">
                                        Reject
                                    </jet-button-secondary>
                                </div>

                                <div v-else>
                                    <jet-button @click.native="close" :disabled="loading">
                                        Close
                                    </jet-button>

                                    <jet-button-secondary @click.native="_default" :disabled="loading">
                                        Default
                                    </jet-button-secondary>
                                </div>



                                <jet-section-border />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2">

                                <div class=" flex items-center justify-start">
                                    <alert-circle :size="48" :fill-color="getStatusColor(loan.progress)"/>
                                    <div class="ml-4">
                                        <div class="text-4xl font-bold">MK{{numberWithCommas(loan.amount)}}</div>
                                        <div class="text-gray-400">{{getStatus(loan.progress)}}</div>
                                    </div>
                                </div>

                                <div class="mt-4 md:ml-4 md:mt-4 p-6 rounded border border-gray-200 bg-gray-100 text-center">
                                    <div class="text-3xl text-gray-800 ">{{loan.code}}</div>
                                    <div class="text-gray-400">Loan Code</div>
                                </div>

                            </div>

                            <jet-section-border />

                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <div class="m-4" >
                                    <div>{{loan.appliedDate}}</div>
                                    <div class="text-sm text-gray-400">Applied Date</div>
                                </div>
                                <div class="m-4" v-show="loan.approvedDate">
                                    <div>{{loan.approvedDate}}</div>
                                    <div class="text-sm text-gray-400">Approved Date</div>
                                </div>
                                <div class="m-4" v-show="loan.dueDate">
                                    <div>{{loan.dueDate}}</div>
                                    <div class="text-sm text-gray-400">Due Date</div>
                                </div>
<!--                                <div class="m-4" v-show="loan.guarantorDate">
                                    <div>{{loan.guarantorDate}}</div>
                                    <div class="text-sm text-gray-400">Guarantor Approval</div>
                                </div>-->


                                <div class="m-4" v-show="loan.closedDate">
                                    <div>{{loan.closedDate}}</div>
                                    <div class="text-sm text-gray-400">Closed Date</div>
                                </div>

                            </div>

                            <jet-section-border />

                            <div class="p-12 w-full flex justify-center">
                                <div>
                                    <div class="text-2xl sm:text-5xl md:text-7xl text-center font-extrabold mt-6">{{ loan.score }}%</div>
                                </div>
                            </div>

<!--                             Personal Information-->
                            <div class="">
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">1</div>
                                    <span class="text-xl ml-4">Personal Information</span>
                                </div>
                                <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">
                                    <div class="my-4 md:col-span-2 flex items-center justify-center">
                                        <img class="max-w-fit md:max-w-sm" :src="url(loan.photo.form)" alt="Photo">
                                        <!--                                        <div class="text-sm text-gray-400">Photo</div>-->

                                    </div>





                                        <div class="">
                                            <span class="text-lg text-gray-400">First Name</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.firstName.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.firstName.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.firstName.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>


                                        <div class=" mt-4">
                                            <span class="text-lg text-gray-400">Middle Name</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.middleName.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.middleName.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.middleName.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Last Name</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.lastName.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.lastName.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.lastName.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Phone Number Mobile</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.phoneNumberMobile.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>+265 {{loan.phoneNumberMobile.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>+265 {{loan.phoneNumberMobile.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Phone Number Work</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.phoneNumberWork.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>+265 {{loan.phoneNumberWork.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>+265 {{loan.phoneNumberWork.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Email</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.email.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">

                                        <div class="mt-2" >
                                            <div>{{loan.email.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.email.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class=" mt-4">
                                            <span class="text-lg text-gray-400">Physical Address</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.physicalAddress.score}}%</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">

                                        <div class="mt-2" >
                                            <div>{{computePhysicalAddress(loan.physicalAddress.form)}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{computePhysicalAddress(loan.physicalAddress.employee)}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Identification</span>
                                        </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.nationalId}}</div>
                                            <div class="text-sm text-gray-400">National Id</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <a :href="url(loan.nationalIdFile)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>
                                            <div class="mt-2 text-sm text-gray-400">National Id</div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <!-- Workplace Information -->
                            <div class="mt-6">
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">2</div>
                                    <span class="text-xl ml-4">Workplace Information</span>
                                </div>
                                <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                        <!--                                        <div class="my-4 md:col-span-2 flex items-center justify-center">-->
                                        <!--                                            <img class="max-w-fit md:max-w-sm" :src="url(loan.photo)" alt="Photo">-->
                                        <!--                                            &lt;!&ndash;                                        <div class="text-sm text-gray-400">Photo</div>&ndash;&gt;-->

                                        <!--                                        </div>   -->
                                        <div class="">
                                            <span class="text-lg text-gray-400">Position</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.position.score}}%</span>
                                        </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.position.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.position.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Work Address</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.workAddress.score}}%</span>
                                        </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{computeWorkAddress(loan.workAddress.form)}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{computeWorkAddress(loan.workAddress.employee)}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>


                                        <div class="col-span-2 mt-4">
                                            <span class="text-lg text-gray-400">Contract Expiry Date</span>
                                            <span class="ml-2 rounded-full p-1 bg-gray-200 text-gray-600 text-sm font-bold">{{loan.contractDuration.score}}%</span>
                                        </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{loan.contractDuration.form}}</div>
                                            <div class="text-sm text-gray-400">Loan Form</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <div>{{loan.contractDuration.employee}}</div>
                                            <div class="text-sm text-gray-400">Employee Data</div>
                                        </div>
                                    </div>
<!--                                        <div class="mt-2 col-span-2">-->
<!--                                            <a :href="url(loan.contract)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>-->
<!--                                            <div class="mt-2 text-sm text-gray-400">Contract File</div>-->
<!--                                        </div>-->

                                        <div class="mt-4">
                                            <span class="text-lg text-gray-400">Payment Details</span>
                                        </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-2" >
                                            <div>{{computePayDay(loan.payDay)}}</div>
                                            <div class="text-sm text-gray-400">Pay Day</div>
                                        </div>
                                        <div class="mt-2 md:ml-4" >
                                            <a :href="url(loan.paySlip)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>
                                            <div class="mt-2 text-sm text-gray-400">Pay Slip</div>
                                        </div>
                                        <div class="mt-2" >
                                            <div>MK{{loan.net}}</div>
                                            <div class="text-sm text-gray-400">Net Pay</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Borrower License Agreement -->
                            <div class="mt-6">
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">3</div>
                                    <span class="text-xl ml-4">Loan Agreements</span>
                                </div>
                                <div class="mx-2 p-6  border-l-2 border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="">
                                            <div> <jet-button-secondary class="mt-4" @click.native="termsAndConditionsDialog=true">View File</jet-button-secondary></div>
                                            <div class="mt-2 text-sm text-gray-600">Employee Loan Agreement File</div>
                                            <jet-dialog-modal :show="termsAndConditionsDialog" @close="closeModal">
                                                <template #title>
                                                    Zachangu Microfinance Agency
                                                </template>

                                                <template #content>
                                                    <div v-html="loan.termsAndConditions"></div>
                                                </template>

                                                <template #footer>
                                                    <jet-button-secondary @click.native="closeModal">
                                                        Close
                                                    </jet-button-secondary>
                                                </template>
                                            </jet-dialog-modal>
                                        </div>
<!--                                        <div class="md:ml-4" >
                                            <div> <jet-button-secondary class="mt-4" @click.native="termsAndConditionsGuarantorDialog=true">View File</jet-button-secondary></div>
                                            <div class="mt-2 text-sm text-gray-600">Guarantor License Agreement File</div>

                                            <jet-dialog-modal :show="termsAndConditionsGuarantorDialog" @close="closeGuarantorModal">
                                                <template #title>
                                                    Zachangu Microfinance Agency
                                                </template>

                                                <template #content>
                                                    <div v-html="loan.termsAndConditionsGuarantor"></div>
                                                </template>

                                                <template #footer>
                                                    <jet-button-secondary @click.native="closeGuarantorModal">
                                                        Close
                                                    </jet-button-secondary>
                                                </template>
                                            </jet-dialog-modal>
                                        </div>-->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </app-layout-admin>



</template>

<script>
import JetButtonSecondary from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin";


export default {
    name: "Show",
    props:[
        '_loan',
    ],
    components:{
         AppLayoutAdmin,
        JetButton,
        JetButtonSecondary,
        JetInput,
        JetDialogModal,
        JetSectionBorder,
        AlertCircle
    },
    data() {
        return {
            termsAndConditionsCheck:false,
            termsAndConditionsDialog:false,
            termsAndConditionsGuarantorDialog:false,
            errorMessage:'',
            errorSection:'',
            loading:false
        }
    },
    computed:{
        loan:function () {
            return this._loan.data;
        },
        score:function () {
            return this.loan.score>0;
        },


    },
    methods:{
        numberWithCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        approve:function () {
            this.loading=true
            this.$inertia.post(route('loan.admin.approve',{code:this.loan.code}),{
                onFinish:()=>this.loading=false
            })
        },
        reject:function () {
            this.loading=true
            this.$inertia.post(route('loan.admin.reject',{code:this.loan.code}),{
                onFinish:()=>this.loading=false
            })
        },
        close:function () {
            this.loading=true
            this.$inertia.post(route('loan.admin.close',{code:this.loan.code}),{
                onFinish:()=>this.loading=false
            })
        },
        _default:function () {
            this.loading=true
            this.$inertia.post(route('loan.admin.default',{code:this.loan.code}),{
                onFinish:()=>this.loading=false
            })
        },
        computePhysicalAddress(physicalAddress){
            let box=(physicalAddress.box).length!==0?'P. O. Box '+physicalAddress.box:'';
            return physicalAddress.name+' '+ box + ' '+ physicalAddress.location;
        },
        computeWorkAddress(workAddress){
            let box=(workAddress.box).length!==0?'P. O. Box '+workAddress.box:'';
            return /*this.workAddressName+' '+*/ box + ' '+ workAddress.location;
        },

        computeHumanReadable:function(duration){
            let today=(new Date().getTime())/1000;
            let text='';
            duration=duration-today;

//                duration=duration/1000; //seconds
            duration=duration/60; //minutes
            duration=duration/60; //hours
            let days=duration/24; //days
            let months=days/30; //months

            if(months>=1){
                text=Math.floor(months)===1?'month':'months';
                return  Math.floor(months)+' '+text;
            }
            else{
                text=Math.floor(days)===1?'day':'days';
                return  Math.floor(days)+' '+text;
            }
        },
        computePayDay(payDay){
            let day=''
            switch(payDay){
                case 1:
                    day='st';
                    break;
                case 21:
                    day='st';
                    break;
                case 31:
                    day='st';
                    break;
                case 2:
                    day= 'nd';
                    break;
                case 22:
                    day= 'nd';
                    break;
                case 3:
                    day='rd';
                    break;
                case 23:
                    day='rd';
                    break;
                default:
                    day='th';
                    break;
            }
            return payDay + day + ' of the month';
        },
        url(path){
            return this.$page.props.publicPath+path
        },
        closeModal() {
            this.termsAndConditionsDialog = false
        },
        closeGuarantorModal() {
            this.termsAndConditionsGuarantorDialog = false
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
