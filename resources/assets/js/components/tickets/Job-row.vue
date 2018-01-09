<template v-if="job">
	<v-layout row>
		<v-flex xs8>
			<p class="pa-2 mb-0">
				<strong>{{ job.title }}</strong>
			</p>
			<p class="pt-0 pl-3">
				{{ job.description }}
			</p>
		</v-flex>
		<v-flex xs1 class="text-xs-right">
			<p class="pa-2 mb-0">
				{{ job.hours }}
			</p>
		</v-flex>
		<v-flex xs1 class="text-xs-right">
			<p class="pa-2 mb-0">
				{{ job.shop_rate | money }}
			</p>			
		</v-flex>
		<v-flex xs2 class="text-xs-right">
			<p class="pa-2 mb-0">
				{{ job.job_labour_total | money }}
			</p>			
		</v-flex>		
	</v-layout>
</template>

<script>
	import JobForm from './../forms/Job-form';
	import PartsForm from './../forms/Job-parts-form';

	export default{
		props: {
			job: {
				required: true
			},
			invoiceState: {
				default: false
			}
		},

		data (){
			return {
				editJobDialog: false,
				addPartsDialog: false,
				markingComplete: false
			}
		},

		components: {
			'job-form': JobForm,
			'parts-form': PartsForm,
		},

		methods: {
			markComplete (is_complete){
				// Toggle loader
				this.markingComplete = true;
				// Dispatch action
				this.$store.dispatch('markJobComplete', {
					id: this.job.id,
					is_complete: is_complete
				})
					.then(() => {
						// Toggle loader
						this.markingComplete = false;
					})
					.catch((error) => {
						// Toggle loader
						this.markingComplete = false;	
						// Handle errors					
		  			if(error.response){
		    			// Form validation errors
		    			if(error.response.data){
								// Check for error response from Laravel controller
								if(error.response.data.result == 'error'){
									this.$router.app.$emit('snackbar', {
										text: error.response.data.message
									});
								}
							}
						}
					});
			}
		}
	}
</script>