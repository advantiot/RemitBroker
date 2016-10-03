<?php include $_SERVER['DOCUMENT_ROOT']."/web/header.php"; ?>

  <section class="title">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <span>Money Transfer Message Broker</span>
        </div>
      </div>
    </div>
  </section>
  <!-- / .title -->       

  <!-- MTMB -->
  <section id="mtmb" class="container">
    <div class="row">
      <!-- Left Column -->
      <div class="col-md-6">
        <h3>THE PROBLEM</h3>
        <p>
        	Money transfer companies operate through networks of branches and agents. Agents include both small mom-and-pop stores and large banks or retail chains. The process of forwarding transactions from the “send” agent to the “payout” agent requires complex integrations between systems. Since multiple partners are involved, resulting in a multi-point network, there are many-to-many system integrations required.
		</p>
		<p>	
			Every company has its own API (application programming interface) which allows partners to either push transactions to their system or pull transactions from their system. Status updates are pulled or pushed correspondingly. Further, FX rates have to be exchanged and updated on a daily or more frequent basis. Modifications and cancellation requests and acknowledgements also need to be exchanged. 
		</p>
		<p>
			With all this complexity, the initial integration requires a lot of effort on the part of every company and constantly changing environments within companies and globally requires the integration to be constantly maintained and upgraded. Delays in integration lead to a loss of competitive advantage and a direct loss of revenues. There is also a cost of IT and other support services to build and maintain these integrations.
		</p>
	</div>
     <!-- /Left Column -->
     <!-- Right Column -->
	<div class="col-md-6">
       <h3>THE SOLUTION</h3>
       <p>
			The AdvantAPI MTMB solution will change this many-to-many integration model to a hub-and-spoke integration model which will require each company to work with a single integration end-point. The AdvantAPI MTMB solution will also introduce a standard data exchange format, further reducing integration complexity.
		</p>
		<p>	
			Business will be able to, rather will have to, maintain their own private data stores, keeping their customer and transaction information confidential and maintain and run their own compliance rules to ensure they only send or receive transactions that are compliant with their company’s or their country’s regulations. But the platform will help to significantly simplify the integration.
       </p>

		
     		<div style="text-align:center;"> 
                    <p><img src="images/pointhubspoke.gif" alt=""></p>
                    <h5>From Point-to-Point to Hub-and-Spoke</h5>                    
		</div>
<p>&nbsp;</p>

</div> <!-- /Right Column -->
</div> <!-- /First Row -->

<hr />
    <div class="row">
     <div class="col-md-12">
	<h3>THE MESSAGE BROKER AND API</h3>
		<p>
		The core of the MTMB platform is the message broker. This component acts as a communication channel between the send agent and the payout agent. The important features of the message broker are explained below. The platform is on the cloud, which means that it is very scalable and will be available with a very high uptime SLA, keeping your business running uninterrupted through both peak and lean periods. The AdvantAPI MTMB solution will be a PaaS (Platform as a Service) on the cloud. This brings several advantages to a business including no investment in hardware or software to integrate with this platform, scalability of infrastructure to keep pace with business rowth and flexible pay-per-use pricing.
		</p>
	
</div>
</div>

<hr />
    <div class="row">

     <!-- Left Column -->
     <div class="col-md-6">
       		<p>
     		<div style="text-align:center;"> 
                    <p><img src="images/technical_components.png" class="img-responsive" alt=""></p>
                    <h5>Technical Components</h5>                    
		</div>
		</p>
		<h4>STANDARDIZED DATA EXCHANGE STRUCTURES AND PROTOCOLS</h4>
		<p>
		All data exchanges will use a standardized structure, defined by the MTMB platform. This data standardization takes into consideration requirements for transactions to be executed on this platform while also allowing for limited customization in exceptional cases.
		The access to the platform is via a standard REST API (Application Programming Interface) which can be invoked by any client written in any technology. However the access mechanism will remain the same, making it easy to program multiple client channels including web and mobile.
		</p>
	
		
		<h4>ASYNCHRONOUS COMMUNICATION</h4>
		<p>
			The technical core of the platform is designed around message queues. Producer applications can post transactions or responses and disconnect with the guarantee that the data will be available for the intended consumer. Producer applications will also be able to check and confirm if the consumer has processed the data.
		</p>
			
   </div> <!-- /Left Column -->
      <!-- Right Column -->
      <div class="col-md-6">
		<h4>HIGH AVAILABILITY</h4>
		<p>
			The platform is on the cloud, which means that it is very scalable and will be available with a very high uptime SLA, keeping your business running uninterrupted through both peak and lean periods.
		</p>	
		<h4>INTEGRITY, CONFIDENTIALITY AND NON-REPUDIATION</h4>
		<p>
		The platform uses the PKI (public key – private key) infrastructure to enforce security. Every agent on the platform is issued an individually unique digital certificate (public key-private key pair). Agents will share their public keys with their partner agents only. Send agents will encrypt transaction data using the public key of the payout agent for which the transaction is intended, which means only that payout agent can decrypt and process the message. Similarly, a payout agent will encrypt response data using the public key of the send agent for which the response is intended which means that only that send agent can process the response. 
		The use of digital certificates will also provide a non-repudiation mechanism, whereby it will not be possible for an agent to dispute the fact that a transaction was sent from the agent’s system.
		</p>
		
		<h4>SECURE AUTHENTICATION AND AUTHORIZATION</h4>
		<p>
			The platform will be accessible only via the API, which will use a secure authentication mechanism. In addition, an authorization matrix of agents will ensure that agents will only be able to communicate with other agents with whom they have an agreement and are authorized to communicate.
		</p>

		<p>
     		<div style="text-align:center;"> 
                    <p><img src="images/security_model.png" class="img-responsive" alt=""></p>
                    <h5>Security Model</h5>                    
		</div>
		<p>	
     </div>
     <!-- /Right Column -->
</div> <!-- /Second Row -->
</section>
<!-- /MTMB -->

<?php include $_SERVER['DOCUMENT_ROOT']."/web/footer.php"; ?>
