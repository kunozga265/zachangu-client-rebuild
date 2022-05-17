<template>
    <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               Edit Employee
            </h2>
        </template>

        <form @submit.prevent="submit">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">

                            <jet-validation-errors class="mb-4" />

                            <!-- Personal Information -->
                            <div>
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">1</div>
                                    <span class="text-xl ml-4">Personal Information</span>
                                </div>
                                <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                    <div class="text-lg text-gray-400">Basic Information</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-4 md:col-span-2">
                                            <jet-label for="photo" value="Upload photo" />
<!--                                            <jet-input id="photo" type="file" class="mt-1 block w-full" v-model="form.photo" required />-->
                                            <input type="file" @input="photoUpload($event.target.files[0])" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                                        </div>
                                        <div class="mt-4" >
                                            <jet-label for="firstName" value="First Name" />
                                            <jet-input id="firstName" type="text" class="mt-1 block w-full" v-model="form.firstName" required  autocomplete="First Name" />
                                        </div>
                                        <div class="mt-4 md:ml-4" >
                                            <jet-label for="middleName" value="Middle Name" />
                                            <jet-input id="middleName" type="text" class="mt-1 block w-full" v-model="form.middleName" autocomplete="Middle Name" />
                                        </div>
                                        <div class="mt-4 ">
                                            <jet-label for="lastName" value="Last Name" />
                                            <jet-input id="lastName" type="text" class="mt-1 block w-full" v-model="form.lastName" required autocomplete="Last Name" />
                                        </div>
                                        <div class="mt-4 md:ml-4">
                                            <jet-label for="phoneNumberMobile" value="Phone Number Mobile" />
                                            <div class="relative">
                                                <span style="padding: 9px" class="absolute">+265</span>
                                                <jet-input id="phoneNumberMobile" type="text" class="mt-1 block w-full pl-14" v-model="form.phoneNumberMobile" required autocomplete="Phone Number Mobile" />
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <jet-label for="phoneNumberWork" value="Phone Number Work" />
                                            <div class="relative">
                                                <span style="padding: 9px" class="absolute">+265</span>
                                                <jet-input id="phoneNumberWork" type="text" class="mt-1 block w-full pl-14" v-model="form.phoneNumberWork" required autocomplete="Phone Number Work" />
                                            </div>
                                        </div>

                                        <div class="mt-4 md:ml-4">
                                            <jet-label for="email" value="Email" />
                                            <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                                        </div>
                                    </div>

                                    <div class="mt-6 text-lg text-gray-400">Residential Address</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-4 md:col-span-2">
                                            <jet-label for="physicalAddressName" value="Physical Address Name" />
                                            <jet-input id="physicalAddressName" type="text" class="mt-1 block w-full" v-model="physicalAddressName" required />
                                        </div>
                                        <div class="mt-4">
                                            <jet-label for="physicalAddressBox" value="Postal Address" />
                                            <div class="relative">
                                                <span style="padding: 9px" class="absolute">P. O. Box</span>
                                                <jet-input id="physicalAddressBox" type="text" class="mt-1 block w-full pl-20" v-model="physicalAddressBox" required autocomplete="Phone Number Work" />
                                            </div>
                                        </div>

                                        <div class="mt-4 md:ml-4">
                                            <jet-label for="physicalAddressLocation" value="District" />
                                            <jet-input id="physicalAddressLocation" type="text" class="mt-1 block w-full" v-model="physicalAddressLocation" required />
                                        </div>
                                    </div>

                                    <div class="mt-6 text-lg text-gray-400">Identification</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-4 ">
                                            <jet-label for="nationalId" value="National Id" />
                                            <jet-input id="nationalId" type="text" class="mt-1 block w-full" v-model="form.nationalId" required autocomplete="National Id" />
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
                                            <jet-label for="position" value="Position" />
                                            <jet-input id="position" type="text" class="mt-1 block w-full" v-model="form.position" required />
                                        </div>
                                    </div>

                                    <div class="mt-6 text-lg text-gray-400">Work Address</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-4">
                                            <jet-label for="workAddressBox" value="Postal Address" />
                                            <div class="relative">
                                                <span style="padding: 9px" class="absolute">P. O. Box</span>
                                                <jet-input id="workAddressBox" type="text" class="mt-1 block w-full pl-20" v-model="workAddressBox" required autocomplete="Work Address Box" />
                                            </div>
                                        </div>

                                        <div class="mt-4 md:ml-4">
                                            <jet-label for="workAddressLocation" value="District" />
                                            <jet-input id="workAddressLocation" type="text" class="mt-1 block w-full" v-model="workAddressLocation" required />
                                        </div>
                                    </div>

                                    <div class="mt-6 text-lg text-gray-400">Contract Details</div>

                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="mt-4 ">
                                            <jet-label for="contractDuration" value="Contract Expiry Date" />
                                            <vue-date-time-picker
                                                v-model="form.contractDuration"
                                                onlyDate
                                                minDate="today"
                                                format="YYYY-MM-DD"
                                            />
