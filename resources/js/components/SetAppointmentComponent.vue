<template>
    <div>
        <section class="section">
            <div class="columns">
                <div class="column is-6 is-offset-3">
                    <div class="time-container">
                        <form @submit.prevent="submitAppointment">
                            <div class="p-2">
                                <h1 class="title is-4 mb-4">SET AN APPOINTMENT NOW</h1>
                                <b-field label="SELECT DATE" grouped  expanded class="is-centered" label-position="on-border">
                                    <b-datepicker rounded expanded
                                          v-model="appointment.appointment_date"
                                          placeholder="Type or select a date..."
                                          icon="calendar-today"
                                          :locale="locale"
                                          editable>
                                    </b-datepicker>
                                </b-field>

                                <b-field label="SELECT TIME (FROM - TO)" grouped  expanded class="is-centered" label-position="on-border">
                                    <b-timepicker
                                        rounded
                                        placeholder="From..."
                                        icon="clock"
                                        v-model="appointment.appointment_time_from"
                                        :enable-seconds="false"
                                        editable
                                        :locale="locale">
                                    </b-timepicker>

                                    <b-timepicker
                                        rounded
                                        placeholder="To..."
                                        icon="clock"
                                        v-model="appointment.appointment_time_to"
                                        :enable-seconds="false"
                                        editable
                                        :locale="locale">
                                    </b-timepicker>
                                </b-field>

                                <b-field label="TRAINING CENTER" expanded label-position="on-border"
                                         :type="errors.training_center ? 'is-danger' : ''"
                                         :message="errors.training_center ? errors.training_center[0] : ''">
                                    <b-select v-model="appointment.training_center" expanded rounded>
                                        <option v-for="(item, index) in trainingCenters" :key="index" :value="item.training_center_id">{{ item.training_center }}</option>
                                    </b-select>
                                </b-field>

                                <b-field label="Remarks" expanded label-position="on-border">
                                    <b-input type="textarea" v-model="appointment.remarks" placeholder="Remarks"></b-input>
                                </b-field>



                                <b-notification v-if="this.errors.not_allowed"
                                    type="is-danger is-light"
                                    aria-close-label="Close notification"
                                    role="alert">
                                    {{ this.errors.not_allowed[0] }}
                                </b-notification>

                                <b-notification v-if="this.errors.limit"
                                                type="is-danger is-light"
                                                aria-close-label="Close notification"
                                                role="alert">
                                    {{ this.errors.limit[0] }}
                                </b-notification>

                                <div class="buttons is-right">
                                    <button class="button is-primary is-rounded">SET APPOINTMENT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {

            locale: undefined, // Browser locale

            errors: {},
            fields: {},

            appointment: {
                appointment_date: null,
                nAppointmentDate: '',
                appointment_type: '',
            },

            trainingCenters: [],
        }
    },
    methods: {

        clearFields(){
            this.appointment = {
                appointment_date: null,
                nAppointmentDate: '',
                appointment_type: '',
            };
            this.errors = {};
        },

        submitAppointment(){

            this.appointment.app_date = new Date(this.appointment.appointment_date).toLocaleDateString();
            //this.appointment.app_time = new Date(this.appointment.appointment_date).toLocaleTimeString();
            this.appointment.app_time_from = new Date(this.appointment.appointment_time_from).toLocaleTimeString();
            this.appointment.app_time_to = new Date(this.appointment.appointment_time_to).toLocaleTimeString();

            axios.post('/set-appointment', this.appointment).then(res=>{

                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'APPOINTMENT SAVED!',
                        message: 'Appointment saved.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.clearFields();
                            window.location = '/my-appointment'
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
                if(err.response.status === 401){
                    this.isModalActive = true;
                }
            });
        },

        loadTrainingCenter(){
            axios.get('/get-open-training-centers').then(res=>{
                this.trainingCenters = res.data;
            })
        },

    },
    mounted() {
        this.loadTrainingCenter();
    }
}
</script>
<style scoped>

    .time-container{
        padding: 15px;
        background: white;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

</style>
