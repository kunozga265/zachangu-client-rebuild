<template>

    <div>
        <div class="flex items-center justify-between flex-wrap" v-if="loan.progress==1 || loan.progress ==2">
            <div class="w-0 flex-1 flex items-center min-w-0 bg-gray-600 p-6">
                        <span class="flex p-2 rounded-lg bg-yellow-400">
<!--                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>-->

                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>

                <p class="ml-3 font-medium text-sm text-white" v-if="loan.progress==1">
                    Waiting for a guarantor to approve the loan. Copy and send the loan code to your prospective guarantor and they should log into their account and guarantee the loan.
                </p>
                <p class="ml-3 font-medium text-sm text-white" v-else>
                    An email has been sent to your employer {{employer.proxyName}} to approve your status of employment with {{employer.name}}. You can follow up with {{employer.proxyName}} to speed up your approval process.
                </p>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                        <div class="block lg:flex mb-4">

                            <div class=" flex items-center justify-start">
                                <alert-circle :size="48" :fill-color="getStatusColor(loan.progress)"/>
                                <div class="ml-4">
                                    <div class="text-4xl font-bold">MK{{numberWithCommas(loan.amount)}}</div>
                                    <div class="text-gray-400">{{getStatus(loan.progress)}}</div>
                                </div>
                            </div>
                            <div v-if="loan.paymentsMade < loan.payments && loan.progress==3 && loan.schedule" class="flex">
                                <div class="lg:ml-8 lg:border-l">
                                    <div class="lg:ml-8 m-2 text-sm text-gray-400 block block sm:flex lg:block xl:flex">
                                        Monthly Payment
                                        <div>
                                            <span class="xl:ml-1 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold">
                                                MK{{numberWithCommas(payment.monthlyPayment.toFixed(2))}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lg:ml-8 m-2 text-sm text-gray-400 block block sm:flex lg:block xl:flex">
                                        Next Payment Date
                                        <div>
                                            <span class="xl:ml-1 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold">
                                                {{payment.calculatedDueDate}}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="ml-8 border-l">
                                    <div class="ml-8 m-2 text-sm text-gray-400 block block sm:flex lg:block xl:flex">
                                        Balance
                                        <div>
                                            <span class="xl:ml-1 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold">
                                                MK{{numberWithCommas(Math.abs(paymentBalance).toFixed(2))}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-8 m-2 text-sm text-gray-400 block block sm:flex lg:block xl:flex">
                                        Payments Made
                                        <div>
                                            <span class="xl:ml-1 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold">
                                                {{loan.paymentsMade}}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>

<!--
                            <div class="mt-4 md:ml-4 md:mt-4 p-6 rounded border border-gray-200 bg-gray-100 text-center">
                                <div class="text-3xl text-gray-800 ">{{loan.code}}</div>
                                <div class="text-gray-400">Loan Code</div>
                            </div>
-->

                        </div>

                        <jet-section-border />

                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="m-4" >
                                <div>{{loan.appliedDate}}</div>
                                <div class="text-sm text-gray-400">Applied Date</div>
                            </div>
                            <div class="m-4" v-show="loan.dueDate">
                                <div>{{loan.dueDate}}</div>
                                <div class="text-sm text-gray-400">Due Date</div>
                            </div>
                            <div class="m-4" v-show="loan.guarantorDate">
                                <div>{{loan.guarantorDate}}</div>
                                <div class="text-sm text-gray-400">Guarantor Approval</div>
                            </div>
                            <div class="m-4" v-show="loan.approvedDate">
                                <div>{{loan.approvedDate}}</div>
                                <div class="text-sm text-gray-400">Approved Date</div>
                            </div>

                            <div class="m-4" v-show="loan.closedDate">
                                <div>{{loan.closedDate}}</div>
                                <div class="text-sm text-gray-400">Closed Date</div>
                            </div>
                        </div>

                        <jet-section-border />

<!--                        Loan Schedule-->
                        <div class="mt-8 sm:col-span-2 md:col-span-3 overflow-x-scroll" v-if="loan.schedule">
                            <table class="md:w-full table-auto">
                                <thead>
                                <tr class="border-gray-400 border-b">
                                    <th class="text-center text-sm">Payments</th>
                                    <th class="text-right text-sm">Opening Balance</th>
                                    <th class="text-right text-sm">Interest</th>
                                    <th class="text-right text-sm">Monthly Payment</th>
<!--                                    <th class="text-right text-sm">Principal</th>-->
                                    <th class="text-right text-sm">Closing Balance</th>
                                    <th class="text-right text-sm">Paid</th>
                                </tr>
                                </thead>
                                <tbody class="mt-2">
                                <tr
                                    v-for="(summary,index) in loan.schedule"
                                    :key="index"
                                >
                                    <td class="text-center text-sm ">{{ summary.calculatedDueDate}}</td>
                                    <td class="text-right text-sm ">{{ summary.openingBalance.toFixed(2) }}</td>
                                    <td class="text-right text-sm">{{ summary.monthlyInterest.toFixed(2) }}</td>
                                    <td class="text-right text-sm ">{{ summary.monthlyPayment.toFixed(2) }}</td>
<!--                                    <td class="text-right text-sm">{{ summary.principal.toFixed(2) }}</td>-->
                                    <td class="text-right text-sm ">{{ Math.abs(summary.balance).toFixed(2) }}</td>
                                    <td v-if="summary.paid" class="text-right text-sm " >
                                        <div class=" flex items-center justify-end">
                                            <check :size="24" fill-color="#4ADE80"/>
                                        </div>
                                    </td>
                                    <td v-else class="text-right text-sm " >
                                        <div class=" flex items-center justify-end">
                                            <minus :size="24" fill-color="#EF4444"/>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <jet-section-border  v-if="loan.schedule" />

                        <div class="p-12 w-full flex justify-center">
                            <div>
                                <div class="text-7xl text-center font-extrabold mt-6">{{ loan.score }}%</div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="">
                            <div class="flex items-center justify-start">
                                <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">1</div>
                                <span class="text-xl ml-4">Personal Information</span>
                            </div>
                            <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                <div class="text-lg text-gray-400">Basic Information</div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="my-4 md:col-span-2 flex items-center justify-center">
                                        <img class="max-w-fit md:max-w-sm" :src="url(loan.photo)" alt="Photo">
<!--                                        <div class="text-sm text-gray-400">Photo</div>-->

                                    </div>
                                    <div class="mt-4" >
                                        <div>{{loan.firstName}}</div>
                                        <div class="text-sm text-gray-400">First Name</div>
                                    </div>
                                    <div class="mt-4 md:ml-4" >
                                        <div>{{loan.middleName}}</div>
                                        <div class="text-sm text-gray-400">Middle Name</div>
                                    </div>
                                    <div class="mt-4 ">
                                        <div>{{loan.lastName}}</div>
                                        <div class="text-sm text-gray-400">Last Name</div>
                                    </div>
                                    <div class="mt-4 md:ml-4">
                                        <div>+265 {{loan.phoneNumberMobile}}</div>
                                        <div class="text-sm text-gray-400">Phone Number Mobile</div>
                                    </div>
                                    <div class="mt-4">
                                        <div>+265 {{loan.phoneNumberWork}}</div>
                                        <div class="text-sm text-gray-400">Phone Number Work</div>
                                    </div>

                                    <div class="mt-4 md:ml-4">
                                        <div>{{loan.email}}</div>
                                        <div class="text-sm text-gray-400">Email</div>
                                    </div>
                                </div>

                                <div class="mt-6 text-lg text-gray-400">Residential Address</div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="mt-4 md:col-span-2">
                                        <div>{{computePhysicalAddress}}</div>
                                        <div class="text-sm text-gray-400">Physical Address</div>
                                    </div>
                                </div>

                                <div class="mt-6 text-lg text-gray-400">Identification</div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="mt-4 ">
                                        <div>{{loan.nationalId}}</div>
                                        <div class="mt-2 text-sm text-gray-400">National Id</div>
                                    </div>
                                    <div class="mt-4 md:ml-4">
                                        <a :href="url(loan.nationalIdFile)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>
                                        <div class="mt-2 text-sm text-gray-400">National Id File</div>
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

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="md:col-span-2">
                                        <div>{{loan.position}}</div>
                                        <div class="text-sm text-gray-400">Position</div>
                                    </div>
                                </div>

                                <div class="mt-6 text-lg text-gray-400">Work Address</div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="mt-4 md:col-span-2">
                                        <div>{{computeWorkAddress}}</div>
                                        <div class="text-sm text-gray-400">Work Address</div>
                                    </div>
                                </div>

                                <div class="mt-6 text-lg text-gray-400">Contract Details</div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="mt-4">
                                        <div>{{$page.props.contractDurationDate}}</div>
                                        <div class="text-sm text-gray-400">Contract Expiry Date</div>
                                    </div>
<!--                                    <div class="mt-4 md:ml-4">
                                        <a :href="url(loan.contract)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>
                                        <div class="mt-2 text-sm text-gray-400">Contract File</div>
                                    </div>-->
                                </div>

                                <div class="mt-6 text-lg text-gray-400">Payment Details</div>
                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="mt-4 ">
                                        <div>{{computePayDay(loan.payDay)}}</div>
                                        <div class="text-sm text-gray-400">Pay Day</div>
                                    </div>
                                    <div class="mt-4 md:ml-4">
                                        <a :href="url(loan.paySlip)" target="_blank"> <jet-button-secondary>View File</jet-button-secondary></a>
                                        <div class="mt-2 text-sm text-gray-400">Pay Slip File</div>
                                    </div>
                                    <div class="mt-4 ">
                                        <div>MK{{loan.net}}</div>
                                        <div class="text-sm text-gray-400">Net Pay</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-6">
                            <div class="flex items-center justify-start">
                                <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">3</div>
                                <span class="text-xl ml-4">Employee Loan Agreement</span>
                            </div>
                            <div class="mx-2 p-6  border-l-2 border-gray-200">
                                <div class="">
                                    <div> <jet-button-secondary class="mt-4" @click.native="termsAndConditionsDialog=true">View File</jet-button-secondary></div>
                                    <div class="mt-2 text-sm text-gray-600">Loan Agreement File</div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import JetButtonSecondary from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetDialogModal from '@/Jetstream/DialogModal'
 import JetSectionBorder from '@/Jetstream/SectionBorder'
import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'
import Check from 'vue-material-design-icons/CheckCircle.vue'
import Minus from 'vue-material-design-icons/MinusCircle.vue'

export default {
    name: "Apply",
    props:[
        'loan',
        'contractDuration',
        'contractDurationEligibility',
        'termsAndConditions',
        'employer'
    ],
    components:{
        JetButton,
        JetButtonSecondary,
        JetInput,
        JetDialogModal,
        JetSectionBorder,
        AlertCircle,
        Check,
        Minus
    },
    data() {
        return {
            termsAndConditionsCheck:false,
            termsAndConditionsDialog:false,
            errorMessage:'',
            errorSection:'',
        }
    },
    computed:{
        payment:function () {
            if(this.loan.paymentsMade === null )
                return null
            else
                return this.loan.schedule[this.loan.paymentsMade];
        },
        paymentBalance:function () {
            if(this.loan.paymentsMade === null )
                return 0

            const index = this.loan.paymentsMade-1
            if(index<0)
                return this.loan.schedule[this.loan.paymentsMade].openingBalance;
            else
                return this.loan.schedule[index].balance;
        },
        env:function () {
            return this.$page.props;
        },
        score:function () {
            return this.loan.score>0;
        },
        validation:function () {
            if(!this.score){
                this.errorMessage='You need a better score';
                this.errorSection='Score';
                return false;
            }else if(!this.contractDurationEligibility){
                this.errorMessage='Your contract duration is not valid';
                this.errorSection='Subscription';
                return false;
            }/*else if(this.consentCheck!==true){
                    this.errorMessage='Upload a signed consent form';
                    this.errorSection='Consent';
                    return false;
                }*/
            else if(!this.termsAndConditionsCheck){
                this.errorMessage='Accept the terms and conditions';
                this.errorSection='Terms and conditions';
                return false;
//                }else if(this.termsCheck!==true){
//                    this.errorMessage='Upload a signed terms and conditions document';
//                    this.errorSection='Terms and conditions';
//                    return false;
            }else
                return true
        },
        computePhysicalAddress(){
            let box=(this.loan.physicalAddress.box).length!==0?'P. O. Box '+this.loan.physicalAddress.box:'';
            return this.loan.physicalAddress.name+' '+ box + ' '+ this.loan.physicalAddress.location;
        },
        computeWorkAddress(){
            let box=(this.loan.workAddress.box).length!==0?'P. O. Box '+this.loan.workAddress.box:'';
            return /*this.workAddressName+' '+*/ box + ' '+ this.loan.workAddress.location;
        },

    },
    methods:{
        numberWithCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        apply(){
            this.$inertia.post(route('loan.apply',{'code':this.loan.code}))
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
table th, table td{
    min-width: 140px;
}

</style>
