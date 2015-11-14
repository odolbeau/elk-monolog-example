#!/bin/bash

WEAR[0]="bermuda"
WEAR[1]="pants"
WEAR[2]="bermuda"

COLOR[0]="red"
COLOR[1]="blue"
COLOR[2]="brown"
COLOR[3]="yellow"
COLOR[4]="purple"
COLOR[5]="white"
COLOR[6]="black"

./bermuda ${WEAR[$RANDOM%3]} -c ${COLOR[$RANDOM%7]}
