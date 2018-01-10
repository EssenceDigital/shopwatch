<template>
 	<v-card class="elevation-0">
    <!-- Title -->
    <v-card-title>
      Work Orders
      <v-spacer></v-spacer>
      <v-text-field
        append-icon="search"
        label="Search"
        single-line
        hide-details
        v-model="search"
      ></v-text-field>
    </v-card-title>

    <!-- Table -->
    <v-data-table
      :headers="headers"
      :items="workOrders"
      :search="search"
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-xs-left">{{ props.item.customer.first + ' ' + props.item.customer.last }}</td>
          <td class="text-xs-left">{{ props.item.vehicle.year + ' ' + props.item.vehicle.make + ' ' + props.item.vehicle.model }}</td>
          <td>
            <span v-if="props.item.is_invoiced" class="success--text">Yes</span>
            <span v-else class="error--text">No</span>
          </td>
          <td>{{ props.item.jobs.length }}</td>
          <td class="text-xs-right">
            <v-tooltip left>
              <v-btn icon slot="activator" @click="$router.push('/work-orders/'+props.item.id)">
                <v-icon>launch</v-icon>
              </v-btn>   
              <span>View WO</span>           
            </v-tooltip>
          </td>
        </tr>
      </template>      
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>
    </v-data-table>
  </v-card>		
</template>

<script>

	export default{
		data (){
			return {
				search: '',
				loading: true,
 				headers: [
          {
            text: 'Customer',
            align: 'left',
            sortable: true,
            value: 'customer'
          },
          {
            text: 'Vehicle',
            align: 'left',
            sortable: true,
            value: 'vehicle'
          },
          {
            text: 'Invoiced?',
            align: 'left',
            sortable: true,
            value: 'is_invoiced'
          },
          {
            text: 'Jobs',
            align: 'left'
          },
          {
            text: '',
            align: 'right'
          }                                                                     
        ],
        editDialog: false,
        addVehicleDialog: false,
        viewVehiclesDialog: false

			}
		},

		computed: {
			workOrders (){
				return this.$store.getters.workOrders;
			}
		},

    components: {

    },

    methods: {
      
    },

		created (){
			this.$store.dispatch('filterWorkOrders', {
        created_at: '',
        is_invoiced: ''
      })
				.then((response) => {
					// Toggle table loader
					this.loading = false;
				});
		}		
	}
</script>