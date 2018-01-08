<template>
	<base-form 
		:action="action" 
		remove-action="removeWorkOrder"
		:fields="form" 
		@saved="saved"
		@error="failed"
	>
		<template slot="form-fields">
			<v-layout row class="pa-0">
				<v-flex xs10>
					<!-- Customer -->
					<v-select
		        :items="customers"
		        v-model="form.vehicle_id.value"
		        :error-messages="form.vehicle_id.errors"
		        label="Customer"
		        single-line
		        bottom
		      ></v-select>				
				</v-flex>
				<v-flex xs2>
					<v-tooltip top>
				    <v-btn icon left slot="activator" class="ml-4 mt-3" @click="addCustomerDialog = true">
				      <v-icon>add_circle_outline</v-icon>
				    </v-btn>				
			      <span>New customer</span>
			    </v-tooltip>				
				</v-flex>				
			</v-layout>

			<!-- Add customer dialog -->
			<v-dialog v-model="addCustomerDialog" persistent max-width="500px">
		    <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="addCustomerDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
		      <v-card-text>
		      	<customer-form action="createCustomer" @saved="addCustomerDialog = false"></customer-form>
		      </v-card-text>
		    </v-card>
		  </v-dialog>				 	    	    		    		
		</template>


    	
	</base-form>
</template>

<script>
	import BaseForm from './_Base-form';
	import Helpers from './../../app/helpers';	
	import CustomerForm from './Customer-form';

	export default{
		props: ['action'],

		data (){
			return {
				form: {
					id: {value: '', errors: []},
					vehicle_id: {value: '', errors: []}
				},
				addCustomerDialog: false
			}
		},

		computed: {
			customers (){
				return this.$store.getters.customersSelect;
			}
		},


		components: {
			'base-form': BaseForm,
			'customer-form': CustomerForm
		},

		methods: {
			saved (){
				// Clear form 
				Helpers.clearForm(this.form);
				Helpers.clearFormErrors(this.form);
				// Notify parent component
				this.$emit('saved');
			},

			failed (errors){
				Helpers.populateFormErrors(this.form, errors);
			}
		},

		created (){
			this.$store.dispatch('filterCustomers');			
		}
	}
</script>