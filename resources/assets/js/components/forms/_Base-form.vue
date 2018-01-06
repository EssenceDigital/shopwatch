/* 
 *	Has logic to validate and submit a form
 *	Has logic to populate a form and put the form into ‘edit state’
 *	Emits a saved event when complete
 *	Has slot to show input fields
 *	Has a prop to indicate fields and validation
*/
<template>
	
	<v-form @submit.default.stop="save">
		<slot name="form-fields"></slot>

		<v-divider class="mt-3 mb-3"></v-divider>

		<v-btn 
			type="submit" 
			color="primary" 
			:loading="isSaving" 
			flat block
		>
			<v-icon left>input</v-icon> Save
		</v-btn>

		<v-tooltip top v-if="editState">
			<v-btn slot="activator" icon>
				<v-icon>delete_sweep</v-icon>
			</v-btn>							
      <span>Remove</span>
    </v-tooltip>


	</v-form>

</template>

<script>
	export default{

		props: {
			fields: {
				type: Object,
				required: true
			},

			action: {
				type: String,
				default: ''
			},

			editState: {
				type: Boolean,
				default: false
			}
		},

		data (){
			return {
				isSaving: false
			}
		},

		methods: {
			save (){
      	// Toggle loader
      	this.isSaving = true;
      	// Populate data for POST Request
      	let data = {};
      	for(let field in this.fields){
      		data[field] = this.fields[field].value;
      	}

      	// Dispatch event to store
      	this.$store.dispatch(this.action, data)
      		.then((response) => {
      			// Toggle loader
      			this.isSaving = false;
    				// The form was saved
    				this.$emit('saved');
      		})
      		.catch((error) => {
      			// Toggle loader
      			this.isSaving = false;
      			// Form validation errors
      			if(error.response.data){
	      			// Cache errors
	      			let errors = error.response.data.errors;
	      			// Send errors to calling form
	      			this.$emit('error', errors);      				
      			}

      		});
			}
		}

	}
</script>
