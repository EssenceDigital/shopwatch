<template v-if="job">
	<v-flex xs12>
		<v-card flat>
			<v-card-title class="pb-0">
        <v-flex xs10>
        	<span class="grey--text"><strong>{{ job.title }}</strong></span>
        </v-flex>
      	<v-flex xs2 class="text-xs-right">
					<v-tooltip top>
	      		<v-btn icon slot="activator" @click="addPartsDialog = true">
	      			<v-icon>library_add</v-icon>
	      		</v-btn>				    			
			      <span>Add parts</span>
			    </v-tooltip>
					<v-tooltip top>
	      		<v-btn icon slot="activator" @click="editJobDialog = true">
	      			<v-icon>edit</v-icon>
	      		</v-btn>				    			
			      <span>Edit job</span>
			    </v-tooltip>      		
      	</v-flex>
      </v-card-title>
			<v-card-text class="pt-0">
				<v-layout row>
					<p>
						{{ job.description }}
					</p>
				</v-layout>
				<v-divider class="mb-2"></v-divider>
				<v-layout row>

					<v-flex xs4 class="text-xs-right">
						<p>
							<strong>Labour total:</strong><br>{{ job.job_labour_total | money }}
						</p>						
					</v-flex>
					<v-flex xs4 class="text-xs-right">
						<p>
							<strong>Parts total:</strong><br>{{ job.parts_total_billed | money }}
						</p>						
					</v-flex>
					<v-flex xs4 class="text-xs-right">
						<p>							
							<strong>Job total:</strong><br>{{ job.job_grand_total | money }}
						</p>						
					</v-flex>										
				</v-layout>
				</v-layout>
			</v-card-text>
		</v-card>

		<!-- Edit job dialog -->
		<v-dialog v-model="editJobDialog" persistent max-width="500px">
      <v-card>
			 	<v-system-bar window class="blue darken-4">
		      <v-spacer></v-spacer>
					<v-tooltip top>
			      <v-btn icon class="mr-0" slot="activator" @click="editJobDialog = false">
			      	<v-icon class="white--text mr-0">close</v-icon>
			      </v-btn>											
			      <span>Close dialog</span>
			    </v-tooltip>			      
		    </v-system-bar>
        <v-card-text>
        	<job-form action="updateJob" :job="job" edit-state="true" @saved="editJobDialog = false"></job-form>
        </v-card-text>
      </v-card>
    </v-dialog>

		<!-- Add parts dialog -->
		<v-dialog v-model="addPartsDialog" persistent max-width="500px">
      <v-card>
			 	<v-system-bar window class="blue darken-4">
		      <v-spacer></v-spacer>
					<v-tooltip top>
			      <v-btn icon class="mr-0" slot="activator" @click="addPartsDialog = false">
			      	<v-icon class="white--text mr-0">close</v-icon>
			      </v-btn>											
			      <span>Close dialog</span>
			    </v-tooltip>			      
		    </v-system-bar>
        <v-card-text>
        	<parts-form action="createJobPart" :job="job.id" @saved="addPartsDialog = false"></parts-form>
        </v-card-text>
      </v-card>
    </v-dialog>    

	</v-flex>

</template>

<script>
	import JobForm from './../forms/Job-form';
	import PartsForm from './../forms/Job-parts-form';

	export default{
		props: ['job'],

		data (){
			return {
				editJobDialog: false,
				addPartsDialog: false
			}
		},

		components: {
			'job-form': JobForm,
			'parts-form': PartsForm
		}
	}
</script>