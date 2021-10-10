# Ticketing App 

## Usage
To get started, make sure you have Docker installed on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running docker-compose up -d --build.

Bringing up the Docker Compose network with site instead of just using up, ensures that only our site's containers are brought up at the start, instead of all of the command containers as well. The following are built for our web server, with their exposed ports detailed:


- **nginx** - `:3050`
- **db** - `:3306`
- **api** - `:8000`
- **redis** - `:6379`
- **client** - `:3000` 


migrate database of app to your database
```sh
docker-compose exec api php artisan migrate
```

### to run seed files, execute
```sh
docker-compose exec api php artisan db:seed
```


You can access your application via localhost, if you're running the containers directly
[link] (http://localhost:3050)
