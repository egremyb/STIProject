docker build -t sti_project_naludrag .

docker run -it -d -p 8080:80 --name sti_project_naludrag sti_project_naludrag