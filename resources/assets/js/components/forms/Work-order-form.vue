<template>
	<base-form 
		:action="action"
		:edit-state="editState"
		:hide-button="editState"
		remove-action="removeWorkOrder"
		:fields="form" 
		@saved="saved"
		@error="failed"
	>
		<template v-if="!editState" slot="form-fields">
			<!-- Customer input row -->
			<v-layout row class="pa-0">
				<v-flex xs10>
					<!-- Customer -->
					<v-select
		        :items="customersSelect"
		        v-model="customer"
		        label="Customer"
		        single-line
		        bottom
		      ></v-select>				
				</v-flex>
				<v-flex xs2>
					<v-tooltip top>
				    <v-btn icon slot="activator" class="ml-4 mt-3" @click="addCustomerDialog = true">
				      <v-icon color="green">add_circle_outline</v-icon>
				    </v-btn>				
			      <span>New customer</span>
			    </v-tooltip>				
				</v-flex>				
			</v-layout>

			<!-- Vehicle input row -->
			<v-layout row class="pa-0">
				<v-flex xs10>
					<!-- Vehicle -->
					<v-select
		        :items="vehiclesSelect"
		        v-model="form.vehicle_id.value"
		        :error-messages="form.vehicle_id.errors"
		        label="Vehicle"
		        single-line
		        bottom
		      ></v-select>				
				</v-flex>
				<v-flex xs2>
					<v-tooltip top v-if="customer">
				    <v-btn icon slot="activator" class="ml-4 mt-3" @click="addVehicleDialog = true">
				      <v-icon color="green">add_circle_outline</v-icon>
				    </v-btn>				
			      <span>New vehicle</span>
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

			<!-- Add vehicle dialog -->
			<v-dialog v-if="selectedCustomer" v-model="addVehicleDialog" persistent max-width="500px">
		    <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="addVehicleDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
		      <v-card-text>
		      	<vehicle-form action="createVehicle" :customer="selectedCustomer.id" @saved="addVehicleDialog = false"></vehicle-form>
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
	import VehicleForm from './Vehicle-form';

	export default{
		props: ['action', 'wo', 'editState'],

		data (){
			return {
				form: {
					id: {value: '', errors: []},
					vehicle_id: {value: '', errors: []}
				},
				customer: '',
				addCustomerDialog: false,
				addVehicleDialog: false
			}
		},

		computed: {
			customers (){
				return this.$store.getters.customers;
			},
			customersSelect (){
				return this.$store.getters.customersSelect;
			},
			selectedCustomer (){
				return this.$store.getters.selectedCustomer;
			},
			vehiclesSelect (){
				return this.$store.getters.selectedCustomerVehiclesSelect;
			}
		},

		watch: {
			wo (wo){
				// Populate the form for editing
				if(wo){
					Helpers.populateForm(this.form, wo);
				}
			},			
			/* 
			 * When the customer is selected we should update the selected customer in the store. 
			 * Allows the use of a computed property for the vehicle select list.
			*/
			customer (id){
				let index = Helpers.pluckObjectById(this.customers, 'id', id);
				this.$store.commit('updateSelectedCustomer', this.customers[index]);
			}
		},


		components: {
			'base-form': BaseForm,
			'customer-form': CustomerForm,
			'vehicle-form': VehicleForm
		},

		methods: {
			saved (id){
				// Clear form 
				Helpers.clearForm(this.form);
				Helpers.clearFormErrors(this.form);
				// Notify parent component
				this.$emit('saved', id);
			},

			failed (errors){
				Helpers.populateFormErrors(this.form, errors);
			}
		},

		created (){
			// Get customers
			if(! this.editState){
				this.$store.dispatch('filterCustomers');	
			}
			
			// Populate the form for editing
			if(this.wo){
				Helpers.populateForm(this.form, this.wo);
				console.log(this.form.id);
			}					
		}
	}
</script>