<!--                                            <jet-input id="nationalId" type="text" class="mt-1 block w-full" v-model="form.nationalId" required autocomplete="National Id" />-->
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <!-- Bank Information -->
                            <div class="mt-6">
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">3</div>
                                    <span class="text-xl ml-4">Bank Information</span>
                                </div>
                                <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="" >
                                            <jet-label for="bankName" value="Bank Name" />
                                            <jet-input id="bankName" type="text" class="mt-1 block w-full" v-model="form.bankName" required  autocomplete="Name" />
                                        </div>
                                        <div class="mt-4 md:ml-4 md:mt-0">
                                            <jet-label for="bankAccountName" value="Bank Account Name" />
                                            <jet-input id="bankAccountName" type="text" class="mt-1 block w-full" v-model="form.bankAccountName" required  autocomplete="Name" />
                                        </div>
                                        <div class="mt-4">
                                            <jet-label for="bankAccountNumber" value="Bank Account Number" />
                                            <jet-input id="bankAccountNumber" type="text" class="mt-1 block w-full" v-model="form.bankAccountNumber" required  autocomplete="Name" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full h-20"></div>
            <div class="fixed bottom-0 h-20 p-6 bg-white shadow w-full flex items-center">
                <div v-if="validation" class="w-full flex items-center justify-center">
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                    >
                        {{form.progress.percentage}}
                    </progress>
                    <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save Employee
                    </jet-button>
                </div>
                <div v-else>
                    <div class="text-red-500">{{errorMessage}}</div>
                    <div class="text-sm text-gray-400">{{errorSection}}</div>
                </div>

            </div>
        </form>
    </app-layout-admin>
</template>

