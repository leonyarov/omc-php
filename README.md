# omc-php
OMC home project

## Intro
Hi, I had a fun time working on the assignment. I had almost no experience with PHP before, so I learned a lot, docker too, I hope you enjoy the code and find it interesting.

## Tech stack
PHP, Slim, Mysql, React

## How to run
1. docker compose up --build
2. cd frontend && npm install && npm run start
### Couple of notes:
1. Cron tabs does not seem to work, I couldn't make them run in docker, I tried to ask you if I can use supercronic which would probabl work but you said go with cron. Cron's tasks/jobs are ready to go, just need a good package.
2. There is an issue with displaying the data in the front end. THERE IS NO DATA! I tried simulating sensors but it just slows sql so much I could find any workaround to just seed some data at the beggining. There is a simulation of sensor data in Cron's job, but its not working, its possible to run it straight from the command line and it will work fine. 
3. The project is very ROUGH. With enough time, libraries and more detailed and planned approach its possible to make this project better and simpler, When I planned my time there was just not enough to build it properly. I hope you can see the potential in the code and the idea behind it.
    * Example - React code is a mess, its just simple UI with BOOTSTRAP and MUIX to display json data.
    * Example - Insertion, aggregation - it can be MUCH BETTER, its hard to work with 4 lines of documentation and image what it should have been. I TRIED MY BEST
4. Most of the code is working buts its hard to see and test because of LACK OF DATA.
5. ENV FILE CAN BE BETTER, as i dont know php well, I kitbashed something that would work, but its not the best. I hope you can see the potential in the code and the idea behind it.

#### Please review the code and not the front part

thanks, Leon

