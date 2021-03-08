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


**Thought Process During Development**

I thought about using batchable jobs to split the json file into jobs per 100 rows. But this meant still having to load the JSON file in first. So
JSON reader is the way to go I think. It should still be possible to code it a bit dynamic and batch the jobs per 100 entries.

For testing convenience I first build the code out in a controller. So I don't have to dispatch jobs every single time. After messing around a bit it
works quite nicely I think. It works like this:
You give it a path to the file, and the minimum and maximum age the user may have.
It then instantiates the JsonReader, which opens the Json file.  JsonReader streams the file instead of loading it in memory.

For each entry it comes across it calls HandleUser, giving it the user data.

HandleUser makes a new User model, and fills it with the data that is readely available. The date of birth is handled by a function on the user model, which
transform the variety of the given birthdays into a standard format.

Then there's a check if the user matches the age criteria, is it between the given ages or is it null? Handled by another function on the user model. If it 
passes the criteria, it's persisted to the database and the creditcard data is loaded in. The expiration date is transformed to a date string using Carbon, 
then the creditcard is also persisted. And done!

First I made the whole thing in a controller, next up is transforming it to jobs. My first idea is having each user be a seperate job. I wonder at how many jobs
in the queue Laravel caps out at? After trying it out, not at 10,000 at least. It seems to work just fine, I'll keep it this way.

For the page styling I used TailwindCSS, so make sure you run npm install first. I've had some fun in figuring out how Laravel file upload works. Did you know
that if you just let it do it's thing a Json file is stored as txt? Why!?

I know it's not done to do it all in the routes file, but I didn't deem it neccecary for this assignment to make it run through controllers. If the front does not
work, there's a commented bit at the routes file that instantiates the job instantly.

The  filetype is checked in a job, to keep all logic seperated. A different idea I played with was checking it on input. Only running the job if the given file
is a json (or xml, or csv). 