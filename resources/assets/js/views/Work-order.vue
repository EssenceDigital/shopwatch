<template v-if="id">
	<layout>
		<div slot="title">Work Order</div>

		<div slot="tools">
			<v-tooltip top>
		    <v-btn flat color="green" left slot="activator" @click="addJobDialog = true">
		      <v-icon left>add_circle_outline</v-icon> Job
		    </v-btn>				
	      <span>Add job</span>
	    </v-tooltip>			
		</div>

		<div slot="content">

			<v-layout row wrap>
					<job-ticket v-for="job in workOrder.jobs" :job="job" :key="job.id"></job-ticket>
			</v-layout>

			<!-- Add job dialog -->
			<v-dialog v-model="addJobDialog" persistent max-width="500px">
        <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="addJobDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
          <v-card-text>
          	<job-form action="createJob" :work-order="workOrder.id" @saved="addJobDialog = false"></job-form>
          </v-card-text>
        </v-card>
      </v-dialog>	

		</div><!--/ Content slot -->

	</layout>
</template>

<script>
	import Layout from './_Layout';
	import JobForm from './../components/forms/Job-form';
	import JobTicket from './../components/tickets/Job-ticket';

	export default{
		props: ['id'],

		data () {
			return {
				addJobDialog: false
			}
		},

		computed: {
			workOrder (){
				return this.$store.getters.selectedWorkOrder;
			}
		},

		watch: {
			id (newId){
				// If ID prop does not match the selected work order then update selected work order
				// ID prop is final source of truth
				if(this.workOrder.id != newId) {
					this.$store.dispatch('getWorkOrder', newId)
				}				
			}
		},

		components: {
			'layout': Layout,
			'job-form': JobForm,
			'job-ticket': JobTicket
		},

		created (){
			// If ID prop does not match the selected work order then update selected work order
			// ID prop is final source of truth
			if(! this.workOrder.id || this.workOrder.id != this.id) {
				this.$store.dispatch('getWorkOrder', this.id)
			}
		}	
	}
</script>