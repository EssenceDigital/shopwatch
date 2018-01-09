<template v-if="id">
	<layout v-if="workOrder">
		<div slot="title">Work Order</div>

		<div v-if="!workOrder.is_invoiced" slot="tools">
			<v-tooltip top>
		    <v-btn color="primary" left slot="activator" @click="woOptionsDialog = true">
		      <v-icon left>info</v-icon> Options
		    </v-btn>				
	      <span>Options</span>
	    </v-tooltip>			
			<v-tooltip top>
		    <v-btn color="primary" left slot="activator" @click="addJobDialog = true">
		      <v-icon left>add_circle_outline</v-icon> Job
		    </v-btn>				
	      <span>Add job</span>
	    </v-tooltip>	
			<v-tooltip top>
		    <v-btn color="teal" dark left slot="activator" @click="confirmInvoiceDialog = true">
		      <v-icon left>credit_card</v-icon> Invoice
		    </v-btn>				
	      <span>Create Invoice</span>
	    </v-tooltip>		
		</div>
		<div v-else slot="tools">
			<v-alert outline color="success" icon="check_circle" class="mt-3" :value="true">
	      This work order has been invoiced.
	    </v-alert>				
		</div>

		<div slot="content">
			<v-layout row wrap>
				<job-ticket v-for="job in workOrder.jobs" :job="job" :key="job.id" class="mt-4"></job-ticket>
			</v-layout>

			<v-layout row class="mt-3">
				<v-spacer></v-spacer>
				<v-flex xs3>
					<v-card class="text-xs-right pt-3 pb-3 pr-3">
						<span class="subheading">
							<strong>Total:</strong> {{ estGrandTotal | money }}
						</span>
					</v-card>
				</v-flex>
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

			<!-- WO options (edit) dialog -->
			<v-dialog v-model="woOptionsDialog" persistent max-width="500px">
        <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="woOptionsDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
          <v-card-text>
          	<wo-form :wo="workOrder" :edit-state="true" @saved="woOptionsDialog = false"></wo-form>
          </v-card-text>
        </v-card>
      </v-dialog>	      

	    <!-- Create invoice dialog -->
	    <v-dialog v-model="confirmInvoiceDialog" persistent max-width="300px">
	      <v-card>
	        <v-system-bar window class="teal">
	          <v-spacer></v-spacer>
	          <v-tooltip top>
	            <v-btn icon class="mr-0" slot="activator" @click="confirmInvoiceDialog = false">
	              <v-icon class="white--text mr-0">close</v-icon>
	            </v-btn>                      
	            <span>Close dialog</span>
	          </v-tooltip>            
	        </v-system-bar>
	        <v-card-text>
	          Create invoice?
	        </v-card-text>
	        <v-card-actions>
	        	<v-spacer></v-spacer>
						<v-btn color="red darken-1" flat @click.native="confirmInvoiceDialog = false">Cancel</v-btn>
	          <v-btn color="green darken-1" flat :loading="invoiceCreating" @click.native="createInvoice">Yes</v-btn>    
	          <v-spacer></v-spacer>    	
	        </v-card-actions>
	      </v-card>
	    </v-dialog>

		</div><!--/ Content slot -->

	</layout>
</template>

<script>
	import Layout from './_Layout';
	import WoForm from './../components/forms/Work-order-form';
	import JobForm from './../components/forms/Job-form';
	import JobTicket from './../components/tickets/Job-ticket';

	export default{
		props: ['id'],

		data () {
			return {
				addJobDialog: false,
				removeWoDialog: false,
				woOptionsDialog: false,
				confirmInvoiceDialog: false,
				invoiceCreating: false
			}
		},

		computed: {
			workOrder (){
				return this.$store.getters.selectedWorkOrder;
			},

			estGrandTotal (){
				if(this.workOrder.jobs){
					let total = 0;

					this.workOrder.jobs.forEach((job) => {
						total += parseFloat(job.job_grand_total);
					});

					return total;
				}
			}
		},

		watch: {
			id (newId){
				// If ID prop does not match the selected work order then update selected work order
				// ID prop is final source of truth
				if(this.workOrder.id != newId) {
					this.$store.dispatch('getWorkOrder', newId);
				}				
			}
		},

		components: {
			'layout': Layout,
			'job-form': JobForm,
			'job-ticket': JobTicket,
			'wo-form': WoForm
		},

		methods: {
			createInvoice (){
				// Toggle loader
				this.invoiceCreating = true;
				// Dispatch action
				this.$store.dispatch('createInvoice', {
					work_order_id: this.id
				})
					.then(() => {
						// Toggle loader
						this.invoiceCreating = false;
						// Toggle dialog
						this.confirmInvoiceDialog = false;
					});
			}
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