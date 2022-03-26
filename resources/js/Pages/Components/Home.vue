<template>
    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 py-12 sm:px-20 bg-white border-b border-gray-200">

                        <div>
                            <inertia-link class="pl-0 p-2"  :href="route('loan.new')">
                                <jet-button :disabled="loan!=null">
                                    Apply for loan
                                </jet-button>
                            </inertia-link>

                            <inertia-link class="p-2" :href="route('guarantor')">
                                <jet-secondary-button>
                                    Guarantee Loan
                                </jet-secondary-button>
                            </inertia-link>

                            <jet-section-border />
                        </div>

                        <div v-if="loan">
                            <div class="mb-4 text-3xl text-gray-800 font-bold">
                                Current Loans
                            </div>
                            <inertia-link
                                :href="route('loan.show',{'code':loan.code})"
                            >
                                <div class="cursor-pointer m-2 p-6 rounded bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition">
                                    <div class="text-4xl text-gray-100 font-bold ">MK{{ loan.amount }}</div>
                                    <div class=" flex justify-start">
                                        <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                        <span class="ml-2 text-gray-400">{{getStatus(loan.progress)}}</span>
                                    </div>
                                </div>
                            </inertia-link>
                            <jet-section-border />
                        </div>

<!--                            <inertia-link-->
<!--                                v-else-->
<!--                                :href="route('loan.new')"-->
<!--                            >-->
<!--                                <div class="cursor-pointer p-6 rounded bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition">-->
<!--                                    <div class="text-4xl text-gray-100 font-bold ">No Active Loan</div>-->
<!--                                    <div class=" flex justify-start">-->
<!--                                        <span class="ml-2 text-gray-400">Click to apply</span>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                            </inertia-link>-->




                        <div class="mt-4 text-3xl text-gray-800 font-bold">
                            Get quick loans with us
                        </div>

                        <div class="mt-2 text-gray-600">
                            You can borrow MK{{ contents.amountLimit.lower }} to MK{{contents.amountLimit.upper}} and pay back an interest of {{((contents.interest)*100).toFixed(1)}}%. Zachangu will charge {{Math.round((contents.fee)*100)}}% of the loan as processing fee.
                        </div>


                        <div class="mt-6 text-gray-600">
                            For Example
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="mt-2 md:col-span-2">
                                <!--                <jet-label for="amount" value="Enter Amount" />-->
                                <jet-input id="amount" type="text" class="mt-1 block w-full" v-model="amount" placeholder="Enter Amount" />
                                <span class="text-xs text-gray-400">Enter amount between MK{{contents.amountLimit.lower}} and MK{{contents.amountLimit.upper}}</span>
                            </div>
                            <div class="mt-2 md:ml-2">
                                <select class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="selectedMonth">
                                    <option
                                        v-for="index in monthsRange"
                                        :key="index"
                                    >{{ index }}</option>
                                </select>
                                <span class="text-xs text-gray-400">Select Number of months to repay loan</span>
                            </div>
                        </div>



                        <div class="mt-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                                <div class="mt-4">
                                    <div>MK{{validation?processingFee:'0'}}</div>
                                    <div class="text-sm text-gray-600">Processing Fee</div>
                                </div>
                                <div class="mt-4">
                                    <div>MK{{validation?amountReceived:'0'}}</div>
                                    <div class="text-sm text-gray-600">Amount Received</div>
                                </div>
                            </div>
                            <jet-section-border />

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                                <div class="mt-4">
                                    <div>MK{{validation?monthlyPayment.toFixed(2):'0'}}</div>
                                    <div class="text-sm text-gray-600">Monthly Payment</div>
                                </div>
                                <div class="mt-4">
                                    <div>MK{{validation?interest:'0'}}</div>
                                    <div class="text-sm text-gray-600">Total interest charged</div>
                                </div>
                                <div class="mt-4">
                                    <div>MK{{validation?amountToPay:'0'}}</div>
                                    <div class="text-sm text-gray-600">Amount to pay back</div>
                                </div>
                                <div class="mt-8 sm:col-span-2 md:col-span-3" v-if="validation">
                                    <table class="w-full table-fixed">
                                        <thead>
                                            <tr class="border-gray-400 border-b">
                                                <th class="text-center text-tiny sm:text-xs md:text-sm lg:text-base">Payments</th>
                                                <th class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">Opening Balance</th>
