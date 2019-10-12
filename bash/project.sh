#!/bin/sh


agent_name="cf_project:status"

check_alive()
{
    status=`ps ax | grep "$agent_name" | grep -v "grep" |wc -l`
    if [ $status -ne 0 ]; then
    echo "process already exist"
        exit 1
    fi
}


check_alive

echo "start process....."
cd "/home/git/prod/crowdfunding"

nohup php bin/console cf_project:status >/dev/null 2>&1 &
