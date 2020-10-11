# STI Project 1

## Table of Content
- [Introduction](#Introduction)  
- [Installation](#Installation)
- [Structure](#Structure)
- [Usage](#Usage)

## Introduction

As part of the STI course we were asked to create a website that will simulate an web email system using a database.
 
In this site you have two different roles, Administrator and Collaborator.  
If you are an Administrator, you will be able to manage the users of the site.

You cannot access the site if you are not logged in. If you try to access other pages you will be redirected in the login page
 
Here are the functions once you signed in the site :

- As a collaborator you can do :
    - See the email sent to you with the following information : the date when the email was sent, the sender and the subject.
    - Next to the information of the email you can choose if you want to delete it, see the details of it(See more information such as the sender and the body) or reply to it
    - Write a new message by filling a form were you specify the sender the subject and the description.
    - Change your password
- As an Administrator you can do:
    - Same actions as a collaborator
    - Add, delete, update a user.

## Installation

Here is the explaination on how to install the site :

1. Clone the project using the command git clone :  
  `git clone git@github.com:Naludrag/STIProject.git`

2. Run the script `run-docker.sh`. This script will build a docker image named `sti_project_naludrag` and will run it on the `8080` port of your localhost.

## Structure

## Usage






