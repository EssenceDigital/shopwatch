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

	},

	/**
	 * Methods that directly change the state cache.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	mutations: {
	
	},

	/** 
	 * Actions that send API server calls and manipulate the database.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	actions: {
		
	},

	/** 
	 * Getters that access the state directly (For components)
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.	 
	*/
	getters: {

	}

});