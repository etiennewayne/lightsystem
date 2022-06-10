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
								<b-switch :value="false" @input="invokeSwitch($event, i)" type="is-success">
									{{ i.room }}
								</b-switch>
							</b-field>
							<div>{{ mark }}</div>
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

		invokeSwitch(evt, data){

			 let token = '';
			if(evt){
				token = data.device_token_on;
				axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=ON')
				this.mark = 'ON';
			}else{
				token = data.device_token_off;
				axios.get('/switch-log?url=' + data.device_ip + '&token=' + token + '&status=OFF')
				this.mark = 'OFF';
			}

			fetch(`http://${data.device_ip}/${token}`);
			
		},

		initData(){
			axios.get('/load-switch-buildings').then(res=>{
				this.buildings = res.data;
				
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