<script>
    import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin";
    import JetLabel from '@/Jetstream/Label.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default {
        props:[
          'employee',
            'physicalAddress',
            'workAddress',
            'contractDurationISO',
        ],

        components: {
            AppLayoutAdmin,
            JetLabel,
            JetButton,
            JetInput,
            JetSectionBorder,
            JetValidationErrors
        },

        data() {
            return {
                form: this.$inertia.form({
                    //Personal Information
                    photo:this.employee.photo,
                    firstName: this.employee.firstName,
                    middleName:this.employee.middleName,
                    lastName: this.employee.lastName,
                    phoneNumberMobile: this.employee.phoneNumberMobile,
                    phoneNumberWork: this.employee.phoneNumberWork,
                    email: this.employee.email,
                    physicalAddress:{},

                    nationalId: this.employee.nationalId,
                    employer_id: this.employee.employer_id,

                    //Workplace Details
                    workAddress:{},
                    position:this.employee.position,
                    bankName:this.employee.bankName,
                    bankAccountName:this.employee.bankAccountName,
                    bankAccountNumber:this.employee.bankAccountNumber,
                    contractDuration:(this.contractDurationISO).substr(0,10),
                }),
                physicalAddressName:this.physicalAddress.name,
                physicalAddressBox:this.physicalAddress.box,
                physicalAddressLocation:this.physicalAddress.location,
                workAddressName:this.workAddress.name,
                workAddressBox:this.workAddress.box,
                workAddressLocation:this.workAddress.location,
                today:new Date().toISOString().substr(0,10),

                errorMessage:'',
                errorSection:'',
            }
        },
        created(){

        },
        computed:{
            // user(){
            //     $page.props.user
            // },
            validation:function () {
                const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

                //personal information
                if (this.form.photo==='' || this.form.photo===null){
                    this.errorSection='Personal Information';
                    this.errorMessage='Upload a photo';
                    return false;
                }
                else if ((this.form.firstName).length===0 || (this.form.lastName).length===0 || (this.form.email).length===0 ){
                    this.errorSection='Personal Information';
                    this.errorMessage='Enter all the details';
                    return false;
                }
                else if ( (this.form.phoneNumberMobile).length<9 || (this.form.phoneNumberWork).length<9 ){
                    this.errorSection='Personal Information';
                    this.errorMessage='Enter a valid phone number';
                    return false;
                }
                else if ( !pattern.test(this.form.email)){
                    this.errorSection='Personal Information';
                    this.errorMessage='Enter a valid e-mail';
                    return false;
                }
                 else if ( (this.physicalAddressName).length===0 || (this.physicalAddressBox).length===0 || (this.physicalAddressLocation).length===0 || (this.computePhysicalAddress).length===2){
                    this.errorSection='Personal Information';
                    this.errorMessage='Enter a valid physical address';
                    return false;
                }
                else if(this.form.nationalId==="" || (this.form.nationalId).length<8){
                    this.errorSection='Personal Information';
                    this.errorMessage='Enter a valid national Id';
                    return false;
                }

                //workplace details
                else if((this.form.position).length===0){
                    this.errorSection='Workplace Information';
                    this.errorMessage='Enter a position';
                    return false;
                }
                 else if ((this.workAddressBox).length===0 || (this.workAddressLocation).length===0 || (this.computeWorkAddress).length===2 ){
                     this.errorSection='Workplace Information';
                     this.errorMessage='Enter a valid work address';
                     return false;
                 }
                else if(this.duration===this.todayTimestamp){
                    this.errorSection='Workplace Information';
                    this.errorMessage='Set a contract expiry date';
                    return false;
                }
                else if ((this.form.bankName).length===0 || (this.form.bankAccountName).length===0 || (this.form.bankAccountNumber).length===0 ){
                    this.errorSection='Bank Information';
                    this.errorMessage='Enter all bank details';
                    return false;
                }
                else
                    return true
            },
            computePhysicalAddress(){
                let box=(this.physicalAddressBox).length!==0?'P. O. Box '+this.physicalAddressBox:'';
                return this.physicalAddressName+' '+ box + ' '+ this.physicalAddressLocation;
            },
            computeWorkAddress(){
                let box=(this.workAddressBox).length!==0?'P. O. Box '+this.workAddressBox:'';
                return /*this.workAddressName+' '+*/ box + ' '+ this.workAddressLocation;
            },
            duration:function () {
                return (new Date(this.form.contractDuration).getTime())/1000
            },
            todayTimestamp:function () {
                return (new Date(this.today).getTime())/1000
            },
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        physicalAddress:{
                            name:this.physicalAddressName,
                            box:this.physicalAddressBox,
                            location:this.physicalAddressLocation,
                        },
                        workAddress:{
                            name:this.workAddressName,
                            box:this.workAddressBox,
                            location:this.workAddressLocation,
                        },
                        contractDuration: this.duration,
                    }))
                    .post(this.route('employee.admin.update',{id:this.employee.id}))
            },
            photoUpload(file){
                const reader=new FileReader();
                if (file){
                    reader.readAsDataURL(file);
                    reader.onload=(e)=>{
                        axios.post(this.$page.props.publicPath+"api/admin/file-upload",{
                            type:"photo",
                            file:e.target.result
                        }).then(res=>{
                            this.form.photo= res.data.new_file
                        }).catch(function(res){
                           // this.form.errors.push(res.data.message)
                        })
                    };
                }
            },
        }


    }
</script>
