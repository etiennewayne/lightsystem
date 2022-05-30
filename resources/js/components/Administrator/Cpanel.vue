<template>
  <div>
    <section class="hero is-fullheight-with-navbar">
            <!-- Hero head: will stick at the top -->
            <div class="hero-head">
                
            </div>

            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="columns is-centered">
						<div class="column is-4">
							<div class="box">
								<div class="box-head">
									CONTROLS
								</div>
								<b-field v-for="(item, index) in devices" :key="index" :label="item.device_name">
									<b-switch :value="false" @input="invokeSwitch(item, index)" v-model="esp[index]" type="is-success">
										{{ item.device_ip }}
									</b-switch>
								</b-field> 
							</div>
						</div>
					</div>
                </div>
            </div>
    </section>
      
  </div>
</template>

<script>
import axios from 'axios'

export default {
	
	data(){
		return{

			fields: {},
			error: {},

			devices: [],

			esp: [],
		}
		
	},
	methods: {
		switch1: function(){
			axios.post('/').then(res=>{

			})
		},

		invokeSwitch(data, index){
			let token = '';
			if(this.esp[index] === true){
				token = data.device_token_on;
			}else{
				token = data.device_token_off;
			}

			fetch(`http://${data.device_ip}/${token}`);
		},

		initData(){
			axios.get('/load-devices').then(res=>{
				this.devices = res.data;
			})
		}
	},

	mounted(){
		this.initData();
	}
}
</script>

<style scoped>
	.box-head{
		font-size: 1.5em;
		font-weight: bold;
		margin: 0 0 25px 0;
	}

	.box{
		padding-bottom: 25px;
	}
</style>
