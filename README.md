# Working Method

**Assignment**
In resources/opdracht is a file called challenge.json. Goal is to write the content of that
file neatly to a database. Preferably as a background task in a Laravel application. After
finishing, it's important to document the way of thinking behind my approach.


**Requirements**
* The process needs to be interuptable, in a way that it will continue where it left off without duplicating data. 
* The process needs to be extendable. The hypothetical client might have extra wishes it wants added at a later date.
* Don't go overboard on the database design, eloquent models or relations. It's about the structure.
* Only transfer records where the age is between 18 and 65, or where the age is unknown.

* Extra: If the file is 500 times as big, it should still be executable. It should be easy to execute the process on XML or CSV files.


**Before starting, it's important to know how the data in the JSON file is structured**
```
array:9 [▼
    "name" => "Prof. Simeon Green"
    "address" => "328 Bergstrom Heights Suite 709 49592 Lake Allenville"
    "checked" => false
    "description" => "Voluptatibus nihil dolor quaerat. Reprehenderit est molestias quia nihil consectetur voluptatum et.<br>Ea officiis ex ea suscipit dolorem. Ut ab vero fuga.<br>Q ▶"
    "interest" => null
    "date_of_birth" => "1989-03-21T01:11:13+00:00"
    "email" => "nerdman@cormier.net"
    "account" => "556436171909"
    "credit_card" => array:4 [▼
        "type" => "Visa"
        "number" => "4532383564703"
        "name" => "Brooks Hudson"
        "expirationDate" => "12/19"
    ]
]
```

**The way I want to approach this assignment is**

The main process is a job / controller called MigrateData. 
The process will use JSONReader, https://github.com/pcrov/JsonReader, to parse JSON file line by line.
This allows the process to circumvent PHP's limited memory.

Per entry a new user and creditcard model is instantiated, allowing for model functions to be used. If the model passes all
requirements, it's queued as a seperate, batchable? job. 

Then, once it's finished. The queue can be ran, or it's already running in the meantime, migrating each entry seperatly to the database.
Thus, allowing the process to be interruptible. 

* Working with Jobs and Queues. https://laravel.com/docs/8.x/queues
- The queue should be set to database in the .env file.


I thought about using batchable jobs to split the json file into jobs per 100 rows. But this meant still having to load the JSON file in first. So
JSON reader is the way to go I think. It should still be possible to code it a bit dynamic and batch the jobs per 100 entries. 