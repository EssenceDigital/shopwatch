import Vue from 'vue';
import Vuex from 'vuex';
import ApiHelper from './../app/api';
import Helpers from './../app/helpers';

Vue.use(Vuex);

/** 
 * Holds state that can be used accross all components
*/
export const store = new Vuex.Store({
	/** 
	 * The cache values to be centralized 
	*/
	state: {
		users: [],
		selectedUser: {},
		customers: [],
		selectedCustomer: {},
		customersFilter: {

		},
		selectedVehicle: {},
		workOrders: [],
		workOrdersFilter: {

		},
		selectedWorkOrder: {},
		invoices: [],
		selectedInvoice: {},
		busConfig: {},
		authUser: {}
	},

	/**
	 * Methods that directly change the state cache.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	mutations: {
		updateBusConfig (state, payload){
			return state.busConfig = payload;
		}
	},

	/** 
	 * Actions that send API server calls and manipulate the database.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	actions: {
		getBusConfig (context, payload){
			return ApiHelper.getAction(context, '/bus-config', 'updateBusConfig');
		},	
		updateBusConfig (context, payload){
			return ApiHelper.postAction(context, payload, '/bus-config/update', 'updateBusConfig');
		},	

		getUsers(context, payload){
			return ApiHelper.getAction(context, '/users', 'updateUsers');
		},
		getUser (context, payload){
			return ApiHelper.getAction(context, '/users/'+payload, 'updateSelectedUser');
		},
		createUser (context, payload){
			return ApiHelper.postAction(context, payload, '/users/create', 'addUser');
		},		
		updateUser (context, payload){
			return ApiHelper.postAction(context, payload, '/users/update', 'updateUser');
		},
		removeUser (context, payload){
			return ApiHelper.removeAction(context, '/users/'+payload+'/remove', 'removeUser');
		},				
		changeUserPassword (context, payload){
			return ApiHelper.postAction(context, payload, '/users/change-password');
		},	
		changeAuthPassword (context, payload){
			return ApiHelper.postAction(context, payload, '/users/change-auth-password');
		},

		filterCustomers (context, payload){
			var url = '/customers';
			// Create payload 
			if(payload){
				if(payload.first != '') url += '/' + payload.first;
					else url += '/' + 0;

				if(payload.last != '') url += '/' + payload.last;
					else url += '/' + 0;							
			}
			// Use api to send the request
			return ApiHelper.getAction(context, url, 'updateCustomers');			
		},
		getCustomer (context, payload){
			return ApiHelper.getAction(context, '/customers/'+payload, 'updateSelectedCustomer');
		},
		getCustomerWorkOrders (context, payload){
			return ApiHelper.getAction(context, '/customers/'+payload+'/work-orders', 'updateWorkOrders');
		},
		getCustomerInvoices (context, payload){
			return ApiHelper.getAction(context, '/customers/'+payload+'/invoices', 'updateInvoices');
		},
		createCustomer (context, payload){
			return ApiHelper.postAction(context, payload, '/customers/create', 'addCustomer');
		},		
		updateCustomer (context, payload){
			return ApiHelper.postAction(context, payload, '/customers/update', 'updateCustomer');
		},
		removeCustomer (context, payload){
			return ApiHelper.removeAction(context, '/customers/'+payload+'/remove', 'removeCustomer');
		},		

		filterVehicles (context, payload){
			var url = '/vehicles';
			// Create payload 
			if(payload){
				if(payload.year != '') url += '/' + payload.year;
					else url += '/' + 0;

				if(payload.make != '') url += '/' + payload.make;
					else url += '/' + 0;		

				if(payload.model != '') url += '/' + payload.model;
					else url += '/' + 0;

				if(payload.plate_number != '') url += '/' + payload.plate_number;
					else url += '/' + 0;																	
			}
			// Use api to send the request
			return ApiHelper.getAction(context, url, 'updateVehicles');			
		},		
		getVehicle (context, payload){
			return ApiHelper.getAction(context, '/vehicles/'+payload, 'updateSelectedVehicle');
		},
		getVehicleWorkOrders (context, payload){
			return ApiHelper.getAction(context, '/vehicles/'+payload+'/work-orders', 'updateVehicles');
		},
		getVehicleInvoices (context, payload){
			return ApiHelper.getAction(context, '/vehicles/'+payload+'/invoices', 'updateInvoices');
		},		
		createVehicle (context, payload){
			return ApiHelper.postAction(context, payload, '/vehicles/create', 'addVehicle');
		},		
		updateVehicle (context, payload){
			return ApiHelper.postAction(context, payload, '/vehicles/update', 'updateVehicle');
		},
		removeVehicle (context, payload){
			return ApiHelper.removeAction(context, '/vehicles/'+payload+'/remove', 'removeVehicle');
		},

		filterWorkOrders (context, payload){
			var url = '/work-orders';
			// Create payload 
			if(payload){
				if(payload.created_at != '') url += '/' + payload.created_at;
					else url += '/' + 0;

				if(payload.is_invoiced != '') url += '/' + payload.is_invoiced;
					else url += '/' + 0;																			
			}
			// Use api to send the request
			return ApiHelper.getAction(context, url, 'updateWorkOrders');			
		},	
		getWorkOrder (context, payload){
			return ApiHelper.getAction(context, '/work-orders/'+payload, 'updateSelectedWorkOrder');
		},
		createWorkOrder (context, payload){
			return ApiHelper.postAction(context, payload, '/work-orders/create', 'addWorkOrder');
		},						
		removeWorkOrder (context, payload){
			return ApiHelper.removeAction(context, '/work-orders/'+payload+'/remove', 'removeWorkOrder');
		},

		getJob (context, payload){
			return ApiHelper.getAction(context, '/jobs/'+payload, 'updateSelectedJob');
		},		
		createJob (context, payload){
			return ApiHelper.postAction(context, payload, '/jobs/create', 'addJob');
		},		
		updateJob (context, payload){
			return ApiHelper.postAction(context, payload, '/jobs/update', 'updateJob');
		},
		removeJob (context, payload){
			return ApiHelper.removeAction(context, '/jobs/'+payload+'/remove', 'removeJob');
		},

		createJobPart (context, payload){
			return ApiHelper.postAction(context, payload, '/job-parts/create', 'addJobPart');
		},		
		updateJobPart (context, payload){
			return ApiHelper.postAction(context, payload, '/job-parts/update', 'updateJobPart');
		},
		removeJobPart (context, payload){
			return ApiHelper.removeAction(context, '/job-parts/'+payload+'/remove', 'removeJobPart');
		},

		filterInvoices (context, payload){
			var url = '/invoices';
			// Create payload 
			if(payload){
				if(payload.from_date != '') url += '/' + payload.from_date;
					else url += '/' + 0;

				if(payload.to_date != '') url += '/' + payload.to_date;
					else url += '/' + 0;		

				if(payload.is_paid != '') url += '/' + payload.is_paid;
					else url += '/' + 0;		
																															
				if(payload.customer_id != '') url += '/' + payload.customer_id;
					else url += '/' + 0;		
			}
			// Use api to send the request
			return ApiHelper.getAction(context, url, 'updateInvoices');			
		},	
		getInvoice (context, payload){
			return ApiHelper.getAction(context, '/invoices/'+payload, 'updateSelectedInvoice');
		},			
		createInvoice (context, payload){
			return ApiHelper.postAction(context, payload, '/invoices/create', 'addInvoice');
		},
		markInvoicePaid (context, payload){
			return ApiHelper.postAction(context, payload, '/invoices/mark-paid', 'updateInvoice');
		},
		removeInvoice (context, payload){
			return ApiHelper.removeAction(context, '/invoices/'+payload+'/remove', 'removeInvoice');
		}

	},

	/** 
	 * Getters that access the state directly (For components)
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.	 
	*/
	getters: {

	}

});