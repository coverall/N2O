#!/bin/sh

if [ ! -h N2O ]; then
	echo -n "Reverting to development tree... "
	if [ -d N2O-encoded ]; then
		rm -r N2O-encoded
	fi
	mv N2O N2O-encoded
	ln -s N2O-dev N2O
	echo "done"
elif [ -h N2O ]; then
	echo -n "Switching to encoded tree... "
	rm N2O 
	mv N2O-encoded N2O
	echo "done"
fi
