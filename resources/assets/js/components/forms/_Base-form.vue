/* 
 *	Has logic to validate and submit a form
 *	Has logic to populate a form and put the form into ‘edit state’
 *	Emits a saved event when complete
 *	Has slot to show input fields
 *	Has a prop to indicate fields and validation
*/
<template>
	
	<div>	

		<slot name="form-fields"></slot>

		<v-divider class="mt-3 mb-3"></v-divider>

		<v-btn 
			type="submit" 
			color="primary" 
			:loading="isSaving" 
			flat block
			@click.native.stop="save"
		>
			<v-icon left>input</v-icon> Save
		</v-btn>

		<v-tooltip top v-if="editState">
			<v-btn slot="activator" icon @click="removeDialog = true">
				<v-icon>delete_sweep</v-icon>
			</v-btn>							
      <span>Remove</span>
    </v-tooltip>

    <!-- Edit user dialog -->
    <v-dialog 
    	v-if="editState"
    	v-model="removeDialog" 
    	persistent 
    	max-width="300px"
    >
      <v-card>
        <v-system-bar window class="red darken-4">
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn icon class="mr-0" slot="activator" @click="removeDialog = false">
              <v-icon class="white--text mr-0">close</v-icon>
            </v-btn>                      
            <span>Close dialog</span>
          </v-tooltip>            
        </v-system-bar>
        <v-card-text>
          Permanently remove this?
        </v-card-text>
        <v-card-actions>
        	<v-spacer></v-spacer>
					<v-btn color="green darken-1" flat @click.native="removeDialog = false">Cancel</v-btn>
          <v-btn color="red darken-1" flat :loading="isRemoving" @click.native="remove">Remove</v-btn>    
          <v-spacer></v-spacer>    	
        </v-card-actions>
      </v-card>
    </v-dialog>     


	</div>

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

			removeAction: {
				type: String,
				default: ''
			},

			editState: {
				default: false
			}
		},

		data (){
			return {
				isSaving: false,
				removeDialog: false,
				isRemoving: false,
				serverError: false,
				errorMsg: ''
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
    				this.$emit('saved', response.id);
      		})
      		.catch((error) => {
      			console.log(error);
      			// Toggle loader
      			this.isSaving = false;

      			if(error.response){
	      			// Form validation errors
	      			if(error.response.data){

								// Check for error response from Laravel controller
								if(error.response.data.result == 'error'){
									this.$router.app.$emit('snackbar', {
										text: error.response.data.message
									});
								}

								// Form errors
								if(error.response.data.errors){
			      			// Cache errors
			      			let errors = error.response.data.errors;
			      			// Send errors to calling form
			      			this.$emit('error', errors);  									
								}	      				
    				
	      			}      				
      			}
      		});
			},

			remove (){
      	// Toggle loader
      	this.isRemoving = true;

      	// Dispatch event to store
      	this.$store.dispatch(this.removeAction, this.fields.id.value)
      		.then((response) => {
      			// Toggle loader and dialog
      			this.removeDialog = false;
      			this.isRemoving = false;
    				// The form was saved
    				this.$emit('saved');
      		})
      		.catch((error) => {
      			// Toggle loader and dialog
      			this.removeDialog = false;
      			this.isRemoving = false;

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
