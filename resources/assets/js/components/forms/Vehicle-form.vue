<template>
	<base-form 
		:action="action" 
		remove-action="removeVehicle"
		:edit-state="editState"
		:fields="form" 
		@saved="saved"
		@error="failed"
	>
		<template slot="form-fields">
			<!-- Make -->
			<v-text-field
	      label="Make"
	 			v-model="form.make.value"
	 			:error-messages="form.make.errors"
	    ></v-text-field>	
	    <!-- Model -->
			<v-text-field
	      label="Model"
	      v-model="form.model.value"
	      :error-messages="form.model.errors"
	    ></v-text-field>  
	    <!-- Year -->
			<v-text-field
	      label="Year"
	      v-model="form.year.value"
	      :error-messages="form.year.errors"
	    ></v-text-field>  
	    <!-- VIN -->
			<v-text-field
	      label="VIN"
	      v-model="form.vin.value"
	      :error-messages="form.vin.errors"
	    ></v-text-field> 
	    <!-- Plate Number -->
			<v-text-field
	      label="Plate Number"
	      v-model="form.plate_number.value"
	      :error-messages="form.plate_number.errors"
	    ></v-text-field>	    	    	    		    		
		</template>
	</base-form>
</template>

<script>
	import BaseForm from './_Base-form';
	import Helpers from './../../app/helpers';	

	export default{
		props: ['action', 'vehicle', 'customer', 'editState'],

		data (){
			return {
				form: {
					id: {value: '', errors: []},
					customer_id: {value: '', errors: []},
					make: {value: '', errors: []},
					model: {value: '', errors: []},
					year: {value: '', errors: []},
					vin: {value: '', errors: []},
					plate_number: {value: '', errors: []}						
				}
			}
		},

		watch: {
			vehicle (vehicle){
				if(vehicle){
					Helpers.populateForm(this.form, vehicle);
				}
			},

			customer (id){
				if(id){
					this.form.customer_id.value = id;
				}
			}
		},

		components: {
			'base-form': BaseForm
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
		}
	}
</script>