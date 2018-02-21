<template>
	<layout>
		<div slot="title">Dashboard</div>

		<div slot="tools">
			<v-tooltip left>
		    <v-btn color="info" left slot="activator" @click="addWoDialog = true">
		      <v-icon left>note_add</v-icon> Work Order
		    </v-btn>				
	      <span>Start WO</span>
	    </v-tooltip>						
		</div>

		<div slot="content">
	
			<wo-container></wo-container>

			<!-- Add WO dialog -->
			<v-dialog v-model="addWoDialog" persistent max-width="500px">
        <v-card>
				 	<v-system-bar window class="blue darken-4">
			      <v-spacer></v-spacer>
						<v-tooltip top>
				      <v-btn icon class="mr-0" slot="activator" @click="addWoDialog = false">
				      	<v-icon class="white--text mr-0">close</v-icon>
				      </v-btn>											
				      <span>Close dialog</span>
				    </v-tooltip>			      
			    </v-system-bar>
          <v-card-text>
          	<work-order-form action="createWorkOrder" @saved="workOrderSaved"></work-order-form>
          </v-card-text>
        </v-card>
      </v-dialog>
		</div><!--/ Content slot -->

	</layout>
</template>

<script>
	import Layout from './_Layout';
	import WorkOrderForm from './../components/forms/Work-order-form';
	import WorkOrdersContainer from './../components/containers/Wo-tickets-container';

	export default{
		data () {
			return {
				addWoDialog: false
			}
		},

		components: {
			'layout': Layout,
			'work-order-form': WorkOrderForm,
			'wo-container': WorkOrdersContainer
		},

		methods: {
			workOrderSaved (id){
				// Toggle digalog
				this.addWoDialog = false;
				// Redirect view
				this.$router.push('/work-orders/'+id);   
			}
		}
	}
</script>