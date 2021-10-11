# Ticketing App 

## Usage
To get started, make sure you have Docker installed on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running 
```sh 
docker-compose up -d --build
```

Bringing up the Docker Compose network with site instead of just using up, ensures that only our site's containers are brought up at the start, instead of all of the command containers as well. The following are built for our web server, with their exposed ports detailed:


- **nginx** - `:3050`
- **db** - `:3306`
- **api** - `:8000`
- **redis** - `:6379`
- **client** - `:3000` 


migrate database of app t
```sh
docker-compose exec api php artisan migrate
```

### to run seed files, execute
```sh
docker-compose exec api php artisan db:seed
```

*** NOW you can login ***
### Account information
* email: user@null.com
* password: password


You can access your application via localhost, if you're running the containers directly
[link] (http://localhost:3050)

## API Collection of ticketing app on postman
[ticketing app collection](https://documenter.getpostman.com/view/3000372/UV5RkfH7)


## API
* [x] implemented JWT based security in a test Core Web API REST project
* [x] perform queuing and caching using redis to avoid hit the database.

## Authentication
* [x] Login
* [x] Me
* [x] Logout
## Movies
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Halls
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Seats
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
* [x] Get Seats with filter if available OR reseved
## Reservations
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Show Times
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
* [x] Get show times of movie based on movie id


## Tests:
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Halls
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Seats
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
* [x] Get Seats with filter if available OR reserved
## Reservations
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
## Show Times
* [x] Create
* [x] Update
* [x] Delete
* [x] Show
* [x] Get show times of movie based on movie id

## UI:
* [x] Using redux to handle the data flow within a component and reduce the complexity gf the application
* [x] Using HTTP_INTERCEPTOR to inject authorization http headers to every http request 
* [x] Using angular material 
* 	[x] Login page to start session
* 	[x] Show all movies
* 	[x] Show times of movie based on movie id
* 	[x] Select your seats and booking