<!--                                                <th class="text-right text-xs sm:text-sm md:text-base invisible md:visible">Monthly Payment</th>-->
                                                <th class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">Principal</th>
                                                <th class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">Interest</th>
                                                <th class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">Closing Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="mt-2">
                                            <tr
                                                v-for="(summary,index) in loanSummary"
                                                :key="index"
                                            >
                                                <td class="text-center text-tiny sm:text-xs md:text-sm lg:text-base">{{ computeDay(summary.month) }}</td>
                                                <td class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">{{ summary.openingBalance }}</td>
<!--                                                <td class="text-right text-xs sm:text-sm md:text-base invisible md:visible">{{ summary.monthlyPayment }}</td>-->
                                                <td class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">{{ summary.principal }}</td>
                                                <td class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">{{ summary.interest }}</td>
                                                <td class="text-right text-tiny sm:text-xs md:text-sm lg:text-base">{{ summary.closingBalance }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

<!--        <div class="w-full h-20"></div>-->
<!--        <div v-show="loan==null" class=" p-6 bg-gray-800 w-full flex items-center justify-center">
            <inertia-link :href="route('loan.new')">
                <jet-button class="">
                    Apply for loan
                </jet-button>
            </inertia-link>
        </div>-->

    </div>
</template>

<script>

    import JetLabel from '@/Jetstream/Label.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetInput from '@/Jetstream/Input'
    import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        props:['loan'],
        components: {
            JetLabel,
            JetButton,
            JetSecondaryButton,
            JetValidationErrors,
            JetInput,
            JetSectionBorder,
            AlertCircle
        },

        data() {
            return {
                amount:'',
                contents:{
                    amountLimit:{
                        lower:this.$page.props.contents.lowerLimit,
                        upper:this.$page.props.contents.upperLimit,
                    },
                    fee:this.$page.props.contents.bankCharge,
                    interest:this.$page.props.contents.interest,
                },
                monthsRange:6,
                selectedMonth:1
            }
        },
        computed:{
            validation:function () {
                return (parseInt(this.amount)>=parseInt(this.contents.amountLimit.lower) && parseInt(this.amount)<=parseInt(this.contents.amountLimit.upper));
            },
            interest:function () {
                return (parseFloat(this.amountToPay)-this.amount).toFixed(2);
            },
            amountReceived:function () {
                return Math.round(this.amount - this.processingFee);
            },
            amountToPay:function () {
                /*
                Deprecated
                return Math.round(this.amount *(this.contents.interest + 1));
                */

                return (this.monthlyPayment * parseInt(this.selectedMonth)).toFixed(2);
            },
            processingFee:function () {
                return Math.round(this.amount * this.contents.fee);
            },
            monthlyPayment:function(){
                let num=(this.contents.interest * Math.pow((1 + this.contents.interest),parseInt(this.selectedMonth)))
                let den=(Math.pow((1 + this.contents.interest),parseInt(this.selectedMonth)) - 1)
                return this.amount * num/den
            },
            loanSummary:function(){
                let summary=[]
                let openingBalance=0.0
                let interest=0
                let balance=this.amount

                for(let iteration=1;iteration<=parseInt(this.selectedMonth); iteration++){
                    openingBalance=balance
                    interest=balance*this.contents.interest
                    balance=(balance*(1+this.contents.interest))-this.monthlyPayment

                    summary.push({
                        month:iteration,
                        openingBalance:parseFloat(openingBalance).toFixed(2),
                        monthlyPayment:(this.monthlyPayment).toFixed(2),
                        principal:(this.monthlyPayment-interest).toFixed(2),
                        interest:(interest).toFixed(2),
                        closingBalance:Math.abs((balance).toFixed(2))
                    })
                }

                return summary
            }

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
            computeDay(payDay){
                let day=''
                switch(payDay){
                    case 1:
                        day='st';
                        break;
                    case 2:
                        day= 'nd';
                        break;
                    case 3:
                        day='rd';
                        break;
                    default:
                        day='th';
                        break;
                }
                return payDay + day;
            },

        }
    }
</script>
