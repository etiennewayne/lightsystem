<template>
  <div>
    <section class="hero is-fullheight-with-navbar">
            <!-- Hero head: will stick at the top -->
            <div class="hero-head">
                
            </div>

            <div class="hero-body">
                <div class="container has-text-centered">
                    
					<div class="columns is-centered" v-for="(item, index) in buildings" :key="index">
						<div class="column is-6">
							<div class="box">

								<div class="box-head">
									{{ item.building_name }}
								</div>
								
								<b-field v-for="(i, ix) in item.devices" :key="ix" :label="i.device_name">
									<b-switch :value="false" @input="invokeSwitch(i, ix)" v-model="esp[ix]" type="is-success">
										{{ i.device_ip }}
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

			buildings: [],



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
			axios.get('/load-switch-buildings').then(res=>{
				this.buildings = res.data;
				console.log(this.buildings);
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
