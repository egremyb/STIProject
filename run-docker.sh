#!/bin/bash
docker pull b3cauda/sti_project2
docker run -it -d -p 8080:80 --name sti_project2 b3cauda/sti_project2
