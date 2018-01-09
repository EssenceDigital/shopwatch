<template v-if="id">
	<layout v-if="invoice">
		<div slot="title">Invoice</div>

		<div slot="tools">	
		
		</div>


		<div slot="content">
			<v-card flat>
				<!-- Top overview container -->
				<v-container fluid>
					<!--Business headline -->
					<v-layout row>
						<v-flex xs6>
								<h1>UNA AUTO SERVICE</h1>
								<h4 class="ml-1">Repairs, detailing, maintenance</h4>						
						</v-flex>
						<v-flex xs6 class="text-xs-right">
								<h1 class="grey--text">INVOICE</h1>	
								<small class="grey--text">(#{{ invoice.id }})</small>					
						</v-flex>							
					</v-layout>

					<v-divider class="mt-2 mb-2"></v-divider>

					<!--WO, Invoice dates -->				
					<v-layout row>
						<v-flex xs4>
							<strong>INVOICE DATE:</strong> {{ invoice.created_at }}
						</v-flex>	
						<v-spacer></v-spacer>						
						<v-flex xs4 class="text-xs-right">
							<strong>WO DATE:</strong> {{ invoice.work_order.created_at }}
						</v-flex>					
					</v-layout>
				</v-container>

				<!-- Customer and vehicle container -->
				<v-container fluid class="pa-2">
					<!-- Customer and vehicle headings -->
					<v-layout row class="red darken-4 mt-2">
						<v-flex xs6>
							<h3 class="white--text pa-2">CUSTOMER</h3>
						</v-flex>
						<v-flex xs6>
							<h3 class="white--text pa-2">VEHICLE</h3>
						</v-flex>						
					</v-layout>

					<v-layout row class="red lighten-4 pt-2">
						<!-- Customer info -->
						<v-flex xs6>
							<v-container fluid>
								<v-layout row>
									<v-flex xs4>
										<strong>NAME:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.customer.first }} {{ invoice.customer.last }}
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs4>
										<strong>PRIMARY:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.customer.phone_one }}
									</v-flex>
								</v-layout>
								<v-layout row v-if="invoice.customer.phone_two">
									<v-flex xs4>
										<strong>SECONDARY:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.customer.phone_two }}
									</v-flex>
								</v-layout>									
							</v-container>														
						</v-flex>
						<!-- Vehicle info -->
						<v-flex xs6>
							<v-container fluid>
								<v-layout row>
									<v-flex xs4>
										<strong>YEAR:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.vehicle.year }}
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs4>
										<strong>MAKE:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.vehicle.make }}
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs4>
										<strong>MODEL:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.vehicle.model }}
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs4>
										<strong>VIN:</strong>
									</v-flex>
									<v-flex xs3>
										{{ invoice.vehicle.vin }}
									</v-flex>
								</v-layout>									
							</v-container>																										
						</v-flex>						
					</v-layout>					
				</v-container>

				<!-- Jobs heading container -->
				<v-container fluid class="pa-2">
					<!-- Job headings -->
					<v-layout row class="red darken-4 mt-2">
						<v-flex xs8>
							<h3 class="white--text pa-2">JOB PERFORMED</h3>
						</v-flex>
						<v-flex xs1 class="text-xs-right">
							<h3 class="white--text pa-2">HOURS</h3>
						</v-flex>
						<v-flex xs1 class="text-xs-right">
							<h3 class="white--text pa-2">RATE</h3>
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<h3 class="white--text pa-2">TOTAL</h3>
						</v-flex>																				
					</v-layout>	

					<!-- All jobs -->
					<job-row v-for="job in invoice.work_order.jobs" :job="job" :key="job.id" :invoice-state="true"></job-row>

					<v-divider class="mt-2 mb-2"></v-divider>

					<!-- Job labour total -->
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>SUBTOTAL:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.total_labour | money }}
							</p>
						</v-flex>						
					</v-layout>
				</v-container>

				<!-- Parts heading container -->
				<v-container fluid class="pa-2" v-if="invoice.total_parts > 0">
					<!-- Parts headings -->
					<v-layout row class="red darken-4 mt-2">
						<v-flex xs3>
							<h3 class="white--text pa-2">PART INVOICE #</h3>
						</v-flex>
						<v-flex xs7>
							<h3 class="white--text pa-2">PARTS</h3>
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<h3 class="white--text pa-2">TOTAL</h3>
						</v-flex>																				
					</v-layout>		

					<div v-for="job in invoice.work_order.jobs" :key="job.id">
						<part-row v-for="part in job.parts" :part="part" :key="part.id" :invoice-state="true"></part-row>
					</div>		
					
					<v-divider class="mt-4 mb-2"></v-divider>

					<!-- Job parts total -->
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>SUBTOTAL:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.total_parts | money }}
							</p>
						</v-flex>						
					</v-layout>												
				</v-container>

				<!-- Grand totals container -->
				<v-container fluid class="pa-2 mt-2">
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>GST RATE:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.gst_rate | gst }}
							</p>
						</v-flex>	
					</v-layout>					
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>TOTAL LABOUR:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.total_labour | money }}
							</p>
						</v-flex>	
					</v-layout>	
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>TOTAL PARTS:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.total_parts | money }}
							</p>
						</v-flex>	
					</v-layout>						
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>TOTAL GST:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.gst_total | money }}
							</p>
						</v-flex>	
					</v-layout>		
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>SHOP SUPPLIES:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.shop_supply_rate | money }}
							</p>
						</v-flex>	
					</v-layout>	
					<!-- Grand total divider -->
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs4>
							<v-divider class="mt-2 mb-2"></v-divider>	
						</v-flex>
					</v-layout>

					<!-- Grand total -->
					<v-layout row>
						<v-spacer></v-spacer>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								<strong>GRAND TOTAL:</strong>
							</p>							
						</v-flex>
						<v-flex xs2 class="text-xs-right">
							<p class="pa-2 mb-0">
								{{ invoice.grand_total | money }}
							</p>
						</v-flex>	
					</v-layout>	
				</v-container>

				<v-container fluid class="mt-5">
					<v-layout row>
						<v-flex xs12 class="text-xs-center">
							<small>
								If you have any questions or concerns regarding this invoice please contact us.
							</small>
						</v-flex>
					</v-layout>
				</v-container>

			</v-card>
			<!--
			<v-layout row>
				<v-flex xs12>
					<v-card flat>
						
					</v-card>
				</v-flex>
			</v-layout>	-->


			<!-- Jobs -->
			<!--<v-layout row wrap>
				<job-ticket v-for="job in invoice.work_order.jobs" :job="job" :key="job.id" :invoice-state="true" class="mt-4"></job-ticket>
			</v-layout>-->
		</div><!--/ Content slot -->

	</layout>
</template>

<script>
	import Layout from './_Layout';
	import JobForm from './../components/forms/Job-form';
	import JobRow from './../components/tickets/Job-row';
	import PartRow from './../components/tickets/Part-row';

	export default{
		props: ['id'],

		data () {
			return {

			}
		},

		computed: {
			invoice (){
				return this.$store.getters.selectedInvoice;
			}
		},

		watch: {
			id (newId){
				// If ID prop does not match the selected work order then update selected work order
				// ID prop is final source of truth
				if(this.invoice.id != newId) {
					this.$store.dispatch('getInvoice', newId);
				}				
			}
		},

		components: {
			'layout': Layout,
			'job-form': JobForm,
			'job-row': JobRow,
			'part-row': PartRow
		},

		methods: {

		},

		created (){
			// If ID prop does not match the selected work order then update selected work order
			// ID prop is final source of truth
			if(! this.invoice.id || this.invoice.id != this.id) {
				this.$store.dispatch('getInvoice', this.id)
			}
		}	
	}
</script>