#!/bin/bash

read -p "Enter Github Username: " NAME

name=$NAME

if [ -d "../project-3135" ]; then
	echo "This Repo Has Already Been Cloned"
else
	echo "Cloning Repo..."
	cd ~
	sleep 2
	git clone git@github.com:$name/project-3135.git
	echo "Building Backup Folder..."
	sleep 2
	mkdir ~/recovery-3135
fi


