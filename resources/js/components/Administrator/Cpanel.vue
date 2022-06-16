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
								<b-switch :value="false" @input="invokeSwitch($event, i, index, ix)" :id="`switch[${index}][${i.room_id}]`" v-model="checkBoxes[i.device_id]" type="is-success">
									{{ i.room }}
								</b-switch>
							</b-field>
							<div v-if="bulbStatus">ONLINE</div>
							<div v-else>OFFLINE</div>
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

			checkBoxes: [],

			bulbStatus: false,
			
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
				axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=ON')
			}else{
				token = data.device_token_off;
				axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=OFF')
			}

			fetch(`http://${data.device_ip}/${token}`);
			//axios.get('/test-switch')
			
		},

		initData(){
			axios.get('/load-switch-buildings').then(res=>{
				this.buildings = res.data;
			})
		},

		getNotifications(){
			//dere ang codes
			this.buildings.forEach(d => {
				//foreach devices
				d.devices.forEach(el =>{
					//console.log(el);
					//let checkboxes = document.querySelector('input[type=checkbox]');
					//console.log(checkboxes);
					//console.log(this.checkBoxes[el.device_id]);
					//console.log(el.device_ip);
					axios.get(`http://${el.device_ip}`).then(res=>{
						console.log(res.data);
						if(res.data === 'ON'){
							this.bulbStatus = true;
							//this.checkBoxes[el.device_id] = true;
							console.log('TURNING ON');
						}else{
							this.checkBoxes[el.device_id] = false;
							//this.bulbStatus = true;
							console.log('TURNING OFF');

						}
					})
				});
			});
			
		},

		test(){
			let checkboxes = document.getElementById('switch[0][4]');
			//console.log(checkboxes.querySelector('input[type=checkbox]'));
			//console.log(checkboxes.value);
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
