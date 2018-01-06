<template>
	<layout>
		<div slot="title">Customers</div>

		<div slot="tools">
			<v-tooltip top>
		    <v-btn icon left slot="activator">
		      <v-icon>add_circle_outline</v-icon>
		    </v-btn>				
	      <span>Add customer</span>
	    </v-tooltip>			

		</div>
	</layout>
</template>

<script>
	import Layout from './_Layout';

	export default{
		components: {
			layout: Layout
		}	
	}
</script>