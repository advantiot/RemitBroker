<?php include $_SERVER['DOCUMENT_ROOT']."/web/header.php"; ?>

    <section class="title">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span>Frequently Asked Questions</span>
                </div>
            </div>
        </div>
    </section>
    <!-- / .title -->       

    <section id="faqs" class="container">
        <ul class="faq">
         <li>
            <span class="number">01</span>
            <div>
                <h4>Who are the entities in a typical use case and what roles do they play?</h4>
                <p>
		There are three main entities in a typical integration use case with the AdvantAPI Money Transfer Message Broker (MTMB). The Send agent, the Payout agent and MTMB. The Send Agent will post transactions to the Broker using the Broker API. The Broker will hold the transaction until the Payout agent requests for it using the Broker API. The Payout agent will post a response to the Send agent, again using the Broker API which will be held by the Broker until the Send agent requests for it using the API.
		</p>
            </div>
        </li>

        <li>
            <span class="number">02</span>
            <div>
                <h4>How is this approach more efficient that connecting to each other's API?</h4>
                <p>
		As long as there are only a few partners you connect to, using each other's APIs may work fine. But once the number of partners increases, things start to get complicated. First each partner has their own API and data exchange standards, which means your developers are spending time, effort and money on integrating with every partner. Second, if you have your own API, you have to continue to keep that updated, sometimes even needing to customize it for some partner requirements. And finally, you or any of your partner agents may not have the wherewithal to develop and maintain an API, which means you will need to integrate using a file transfer mechanism which is very inefficient and error prone.</p>
<p>The MTMB approach does not require you to build or develop an API. You only need to develop a client application to integrate with the platform API once and connecting with every partner thereafter is only a matter of data configuration. Further, data exchange formats and structures are standardized so you can be guaranteed to send and receive all the information you need to process a transaction without haveing to go back and forth to gather missing data.
		</p>
            </div>
        </li>

        <li>
            <span class="number">03</span>
            <div>
                <h4>Is the patform secure?</h4>
                <p>
		The platform uses TSL (previously known as SSL) to ensure data sent over the internet is encrypted and cannot be tampered with in transit. The broker take security and confidentiality to another level but using peer-to-peer asymmetric key encryption. Every agent on the platform is issued a unique public-private key pair (commonly known as digital certificates). Agents must always keep their private key confidential. The public key is for sharing but that too only with partners you intend to exchange transactions with. To ensure confidentiality, the broker does not store or know any of these keys and therefore cannot view any data being exchanged. The data remains completely confidential between the sender and the receiver.
		</p>
<p>All agents are issued one mater password which can be used to set up partner connections, exchange keys and manage other platform parameters. This master password shouldbe kept confidential.
            </div>
        </li>

        <li>
            <span class="number">04</span>
            <div>
                <h4>What technology does the platform support?</h4>
                <p>
		Your client applications can be written in any technology environment and programming language as long as it supports REST API calls. The developer guide includes sample code and instructions for PHP, Python, Ruby on Rails, Java and C#.
		</p>
		<p>
		 The data exchange format is JSON. Although we contemplated supporting XML as well, but the trend is highly in favor of JSON as a data exchange format for APIs. XML is a very powerful language but is too heavy for simple data exchange.
		</p>
            </div>
        </li>

        <li>
            <span class="number">05</span>
            <div>
                <h4>How does the pricing work?</h4>
                <p>
		We are currently charging a fixed price based on consumption tiers (categorised by number of partners, number of transactions and number of API calls). You should measure the costs per transaction which, at the current pricing, never exceeds US$0.05 per transaction (that's 5 US cents per transaction). the only add-on cost is for your digital certificate, which you must procure from a global certification authority such as Verisign. This may vary yb provider and other parameters but the cost is only in the low hundreds of US dollars annually.
		</p>
            </div>
        </li>

        <li>
            <span class="number">06</span>
            <div>
                <h4>Do I have to replace my existing applications?</h4>
                <p>
		Not at all. In fact all your applications are needed to complete the transaction capture and processing and database functions. Note that you are still responsible for ensuring data is captured correctly, all amount calculations are correct and all compliance checks are executed.
		</p>
		<p>
		The only component you need to re-write is the one that connects to your partners' systems to exchange transactions, data and responses. And you will now be replacing many such repeatable components with one single component. The cost of developing the single connector will be recovered in no time by the savings you will by not having to develop and maintain multiple partner connections. If you factor in the faster time-to-market for new partners, the returns on your investment in the AdvantAPI MTMB platform will be even faster.
		</p>
            </div>
        </li>
    </ul>
    <p>&nbsp;</p>
    
</section>

<?php include $_SERVER['DOCUMENT_ROOT']."/web/footer.php"; ?>

