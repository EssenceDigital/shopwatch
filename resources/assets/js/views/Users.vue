<template>
	<layout>
		<div slot="title">Users</div>

		<div slot="tools">
			<v-tooltip top>
		    <v-btn icon left slot="activator" @click="addDialog = true">
		      <v-icon>add_circle_outline</v-icon>
		    </v-btn>				
	      <span>Add user</span>
	    </v-tooltip>			
		</div>

		<div slot="content">
			<users-table></users-table>

			<v-dialog v-model="addDialog" persistent max-width="500px">
        <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="addDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
          <v-card-text>

          </v-card-text>
        </v-card>
      </v-dialog>			

		</div><!--/ Content slot -->

	</layout>
</template>

<script>
	import Layout from './_Layout';
	import UsersTable from './../components/tables/User-table';

	export default{
		data () {
			return {
				addDialog: false
			}
		},

		components: {
			'layout': Layout,
			'users-table': UsersTable
		}	
	}
</script>