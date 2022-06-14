<template>
	<div>

		<div class="section">

			<div class="building-container">
				<div class="building" v-for="(item, index) in buildings" :key="index">
					<div class="box" v-if="item.devices.length > 0">
						<div class="box-head">
							{{ item.building_name }}
						</div>
						
						<div v-for="(i, ix) in item.devices" :key="ix">
							<hr>
							<div>{{ i.floor_name }}</div>
							<b-field :label="i.device_name">
								<b-switch :value="false" @input="invokeSwitch($event, i, index, ix)" :id="i.room_id" v-model="i.s" type="is-success">
									{{ i.room }}
								</b-switch>
							</b-field>
							<div v-if="i.s">ON</div>
							<div v-else>OFF</div>
						</div>
					</div>
				</div>

			</div><!--building-container-->
		</div>

	</div>
</template>

<script>
import axios from 'axios'




export default {
	
	data(){
		return{
			mark: 'OFF',
			buildings: [],

			
		}
		
	},
	methods: {
		switch1: function(){
			axios.post('/').then(res=>{
			})
		},
	
		invokeSwitch(evt, data, index, ix){

			let token = '';

			// console.log(data.room_id);
		
			// var swQ = document.querySelector("switch").querySelectorAll('checkbox');

			// var sw = document.getElementById(data.room_id);

			// console.log(sw);
			// console.log(swQ);
			
			if(evt){
				token = data.device_token_on;
				//axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=ON')
			}else{
				token = data.device_token_off;
				//axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=OFF')
			}

			//fetch(`http://${data.device_ip}/${token}`);
			axios.get('/test-switch')
			
		},

		initData(){
			axios.get('/load-switch-buildings').then(res=>{
				this.buildings = res.data;
			})
		},

		getNotifications(){
			//dere ang codes
			console.log('fire every 30sec');
		},

		test(){
			let checkboxes = document.querySelectorAll('switch');
			console.log(checkboxes);
		}
	},

	mounted(){
		this.initData();

		window.setInterval(() => {
			this.getNotifications()
		}, 30000);

		this.test();
		
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
		margin: 15px;
	}

	.building-container{
		display: flex;
		justify-content: center;
	}

	.building{
		width: 300px;
	}

	@media only screen and (max-width: 600px) {
		.building-container{
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
	}
</style>
