# remitbroker
RemitBroker is an intermediary platform to allow money transfer companies to exchange transaction information without having to build point-to-point connections with each other's systems. 
 
The application has two main components: 
  
- The API 
Developed using nodejs and mogodb 
Used to post, get, put (modify) and delete (cancel) transactional data. 
   
- The Admin Console 
Developed using PHP/Laravel and MySQL 
Used for managing API access, mapping partners, viewing data posted on broker. 
    
- Website 
There is also a supporting website, for information purposes only. 
     
The code is structured as follows: 
      
RemitBroker 
| 
|___admin 
| 
|___api 
| 
|___www
