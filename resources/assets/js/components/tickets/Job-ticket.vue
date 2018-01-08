<template>
	<v-flex xs12>
		<v-card flat>
			<v-card-title>
        <v-flex xs10>
        	<span class="grey--text"><strong>{{ job.title }}</strong></span>
        </v-flex>
      	<v-flex xs2>
					<v-tooltip top>
	      		<v-btn icon slot="activator">
	      			<v-icon>library_add</v-icon>
	      		</v-btn>				    			
			      <span>Add parts</span>
			    </v-tooltip>
					<v-tooltip top>
	      		<v-btn icon slot="activator">
	      			<v-icon>edit</v-icon>
	      		</v-btn>				    			
			      <span>Edit job</span>
			    </v-tooltip>      		
      	</v-flex>
      </v-card-title>
			<v-card-text class="pt-0">
				<p>
					sdfsdf
				</p>
			</v-card-text>
		</v-card>		
	</v-flex>

</template>

<script>
	export default{
		props: ['job']
	}
</script>