<template>
 	<v-card class="elevation-0">
    <!-- Title -->
    <v-card-title>
      Customers
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
      :items="customers"
      :search="search"
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-xs-left">{{ props.item.first }}</td>
          <td class="text-xs-left">{{ props.item.last }}</td>
          <td class="text-xs-left">{{ props.item.phone_one }}</td>
          <td class="text-xs-left">
            <span v-if="props.item.phone_two">
              {{ props.item.phone_two }}
            </span>
            <span v-else>
              <v-chip label outline color="red">None</v-chip>
            </span>
          </td>
          <td class="text-xs-right">
            <v-menu offset-y >
              <v-btn slot="activator" icon>
                <v-icon>settings</v-icon>
              </v-btn>              
              <v-list>
                <v-list-tile @click="openDialog(props.item, 'editDialog')">
                  <v-list-tile-title>Edit</v-list-tile-title>
                </v-list-tile>  
                <v-list-tile @click="openDialog(props.item, 'viewVehiclesDialog')">
                  <v-list-tile-title>View vehicles</v-list-tile-title>
                </v-list-tile>                 
                <v-list-tile @click="openDialog(props.item, 'addVehicleDialog')">
                  <v-list-tile-title>Add vehicle</v-list-tile-title>
                </v-list-tile>                               
              </v-list>
            </v-menu>            

          </td>
        </tr>
      </template>      
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>
    </v-data-table>

    <!-- Edit customer dialog -->
    <v-dialog v-model="editDialog" persistent max-width="500px">
      <v-card>
        <v-system-bar window class="blue darken-4">
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn icon class="mr-0" slot="activator" @click="closeDialog('editDialog')">
              <v-icon class="white--text mr-0">close</v-icon>
            </v-btn>                      
            <span>Close dialog</span>
          </v-tooltip>            
        </v-system-bar>
        <v-card-text>
          <customer-form action="updateCustomer" :customer="selectedCustomer" edit-state="true" @saved="closeDialog('editDialog')"></customer-form>
        </v-card-text>
      </v-card>
    </v-dialog> 

    <!-- Add vehicle dialog -->
    <v-dialog v-model="addVehicleDialog" persistent max-width="500px">
      <v-card>
        <v-system-bar window class="blue darken-4">
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn icon class="mr-0" slot="activator" @click="closeDialog('addVehicleDialog')">
              <v-icon class="white--text mr-0">close</v-icon>
            </v-btn>                      
            <span>Close dialog</span>
          </v-tooltip>            
        </v-system-bar>
        <v-card-text>
          <vehicle-form action="createVehicle" :customer="selectedCustomer.id" @saved="closeDialog('addVehicleDialog')"></vehicle-form>
        </v-card-text>
      </v-card>
    </v-dialog>  

    <!-- View vehicles dialog -->
    <v-dialog v-model="viewVehiclesDialog" persistent max-width="750px">
      <v-card>
        <v-system-bar window class="blue darken-4">
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn icon class="mr-0" slot="activator" @click="closeDialog('viewVehiclesDialog')">
              <v-icon class="white--text mr-0">close</v-icon>
            </v-btn>                      
            <span>Close dialog</span>
          </v-tooltip>            
        </v-system-bar>
        <v-card-text>
          <vehicles-table :vehicles="selectedCustomer.vehicles"></vehicles-table>
        </v-card-text>
      </v-card>
    </v-dialog>             

  </v-card>		
</template>

<script>
  import CustomerForm from './../forms/Customer-form';
  import VehicleForm from './../forms/Vehicle-form';
  import VehiclesTable from './../tables/Vehicles-table';

	export default{
		data (){
			return {
				search: '',
				loading: true,
 				headers: [
          {
            text: 'First Name',
            align: 'left',
            sortable: true,
            value: 'first'
          },
          {
            text: 'Last Name',
            align: 'left',
            sortable: true,
            value: 'last'
          },
          {
            text: 'Primary Phone',
            align: 'left',
            sortable: true,
            value: 'phone_one'
          },
          {
            text: 'Secondary Phone',
            align: 'left',
            sortable: true,
            value: 'phone_two'
          },
          {
            text: '',
            align: 'right',
            sortable: false,
          }                                    
        ],
        editDialog: false,
        addVehicleDialog: false,
        viewVehiclesDialog: false

			}
		},

		computed: {
			customers (){
				return this.$store.getters.customers;
			},

      selectedCustomer (){
        return this.$store.getters.selectedCustomer;
      }
		},

    components: {
      'customer-form': CustomerForm,
      'vehicle-form': VehicleForm,
      'vehicles-table': VehiclesTable
    },

    methods: {
      openDialog (customer, dialog){
        // The the customer for editing
        this.$store.commit('updateSelectedCustomer', customer);
        // Toggle Dialog
        this[dialog] = true;
      },

      closeDialog (dialog){
        // Toggle Dialog
        this[dialog] = false;        
      }
      
    },

		created (){
			this.$store.dispatch('filterCustomers')
				.then((response) => {
					// Toggle table loader
					this.loading = false;
				});
		}		
	}
</script>