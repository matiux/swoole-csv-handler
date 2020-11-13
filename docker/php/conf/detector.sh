#!/bin/bash

if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        echo 'Linux'
elif [[ "$OSTYPE" == "darwin"* ]]; then
        echo 'Mac'
elif [[ "$OSTYPE" == "cygwin" ]]; then
        echo 'Win1'
elif [[ "$OSTYPE" == "msys" ]]; then
        echo 'Win2'
elif [[ "$OSTYPE" == "win32" ]]; then
        echo 'O.o'
elif [[ "$OSTYPE" == "freebsd"* ]]; then
        echo 'Freebsd'
else
        echo 'Mmmmm'
fi