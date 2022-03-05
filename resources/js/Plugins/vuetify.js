import Vue from 'vue'
import Vuetify, { VAutocomplete } from 'vuetify/lib'
// import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify,{
    components:{
        VAutocomplete
    }
})

const opts = {}

export default new Vuetify(opts)
